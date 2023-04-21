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
 * 用户登录、退出、个人信息等
 */
class UserServices extends BaseService
{
    // auth 模块名称 web|admin|docs|api|...
    private $authName = 'web';

    // 用户授权方式 支持session或token(jwt|passport|...)两种方式
    private $authType = 'session';

    // 用户登录方式 参见 $loginTypeMap
    private $loginType = 'password';

    // 登录方式
    const LOGIN_TYPE_PASSWORD = 'password';
    const LOGIN_TYPE_EMAIL    = 'email';
    const LOGIN_TYPE_SMS      = 'sms';
    const LOGIN_TYPE_USER_ID  = 'id';

    public static $loginTypeMap = [
        self::LOGIN_TYPE_PASSWORD => '手机号+密码',
        self::LOGIN_TYPE_EMAIL    => '邮箱号+密码',
        self::LOGIN_TYPE_SMS      => '短信验证码',
        self::LOGIN_TYPE_USER_ID  => '用户id',
    ];

    // 设置授权名称
    public function setAuthName($name = 'web')
    {
        $this->authName = $name;
        return $this;
    }

    // 设置授权方式 支持session或token两种方式
    public function setAuthType($type = 'session')
    {
        $this->authType = $type;
        return $this;
    }

    // 设置登录方式
    public function setLoginType($type = self::LOGIN_TYPE_PASSWORD)
    {
        if (empty(self::$loginTypeMap[$type])) {
            throw new \Exception('不支持的登录方式');
        }
        $this->loginType = $type;
        return $this;
    }

    // 获取用户信息
    public function info()
    {
        return [
            'code'    => 200,
            'message' => '获取成功',
            'data'    => [
                auth($this->authName)->user(),
            ],
        ];
    }

    /**
     * 使用账号 和密码进行登录
     *
     * @param string      $account  账号(手机号、邮箱号、用户id)
     * @param string|null $password 密码（账号为用户id时候为空）
     *
     * @return array
     */
    public function login(string $account, string|null $password = '')
    {
        $remember = false; // 是否记住密码

        $code    = 412;
        $message = '账号或者密码错误';
        $data    = [];

        if ($this->authType == 'session') {
            $isLogin = false;
            if ($this->loginType == self::LOGIN_TYPE_USER_ID) {
                $isLogin = auth($this->authName)->loginUsingId($account, $remember);
            }
            if ($this->loginType == self::LOGIN_TYPE_PASSWORD) {
                $isLogin = auth($this->authName)->attempt(['mobile' => $account, 'password' => $password], $remember);
            }
            if ($this->loginType == self::LOGIN_TYPE_EMAIL) {
                $isLogin = auth($this->authName)->attempt(['email' => $account, 'password' => $password], $remember);
            }
            if ($this->loginType == self::LOGIN_TYPE_SMS) {
                // TODO 短信登录
                // $isLogin = auth($this->authName)->login($user, $remember);
            }
            if ($isLogin) {
                $user = auth($this->authName)->user();
                if ($user->status !== User::STATUS_NORMAL) {
                    $code    = 412;
                    $message = '账号未激活或已冻结';
                } else {
                    $code    = 200;
                    $message = '登录成功';
                }
            }
        } else {
            // 使用 api token 登录
            $user = '';
            switch ($this->loginType) {
                case self::LOGIN_TYPE_USER_ID:
                    $user = User::where('id', $account)->first();
                    break;
                case self::LOGIN_TYPE_PASSWORD:
                case self::LOGIN_TYPE_SMS:
                    $user = User::where('mobile', $account)->first();
                    break;
                case self::LOGIN_TYPE_EMAIL:
                    $user = User::where('email', $account)->first();
                    break;
                default:
            }

            if ($user && ($this->loginType == self::LOGIN_TYPE_USER_ID || Hash::check($password, $user->password))) {
                if ($user->status !== User::STATUS_NORMAL) {
                    $code    = 412;
                    $message = '账号未激活或已冻结';
                } else {
                    $authInfo = $user->createToken($this->authName);
                    $token    = $authInfo->accessToken;
                    if ($token) {
                        $code    = 200;
                        $message = '登录成功';
                        $data    = [
                            'access_token' => $token,
                            'token_type'   => 'Bearer',
                            // 'client_id'  => $authInfo->client_id
                        ];
                    }
                }
            }
        }

        // 检查关联企业
        //    if ($this->useApiToken) {
        //        // 如果是 api token 方式，需要手动修改 header 头 让auth() 可以获取当前用户信息
        //        request()->headers->set('Authorization', "Bearer " . $token);
        //    }

        // 记录用户来源问题

        return compact('code', 'message', 'data');
    }

    // 用户退出
    public function logout()
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
    public function register($data)
    {
        $code    = 412;
        $message = '注册失败!';
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
            }
        } catch (Exception $err) {
            if ($err->getCode() == 23000) {
                $code    = 200;
                $message = '该账号已经注册过了!';
            } else {
                $code    = 500;
                $message = '出错啦，请稍后再试!';
            }
        }
        // 记录用户来源问题

        return compact('code', 'message');
    }

    /**
     * 第三方平台快速登录
     *
     * @param string $loginSource 登录的第三方来源,例如 qq,sina
     * @param        $userInfo
     *
     * @return array
     * @throws Exception
     */
    public function fastLogin(string $loginSource, $userInfo)
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
    public function sendSms()
    {

    }
}
