<?php

namespace Modules\Spider\Services;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Schema;
use Modules\Spider\Entities\SpiderTask;
use Modules\Spider\Entities\SpiderTasksLog;

/**
 * 爬虫任务
 */
class SpiderTasksService
{
    /**
     * 调度用户自定义定时任务[通过 Console/Kernel.php 进行调度]
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    public static function customCronTasks(Schedule $schedule)
    {
        if (Schema::hasTable('spider_tasks')) {
            try {
                $tasks = SpiderTask::main()->get();
                foreach ($tasks as $task) {
                    $schedule->call(function () use ($task) {
                        // TODO 放在一个数组中，再慢慢的去调度，避免运行超时1分钟
                        try {
                            // 调度采集任务
                            (new SpiderHandleService())->process($task);
                        } catch (\Exception $err) {
                            $model             = SpiderTask::where('id', $task->id)->first();
                            $model->run_status = SpiderTask::RUN_STATUS_FAIL; // 失败
                            $model->run_at     = now()->toDateTimeString();
                            $model->save();
                            try {
                                SpiderTasksLog::writeErr($task, $err);
                            } catch (\Exception $e) {
                            }
                        }
                    })->cron($task->timer);
                }
            } catch (\Exception $e) {
                // 套入此层是为了防止 爬虫异常 而影响到其他 命名定时任务
            }
        }

    }

    /**
     * 解析任务时间
     *
     * @param string $timeStr 定时任务执行的时间 格式为 时:分:天:月:星期, 例如： /5(每5分钟一次)、23:01(每天晚上11时1分)、23:01:1(每月1号的每天晚上11时1分)
     *
     * @return string
     */
    protected static function analyzeTimer(string $timeStr)
    {
        if (empty($timeStr)) {
            return response([
                'code'    => 0,
                'message' => '时间格式错误.',
            ], 412);
        }
        $cronTimeArr   = explode(':', $timeStr);
        $cronFrequency = '';
        if (($count = count($cronTimeArr)) > 1) {
            list($cronTimeArr[1], $cronTimeArr[0]) = $cronTimeArr;
        }
        if ($count > 5) {
            return response([
                'code'    => 0,
                'message' => '时间格式错误.',
            ], 412);
        }
        foreach ($cronTimeArr as $key => $item) {
            $cronFrequency .= ($key > 0 ? ' ' : '') . (int)ltrim($item, '0');
        }
        $cronFrequency .= str_repeat(' *', 5 - $count);
        return $cronFrequency;
    }

}
