<?php

namespace Modules\Spider\Services;

use Modules\Blog\Entities\Article;
use Modules\Spider\Contracts\SpiderArticleInterface;
use Modules\Spider\Entities\SpiderLink;
use Modules\Spider\Entities\SpiderTask;
use Modules\Spider\Entities\SpiderTasksLog;
use Illuminate\Database\Eloquent\Model;
use zxf\dom\Document;
use zxf\dom\Query;

/**
 * 执行爬虫采集任务
 */
class SpiderHandleService
{
    // 采集内容时候，需要提取 时间格式 的规则字段
    // 支持格式:2022-05-30,2022/05/30,2022.05.30,2022年05月30日,2022-05-30 12:12:12
    protected $getTimeOrDate = ['publish_time', 'time', 'date'];

    // 当前正在执行的任务
    protected $currentTask;
    // 当前正在采集的url
    protected $currentUrl = '';
    // 采集任务开始时间
    protected $startTime;
    // 采集失败信息列表
    protected $failList = [];

    // 成功执行次数
    protected static $successTotal = 0;
    // 失败执行次数
    protected static $failTotal = 0;
    // 获取文章数
    protected static $articleTotal = 0;

    /**
     * 执行爬虫采集数据 入口
     */
    public function process(SpiderTask $task)
    {
        set_time_limit(0);

        $this->currentTask = $task;
        // 执行爬虫任务 扩展属性
        $this->extendHandle('start');

        try {
            if ($task->type == SpiderTask::TYPE_LIST) {
                $this->getList($task);
            } else {
                $this->getContent($task);
            }
        } catch (\Exception $err) {
            $this->failList[] = [
                'id'  => $this->currentTask->id,
                'url' => $this->currentUrl,
                'msg' => '[主任务]采集异常:' . $err->getMessage(),
            ];
            $this->reportErr($err, '[主任务]采集异常，id:' . $task->id);
        }

        $this->currentTask = $task;
        // 执行爬虫任务 扩展属性
        $this->extendHandle('end');
    }

    /**
     * 获取 文章列表类 数据
     *
     * @param SpiderTask  $task
     * @param string|null $url 可为空，为空时候去$task里面去取
     *
     * @return bool|mixed|null
     */
    private function getList(SpiderTask $task, string|null $url = '')
    {
        if ($task->type != SpiderTask::TYPE_LIST) {
            return $this->getContent($task, $url);
        }

        $this->currentTask = $task;
        // 先初始化为成功
        $task->run_status = SpiderTask::RUN_STATUS_SUCCESS;
        $task->run_at     = time();

        // 判断url 地址
        $url              = empty($url) ? $task->url : $url;
        $this->currentUrl = $url;

        // 执行爬虫任务 扩展属性
        $this->extendHandle('before');
        if (empty($url)) {
            self::$failTotal++;
            $task->run_status = SpiderTask::RUN_STATUS_FAIL;
            $task->save();
            // 执行爬虫任务 扩展属性
            $this->extendHandle('fail', ['task_id' => $task->id, 'type' => 'list'], '[config-err]缺少采集地址url');
            $this->failList[] = [
                'id'  => $this->currentTask->id,
                'url' => $this->currentUrl,
                'msg' => 'getList:[config-err]缺少采集地址url',
            ];
            return false;
        }
        $url              = (mb_substr($url, 0, 4, "utf-8") == 'http') ? $url : (($task->domain_prefix ?? '') . $url);
        $url              = trim(trim($url), '.');
        $this->currentUrl = $url;

        if (empty($task->rules)) {
            self::$failTotal++;
            $task->run_status = SpiderTask::RUN_STATUS_FAIL;
            $task->save();

            // 执行爬虫任务 扩展属性
            $this->extendHandle('fail', ['task_id' => $task->id, 'type' => 'list'], '[config-err]缺少采集规则');
            $this->failList[] = [
                'id'  => $this->currentTask->id,
                'url' => $this->currentUrl,
                'msg' => 'getList:[config-err]缺少采集规则',
            ];
            return false;
        }
        $document = new Document($url, true);

        $rules = is_array($task->rules) ? $task->rules : json_decode($task->rules, true);

        $emptyFieldAndRule = []; // 没有采集到数据的字段和规则

        $resData = []; // 采集结果
        foreach ($rules as $field => $rule) {
            // $list 表示 通过 $rule 规则采集到的结果
            // $needClear 表示 采集到 $result 的规则是否需要手动清洗数据
            list($list, $needClear, $isText) = $this->exploratoryFindAndReturnResult($document, $rule);
            if (empty($list) || empty($list[0])) {
                self::$failTotal++;
                $task->run_status = SpiderTask::RUN_STATUS_FAIL;
                // 没有采集到数据内容字段
                $emptyFieldAndRule[] = [
                    'task_id' => $task->id,
                    'filed'   => $field,
                    'rule'    => $rule,
                ];
                continue;
            }

            foreach ($list as $row) {
                $href = trim($row->attr('href'));
                // TODO 1、保存列表内容 ，暂无该步骤，直接跳过
                // sdk 存在不足导致 $isText 规则的 需要 通过->html() 方法才能获取到内容
                $rowText = ($isText || !$needClear) ? trim($row->html()) : trim($row->text());
                // /text() 规则需要手动清洗
                if ($isText) {
                    $rowText = $this->trimAll(truncate($rowText));
                }
                $resData[] = [
                    'text' => $rowText,
                    'href' => $href,
                ];
                // 2、如果有下一个任务则进入下一个任务
                $this->next($task, $href);
            }
        }

        $task->save();

        if (!empty($emptyFieldAndRule)) {
            self::$failTotal++;
            // 执行爬虫任务 扩展属性
            $this->extendHandle('fail', ['empty_field' => $emptyFieldAndRule, 'rule' => $task->rules, 'type' => 'list'], '[warning]采集字段缺失');
            $this->failList[] = [
                'id'   => $this->currentTask->id,
                'url'  => $this->currentUrl,
                'msg'  => '[warning]采集字段缺失',
                'data' => ['empty_field' => $emptyFieldAndRule, 'rule' => $task->rules, 'type' => 'list'],
            ];
        } else {
            self::$successTotal++;
            $this->extendHandle('success', $resData);
        }
        // 执行爬虫任务 扩展属性
        $this->extendHandle('after');

        return true;
    }

    /**
     * 获取非列表(文章正文)类数据
     *
     * @param SpiderTask  $task
     * @param string|null $url 可为空，为空时候去$task里面去取
     *
     * @return bool|mixed|void|null
     */
    private function getContent(SpiderTask $task, string|null $url = '')
    {
        if ($task->type == SpiderTask::TYPE_LIST) {
            return $this->getList($task, $url);
        }
        $this->currentTask = $task;
        // 初始化为成功
        $task->run_status = SpiderTask::RUN_STATUS_SUCCESS;
        $task->run_at     = time();

        $this->currentUrl = $url;

        // 执行爬虫任务 扩展属性
        $this->extendHandle('before');

        // 判断url 地址
        $url = empty($url) ? $task->url : $url;
        if (empty($url)) {
            self::$failTotal++;
            $task->run_status = SpiderTask::RUN_STATUS_FAIL;
            $task->save();

            // 执行爬虫任务 扩展属性
            $this->extendHandle('fail', ['task_id' => $task->id, 'type' => 'content'], '[err]缺少采集地址url');

            $this->failList[] = [
                'id'  => $this->currentTask->id,
                'url' => $this->currentUrl,
                'msg' => '[err]缺少采集地址url',
            ];
            $this->next($task);
            return false;
        }
        $url              = (mb_substr($url, 0, 4, "utf-8") == 'http') ? $url : (($task->domain_prefix ?? '') . $url);
        $url              = trim(trim($url), '.');
        $this->currentUrl = $url;

        if (empty($task->rules)) {
            // 缺少采集规则
            self::$failTotal++;
            $task->run_status = SpiderTask::RUN_STATUS_FAIL;
            $task->save();

            // 执行爬虫任务 扩展属性
            $this->extendHandle('fail', ['task_id' => $task->id, 'type' => 'content'], '[err]缺少采集规则');
            $this->failList[] = [
                'id'  => $this->currentTask->id,
                'url' => $this->currentUrl,
                'msg' => '[err]缺少采集规则',
            ];
            $this->next($task);
            return false;
        }

        // 检查是否爬取过此 url
        if (!SpiderLink::query()->where('url', $url)->exists()) {

            $rules = is_array($task->rules) ? $task->rules : json_decode($task->rules, true);

            $document = new Document($url, true);

            $data              = [];
            $emptyFieldAndRule = []; // 没有采集到数据的字段和规则
            foreach ($rules as $field => $rule) {
                // 会存在 一个 内容页面 有多种页面展示（即 多种规格），需要 遍历 获取数据，只要有任意一个规则有效就视为抓取成功
                // $result 表示 通过 $rule 规则采集到的结果
                // $needClear 表示 采集到 $result 的规则是否需要手动清洗数据
                // $isText 表示 采集到 $result 的规则是否是通过 /text() 规则采集到的 ，优先级大于 $needClear
                list($result, $needClear, $isText) = $this->exploratoryFindAndReturnResult($document, $rule);

                if (empty($result) || empty($result[0])) {
                    // 没有采集到数据内容字段
                    $emptyFieldAndRule[] = [
                        'task_id' => $task->id,
                        'filed'   => $field,
                        'rule'    => $rule,
                    ];
                    continue;
                }
                $result = $result[0];

                // 是否清洗html
                try {
                    // sdk 存在不足导致 $isText 规则的 需要 通过->html() 方法才能获取到内容
                    $result = ($isText || !$needClear) ? trim($result->html()) : trim($result->text());
                } catch (\Exception $err) {
                    $this->reportErr($err, 'html内容清洗异常');
                    $this->failList[] = [
                        'id'  => $this->currentTask->id,
                        'url' => $this->currentUrl,
                        'msg' => 'html内容清洗异常,field:' . $field,
                    ];
                    continue;
                }
                // /text() 规则需要手动清洗
                if ($isText) {
                    $result = $this->trimAll(truncate($result));
                }
                // 正则匹配时间格式 日期格式
                if (!empty($this->getTimeOrDate) && in_array($field, $this->getTimeOrDate)) {
                    $result = $this->getTimeOrDate($result);
                }
                $data[$field] = $result;
            }
            if (!empty($emptyFieldAndRule)) {
                if (!empty($data)) {
                    // 执行爬虫任务 扩展属性
                    $this->extendHandle('fail', ['empty_field' => $emptyFieldAndRule, 'get_field' => array_keys($data), 'type' => 'content'], '[warning]采集字段缺失');
                    $this->failList[] = [
                        'id'   => $this->currentTask->id,
                        'url'  => $this->currentUrl,
                        'msg'  => '[warning]采集字段缺失',
                        'data' => ['empty_field' => $emptyFieldAndRule, 'get_field' => array_keys($data), 'type' => 'content'],
                    ];
                } else {
                    self::$failTotal++;
                    // 执行爬虫任务 扩展属性
                    $this->extendHandle('fail', ['empty_field' => $emptyFieldAndRule, 'type' => 'content'], '[null]没有采集到数据');
                    $this->failList[] = [
                        'id'   => $this->currentTask->id,
                        'url'  => $this->currentUrl,
                        'msg'  => '[null]没有采集到数据',
                        'data' => ['empty_field' => $emptyFieldAndRule, 'type' => 'content'],
                    ];
                }
            }
            if (!empty($data) && !empty($data['title']) && !empty($data['content'])) {
                try {
                    // 记录采集url 记录 ,防止重复添加，所以加上 try
                    SpiderLink::create(['url' => $url]);

                    // 执行爬虫任务 扩展属性
                    $this->extendHandle('success', $data);

                    self::$successTotal++;
                    self::$articleTotal++;

                } catch (\Exception $e) {
                    // 进入到此处说明是重复采集了，不记录异常
                }
            } else {
                $this->extendHandle('fail', $data, '缺失采集数据必要字段title和content');
                $this->failList[] = [
                    'id'   => $this->currentTask->id,
                    'url'  => $this->currentUrl,
                    'msg'  => '缺失采集数据必要字段title和content',
                    'data' => $data,
                ];
            }
        }

        $task->save();
        // 执行爬虫任务 扩展属性
        $this->extendHandle('after');
        // 如果有下一个任务则进入下一个任务
        $this->next($task);

    }

    /**
     * 判断并执行下一个任务
     *
     * @param SpiderTask  $task
     * @param string|null $url
     *
     * @return void
     */
    private function next(SpiderTask $task, string|null $url = '')
    {
        try {
            // 如果有下一个任务
            $nextTask = (!empty($task->next_tasks_id) && is_numeric($task->next_tasks_id)) ? SpiderTask::where('id', $task->next_tasks_id)->first() : null;
            if ($nextTask) {
                $this->currentTask = $nextTask;
                $this->getList($nextTask, $url);
            }
        } catch (\Exception $err) {
            if (!empty($nextTask)) {
                $this->reportErr($err, '任务执行失败,id:' . $nextTask->id);
                $this->failList[] = [
                    'id'  => $this->currentTask->id,
                    'url' => $this->currentUrl,
                    'msg' => '任务执行失败,id:' . $nextTask->id . ',' . $err->getMessage(),
                ];
            }
        }
    }

    /**
     * 报告在执行某个采集任务时候遇到的异常
     *
     * @param \Exception $err
     * @param string     $title 提示标题
     *
     * @return void
     */
    private function reportErr(\Exception $err, string $title = '采集异常')
    {
        self::$failTotal++;
        try {
            $task = $this->currentTask;
            $url  = $this->currentUrl ?? $task->url;
            SpiderTasksLog::writeLog($task, $title, [
                '异常信息' => $err->getMessage(),// 返回用户自定义的异常信息
                '异常代码' => $err->getCode(),   // 返回用户自定义的异常代码
                '异常文件' => $err->getFile(),   // 返回发生异常的PHP程序文件名
                '异常行号' => $err->getLine(),   // 返回发生异常的代码所在行的行号
                '异常路线' => $err->getTrace(),  // 以数组形式返回跟踪异常每一步传递的路线
            ], $url, SpiderTasksLog::STATUS_FAIL);
        } catch (\Exception $e) {
        }

    }

    /**
     * 获取dom 查找规则是 css选择器还是 xpath
     *
     * @param string|null $str
     *
     * @return string
     */
    private function getRuleType(string|null $str): string
    {
        return empty($str) ? Query::TYPE_CSS : (in_array(mb_substr($str, 0, 1, "utf-8"), ['.', '#']) ? Query::TYPE_CSS : Query::TYPE_XPATH);
    }

    /**
     * 因为 xpath 中取 「/text()」 的时候会导致取不出数据来，针对此情况，先取html,再手动清洗 为text
     *
     * @param string|null $rule
     *
     * @return array [ xpath|CSS-selector , 规则中是否使用了/text() ]
     */
    private function whenXpathGetTextReturnHtmlAndFlag(string|null $rule): array
    {
        // 判断 xpath 是否是取 「/text()」
        return empty($rule) ? ['', false] : ((mb_substr($rule, -7, 7, "utf-8") == '/text()') ? [mb_substr($rule, 0, -7, "utf-8"), true] : [$rule, false]);
    }

    // 去除所有空格和换行
    private function trimAll(string|null $str): string
    {
        return empty($str) ? '' : trim(str_replace(PHP_EOL, '', $str));
    }

    /**
     * 如果获取 某字段 的规则有多个「数组」，则逐一尝试去验证规则，只要有一个规则采集到数据，就视为采集成功
     * 此方法只负责处理 通过指定规则获取采集数据
     *
     * @param Document     $document 采集到的html 页面对象
     * @param array|string $rules    采集规则 数组或者字符串
     *
     * @return array
     * @throws \zxf\dom\Exceptions\InvalidSelectorException
     */
    private function exploratoryFindAndReturnResult(Document $document, array|string $rules): array
    {
        // $needClear 采集内容是否需要清洗html格式；1需要，2不需要
        $needClear = empty($rules['need_clear']) || $rules['need_clear'] == 1;        // 采集到数据时候使用的规则，是否需要手动清理一次,默认清洗
        $result    = [];                                                              // 没有采集到的数据返回空
        $isText    = false;                                                           // 采集规则时候是否使用了text()
        if (empty($document) || empty($rules)) {
            return [$result, $needClear];
        }
        if (is_array($rules)) {
            // 采集规则，支持的格式 1、 ['规则一','规则二','...']; 2、['route'=>['规则一','规则二','...']]; 3、['need_clear'=>1|2,'route'=>['规则一','规则二','...']]
            $routeList = !empty($rules['route']) ? (array)$rules['route'] : (empty(array_filter($rules, function ($k) {
                return !is_numeric($k);
            }, ARRAY_FILTER_USE_KEY)) ? $rules : []);
            // 会存在 一个 内容页面 有多种页面展示（即 多种规格），需要 遍历 获取数据，只要有任意一个规则有效就视为抓取成功
            // 逐个规格 探索性 的 去采集
            foreach ($routeList as $item) {
                // 判断是 xpath 还是css 选择器
                $type = $this->getRuleType($item);
                // xpath 中结尾使用了「/text()」时候，$isText才为true,此情况 爬取 html 再手动清洗html为 text
                list($rule, $isText) = $this->whenXpathGetTextReturnHtmlAndFlag($item);
                $result = $document->find($rule, $type);
                if (!empty($result) && !empty($result[0])) {
                    break;
                }
            }
        } else {
            // 采集规则，$rules 直接就表示单个 '规则地址' 字符串
            // 判断是 xpath 还是css 选择器
            $type = $this->getRuleType($rules);
            // xpath 中结尾使用了「/text()」时候，$needClear才为true,此情况 爬取 html 再手动清洗html为 text
            list($rule, $isText) = $this->whenXpathGetTextReturnHtmlAndFlag($rules);
            $result = $document->find($rule, $type);
        }

        return [$result, $needClear, $isText];
    }

    // 正则匹配时间格式 日期格式 preg_match_all | preg_match, 支持格式:2022-05-30,2022/05/30,2022.05.30,2022年05月30日,2022-05-30 12:12:12
    private function getTimeOrDate(string|null $string)
    {
        if (!empty($string) && preg_match("/(\d{2,4})(-|\/|.|,|、|年|\s)(\d{1,2})(-|\/|.|,|、|月|\s)(\d{1,2})(日)?(\s+(\d{1,2})\:(\d{1,2})\:(\d{1,2}))?/", $string, $parts)) {
            if (isset($parts[0]) && !empty($parts[0])) {
                $string = $parts[0];
            }
        }
        return $string;
    }

    /**
     * 在执行爬虫采集的各个阶段时候的一些调度
     *
     * @param string $processName 过程名称，支持 start(进入到爬虫任务)|before(爬虫执行前)|after(爬虫执行后)|fail(爬虫执行失败时)|success(爬虫执行成功时)
     * @param array  $data        写入日志的数据
     * @param string $errMsg      错误日志的标题
     *
     * @return void
     */
    private function extendHandle(string $processName = '', array $data = [], string $errMsg = ''): void
    {
        switch ($processName) {
            case 'start': // 进入到爬虫任务
                $this->extendSceneStartHandle();
                break;
            case 'fail': // 采集失败
                $this->extendSceneFailHandle($data, $errMsg);
                break;
            case 'before': // 爬虫执行前
                $this->extendSceneBeforeHandle($data);
                break;
            case 'after': // 爬虫执行后
                $this->extendSceneAfterHandle($data);
                break;
            case 'success': // 采集成功
                $this->extendSceneSuccessHandle($data);
                break;
            case 'end': // 完成本轮爬虫任务
                $this->extendSceneEndHandle();
                break;
        }
    }

    // 采集开始的场景
    private function extendSceneStartHandle()
    {
        // 初始化计数
        self::$successTotal = 0;
        self::$failTotal    = 0;
        self::$articleTotal = 0;
        $this->failList     = [];

        // 任务开始时间
        $this->startTime = microtime(true);
        SpiderTasksLog::writeLog($this->currentTask, '[start]「' . $this->currentTask->name . '」:开始执行采集任务,id:' . $this->currentTask->id);
    }

    // 采集失败的场景
    private function extendSceneFailHandle(array $data = [], string $errMsg = '')
    {
        SpiderTasksLog::writeLog($this->currentTask, $errMsg || '采集失败', $data, $this->currentUrl, SpiderTasksLog::STATUS_FAIL);
    }

    // 采集前的场景
    private function extendSceneBeforeHandle(array $data = [])
    {

    }

    // 采集后的场景
    private function extendSceneAfterHandle(array $data = [])
    {

    }

    // 采集成功的场景
    private function extendSceneSuccessHandle(array $data = [])
    {
        $task       = $this->currentTask;
        $extend     = $task->extend;
        $sceneValue = '';
        $saveValve  = '';
        // $sceneName : 场景名称
        if (empty($extend) || empty($sceneValue = $extend['success']) || empty($saveValve = $sceneValue['save'])) {
            $saveValve = 'default';
        }

        // 不是列表时候 保存 $data 数据 为文章
        if ($task->type != SpiderTask::TYPE_LIST) {
            if ($saveValve && !empty($data) && !empty($data['title']) && !empty($data['content'])) {
                if (!empty($saveModelClass = SpiderTask::$extendSuccessSaveToModels[$saveValve])) {
                    $saveModel = new $saveModelClass();
                    // 是否继承模型
                    if (($saveModel instanceof Model) && ($saveModel instanceof SpiderArticleInterface)) {
                        try {
                            // 保存下载下来的文章数据
                            $data['user_id']     = 0;
                            $data['classify_id'] = $task->classify_id;
                            $data['type']        = $saveModel::getEditorTypeValue();
                            $data['source_type'] = $saveModel::getSourceTypeSpiderValue();
                            $data['status']      = $saveModel::getStatusNormalValue();

                            $saveModel::create($data);
                            return true;
                        } catch (\Exception $e) {
                            $this->reportErr($e, '网页内容数据写入失败');
                            $this->failList[] = [
                                'id'             => $this->currentTask->id,
                                'url'            => $this->currentUrl,
                                'msg'            => '网页内容数据写入失败:' . $e->getMessage(),
                                'data'           => $data,
                                'saveModelClass' => $saveModelClass,
                            ];
                            return false;
                        }
                    } else {
                        try {
                            throw new \Exception('文章模型类 未正确继承基类 Model 和 SpiderArticleInterface:' . $saveModelClass);
                        } catch (\Exception $e) {
                            $this->reportErr($e, '爬虫写入文章类未正确继承基类');
                            $this->failList[] = [
                                'id'  => $this->currentTask->id,
                                'url' => $this->currentUrl,
                                'msg' => $e->getMessage(),
                            ];
                        }
                    }
                }
            }
        } else {
            // TODO 是列表时候保存列表数据
        }
    }

    // 采集结束的场景
    private function extendSceneEndHandle()
    {
        // 结束时间(秒)
        $second = round(bcsub(microtime(true), $this->startTime, 0), 3);
        $data   = ['article_num' => self::$articleTotal . '-篇', 'success' => self::$successTotal . '-次', 'fail' => self::$failTotal . '-次', 'time' => $second . '秒'];

        SpiderTasksLog::writeLog($this->currentTask, '[end]「' . $this->currentTask->name . '」:采集结束,id:' . $this->currentTask->id, $data);

        $task        = $this->currentTask;
        $extend      = $task->extend;
        $sceneValue  = '';
        $noticeValve = '';
        // $sceneName : 场景名称
        if (empty($extend) || empty($sceneValue = $extend['end']) || empty($noticeValve = $sceneValue['notice'])) {
            $noticeValve = 'default';
        }
        if ($noticeValve) {
            // TODO 发送消息通知
            // $this->currentTask->name 任务执行结束
            // 处理 $this->failList 、$data 等信息
        }

        // 全部处理完后重新初始化
        self::$successTotal = 0;
        self::$failTotal    = 0;
        self::$articleTotal = 0;
        $this->failList     = [];
        $this->currentTask  = '';
        $this->currentUrl   = '';
    }

    // 后续应该把此操作迁移到文章模型中
    private function saveToArticle(array $data, int $classify_id = 0)
    {
        try {
            // 保存下载下来的文章数据
            $data['user_id']     = 0;
            $data['classify_id'] = $classify_id;
            $data['type']        = Article::TYPE_EDITOR;
            $data['source_type'] = Article::SOURCE_TYPE_SPIDER;
            $data['status']      = Article::STATUS_NORMAL;
            Article::insertRow($data);
            return true;
        } catch (\Exception $e) {
            $this->reportErr($e, '网页内容数据写入失败');
            $this->failList[] = [
                'id'   => $this->currentTask->id,
                'url'  => $this->currentUrl,
                'msg'  => '网页内容数据写入失败:',
                'data' => $data,
            ];
            return false;
        }
    }

}
