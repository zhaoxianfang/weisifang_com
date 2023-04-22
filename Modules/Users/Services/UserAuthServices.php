<?php

namespace Modules\Users\Services;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Services\BaseService;
use Modules\Users\Entities\User;
use Modules\Users\Entities\UserOrigin;

/**
 * 用户登录、退出、获取个人信息等、第三方平台快速登录
 *
 * # 登录
 * 默认模式 手机号+密码 session 登录 web
 * UserAuthServices::instance()->login($request->mobile, $request->password);
 * 设置 手机号+密码 登录 api
 * UserAuthServices::instance()->auth('api')->byToken()->use('password')->login($request->mobile,$request->password);
 * 设置 邮箱号+密码 登录 web
 * UserAuthServices::instance()->byToken()->use('email')->login($request->email,$request->password);
 * 设置 id 方式进行 登录 api
 * UserAuthServices::instance()->auth('api')->byToken()->use('id')->login($request->id);
 * 设置 id + 记住我 方式 登录 api
 * UserAuthServices::instance()->auth('api')->byToken()->use('id')->needRemember()->login($request->id);
 *
 * # 注册
 * UserAuthServices::instance()->register();
 *
 * #获取登录用户的用户信息
 * UserAuthServices::instance()->userInfo();
 *
 * # 发送验证码
 * UserAuthServices::instance()->sendSms('mobile','sms_code');
 *
 * # 第三方快速登录
 * UserAuthServices::instance()->fastLogin();
 */
class UserAuthServices extends BaseService
{

    /**
     * auth 授权的名称
     *
     * @var string 参照 auth.php 配置里面的 guards 例如 web|admin|docs|api|...
     */
    private $authName = 'web';

    /**
     * 是否使用apiToken 方式登录授权
     *
     * @var bool 默认false [true:使用token(jwt|passport|...)方式,false:使用session方式]
     */
    private $replyToken = false;

    /**
     * 登录方式
     *
     * @var string 参见 $loginTypeMap
     */
    private $loginType = 'password';

    /**
     * 是否记住登录状态
     *
     * @var bool
     */
    private $remember = false;

    // 登录方式
    const LOGIN_TYPE_MOBILE  = 'mobile';
    const LOGIN_TYPE_EMAIL   = 'email';
    const LOGIN_TYPE_SMS     = 'sms';
    const LOGIN_TYPE_USER_ID = 'id';

    public static $loginTypeMap = [
        self::LOGIN_TYPE_MOBILE  => '手机号+密码',
        self::LOGIN_TYPE_EMAIL   => '邮箱号+密码',
        self::LOGIN_TYPE_SMS     => '短信验证码',
        self::LOGIN_TYPE_USER_ID => '用户id',
    ];

    /**
     * 选择一个授权方式/模块 参照 auth.php 配置里面的 guards
     *
     * @param string $name 例如 web|admin|docs|api|...
     *
     * @return $this
     */
    public function auth(string $name = 'web')
    {
        $this->authName = $name;
        return $this;
    }

    /**
     * 设置授权方式 支持session或token两种方式
     *
     * @param bool $replyToken [true:使用token(jwt|passport|...)方式,false:使用session方式]
     *
     * @return $this
     */
    public function byToken(bool $replyToken = true)
    {
        $this->replyToken = $replyToken;
        return $this;
    }

    /**
     * 选择登录方式
     *
     * @param string $type 参见 self::$loginTypeMap
     *
     * @return $this
     * @throws Exception
     */
    public function use(string $type = self::LOGIN_TYPE_MOBILE)
    {
        if (empty(self::$loginTypeMap[$type])) {
            throw new \Exception('不支持的登录方式');
        }
        $this->loginType = $type;
        return $this;
    }

    /**
     * 获取用户信息
     *
     * @return array
     */
    public function userInfo()
    {
        if (auth($this->authName)->check()) {
            return [
                'code'    => 200,
                'message' => '获取成功',
                'data'    => [
                    auth($this->authName)->user(),
                ],
            ];
        } else {
            return [
                'code'    => 403,
                'message' => '暂未登录，无法获取用户信息',
            ];
        }
    }

    /**
     * 是否记住登录状态
     *
     * @param bool $remember
     *
     * @return $this
     */
    public function needRemember(bool $remember = true)
    {
        $this->remember = $remember;
        return $this;
    }

    /**
     * 使用账号 和密码进行登录
     *
     * @param string      $account  账号(手机号、邮箱号、用户id)
     * @param string|null $password 密码或短信验证码（账号为用户id时候为空）
     *
     * @return array
     */
    public function login(string $account, ?string $password = '')
    {
        $data    = [];
        $isLogin = false;

        // 使用session 登录
        if (!$this->replyToken) {
            if ($this->loginType == self::LOGIN_TYPE_USER_ID) {
                $isLogin = auth($this->authName)->loginUsingId($account, $this->remember);
            }
            if ($this->loginType == self::LOGIN_TYPE_MOBILE) {
                $isLogin = auth($this->authName)->attempt(['mobile' => $account, 'password' => $password], $this->remember);
            }
            if ($this->loginType == self::LOGIN_TYPE_EMAIL) {
                $isLogin = auth($this->authName)->attempt(['email' => $account, 'password' => $password], $this->remember);
            }
            if ($this->loginType == self::LOGIN_TYPE_SMS) {
                if ($this->checkSms($account, $password)) {
                    $isLogin = auth($this->authName)->attempt(['mobile' => $account], $this->remember);
                }
            }
        } else {
            // 使用 api token 登录
            $accountFieldName = $this->loginType == self::LOGIN_TYPE_SMS ? self::LOGIN_TYPE_MOBILE : $this->loginType;
            $user             = User::where($accountFieldName, $account)->first();

            if ($user) {
                if ($this->loginType == self::LOGIN_TYPE_SMS) {
                    $isLogin = $this->checkSms($account, $password);
                } elseif ($this->loginType == self::LOGIN_TYPE_USER_ID || Hash::check($password, $user->password)) {
                    $isLogin = true;
                }
                if ($isLogin) {
                    $authInfo = $user->createToken($this->authName);
                    $token    = $authInfo->accessToken;
                    if ($token) {
                        $data = [
                            'access_token' => $token,
                            'token_type'   => 'Bearer',
                            // 'client_id'  => $authInfo->client_id
                        ];
                    } else {
                        $isLogin = false;
                    }
                }
            }
        }
        if ($isLogin && auth($this->authName)->check()) {
            $code    = 200;
            $message = '登录成功';
            $user    = auth($this->authName)->user();
            if ($user->status !== User::STATUS_NORMAL) {
                $isLogin = false;
                $this->logout();
                $code    = 403;
                $message = $user->status == User::STATUS_FREEZE ? '此账号已冻结,无法登录' : '此账号未激活,请先联系管理员再试';
            }
        } else {
            $code    = 403;
            $message = $this->loginType == self::LOGIN_TYPE_SMS ? '账号或者验证码错误' : '账号或者密码错误';
            $this->logout();
        }

        // 检查关联企业
        //    if ($this->useApiToken) {
        //        // 如果是 api token 方式，需要手动修改 header 头 让auth() 可以获取当前用户信息
        //        request()->headers->set('Authorization', "Bearer " . $token);
        //    }

        //TODO 记录用户来源问题

        return $isLogin ? compact('code', 'message', 'data') : compact('code', 'message');
    }

    /**
     * 验证 短信验证码是否有效
     *
     * @param string $mobile
     * @param string $smsCode
     *
     * @return bool
     */
    private function checkSms(string $mobile = '', string $smsCode = ''): bool
    {
        // TODO
        return false;
    }

    // 用户退出
    public function logout(): array
    {
        auth($this->authName)->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return [
            'code'    => 200,
            'message' => '退出成功',
        ];
    }

    // 注册
    public function register($data): array
    {
        $code    = 412;
        $message = '注册失败!';
        DB::beginTransaction();
        try {
            // 注销包含字段
            $contains = ['username', 'nickname', 'mobile', 'gender', 'email', 'password'];
            if (!Arr::has($data, $contains)) {
                $message = '请求参数错误';
            } else {
                $data = Arr::only($data, $contains);
                $user = new User([
                    'username' => $data['username'],
                    'nickname' => $data['nickname'],
                    'mobile'   => $data['mobile'],
                    'gender'   => $data['gender'],
                    'email'    => $data['email'],
                    'password' => bcrypt($data['password']),
                ]);

                if ($user->save()) {
                    // 依赖 mobile 或者 email 唯一 和观察者 模式是否返回false
                    $code    = 200;
                    $message = '注册成功!';
                }
                DB::commit();
            }
        } catch (Exception $err) {
            DB::rollBack();
            if ($err->getCode() == 23000) {
                $code    = 200;
                $message = '该账号已经注册过了!';
            } else {
                $code    = 500;
                $message = '出错啦，请稍后再试!';
            }
        }
        //TODO 记录用户来源问题

        return compact('code', 'message');
    }

    /**
     * 第三方平台快速登录
     *
     * @param string $loginSource 登录的第三方来源,例如 qq,sina
     * @param array  $userInfo
     *
     * @return array
     * @throws Exception
     */
    public function fastLogin(string $loginSource, array $userInfo): array
    {
        DB::beginTransaction();
        try {
            $openId = $userInfo['openid'] ?? ($userInfo['open_id'] ?? $userInfo['uid']);
            if (empty($openId)) {
                throw new Exception('登录失败！');
            }
            //昵称 QQ:nickname  Sina:name|screen_name
            $nickname = $userInfo['nickname'] ?? ($userInfo['name'] ?? '');
            // 本平台性别：0未设置，1男，2女
            // 性别：QQ-gender_type：1女2男  Sina-gender:m男w:女
            $gender = $loginSource == 'qq' ? ($userInfo['gender_type'] == 2 ? 1 : 2) : ($loginSource == 'sina' ? ($userInfo['gender'] == 'm' ? 1 : 2) : 0);
            //头像 QQ:figureurl_qq Sina:avatar_large
            $cover = $userInfo['figureurl_qq'] ?? ($userInfo['avatar_large'] ?? '');

            // 省市
            $province = $city = '';
            if ($loginSource == 'sina') {
                list($province, $city) = explode(' ', $userInfo['location']);
            }
            if ($loginSource == 'qq') {
                $province = $userInfo['province'];
                $city     = $userInfo['city'];
            }

            $UserOrigin = UserOrigin::where([
                'type'    => $loginSource,
                'open_id' => $openId,
            ])->first();
            if (!$UserOrigin) {
                // 看着这里使用 cover 查询，你会感觉很诧异，使用cover 是为了解决
                // 同一平台申请了多个应用（例如：web端app应用和移动端app应用）
                //无法使用openid来区分，使用头像进行一波比较
                $UserOrigin = UserOrigin::where([
                    'type'  => $loginSource,
                    'cover' => $cover,
                ])->first();

                $user = $UserOrigin ? $UserOrigin->user : '';
                $data = [
                    'nickname' => $nickname,
                    'gender'   => $gender,//'性别：0未设置，1男，2女');
                    'cover'    => $cover,
                    'type'     => $loginSource,
                    'open_id'  => $openId,
                    'province' => $province,
                    'city'     => $city,
                    'all'      => json_encode($userInfo),
                    'status'   => 1,
                ];
                if ($user) {
                    $data['user_id'] = $user->id;
                }
                $UserOrigin = UserOrigin::create($data);
                if ($user) {
                    $UserOrigin->refresh();
                }
            }

            if (isset($UserOrigin->user_id) && $UserOrigin->user()->exists()) {
                $user = $UserOrigin->user;
            } else {
                $user = new User([
                    'username' => $UserOrigin['username'] ?? '',
                    'nickname' => $UserOrigin['nickname'] ?? '',
                    'gender'   => $UserOrigin['gender'],
                    'cover'    => $UserOrigin['cover'],
                    'province' => $UserOrigin['province'] ?? '',
                    'city'     => $UserOrigin['city'] ?? '',
                    'status'   => 1,
                    //'mobile'=>'',
                    //'email',
                    //'password',
                    //'identity_card'=>'',
                    //'email_verified_at',
                    //'county',
                    //'town',
                    //'village',
                    //'enterprise_id'
                ]);
                $user->save();
                $UserOrigin->user_id = $user->id;
                $UserOrigin->save();
            }
            // 转数组
            $user                = collect($user)->toArray();
            $user['loginSource'] = collect($UserOrigin)->except(['all', 'user'])->toArray();

            DB::commit();
            return $user;
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }

    // 发送短信验证码
    public function sendSms(): bool
    {
        return false;
        $accessKeyId     = "阿里云或者腾讯云 appid";
        $accessKeySecret = "阿里云或者腾讯云 secret";

        // 可发送多个手机号，变量为数组即可，如：[11111111111, 22222222222]
        $mobile   = '18***888';
        $template = '您申请的短信模板';
        $sign     = '您申请的短信签名';

        // 短信模板中用到的 参数 模板变量为键值对数组
        $params = [
            "code"    => rand(1000, 9999),
            "title"   => '您的标题',
            "content" => '您的内容',
        ];

        // 初始化 短信服务（阿里云短信或者腾讯云短信）
        $smsObj = \zxf\sms\Sms::instance($accessKeyId, $accessKeySecret, 'ali或者tencent');

        // 若使用的是 腾讯云短信 需要 设置 appid 参数; 阿里云则不用
        // $smsObj = $smsObj->setAppid($appid);

        // 发起请求
        // 需要注意，设置配置不分先后顺序，send后也不会清空配置
        $result = $smsObj->setMobile($mobile)->setParams($params)->setTemplate($template)->setSign($sign)->send();
        /**
         * 返回值为bool，你可获得阿里云响应做出你业务内的处理
         *
         * status bool 此变量是此包用来判断是否发送成功
         * code string 阿里云短信响应代码
         * message string 阿里云短信响应信息
         */
        if (!$result) {
            $response = $smsObj->getResponse();
            // 做出处理
        }
        return false;
    }
}
