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

    // 用户授权方式 session|jwt|passport|...
    private $authType = 'session';

    // 用户登录方式 参见 $loginTypeMap
    private $loginType = 'session';

    // 登录方式
    const LOGIN_TYPE_PASSWORD = 'password';
    const LOGIN_TYPE_EMAIL    = 'email';
    const LOGIN_TYPE_SMS      = 'sms';
    const LOGIN_TYPE_USER_ID  = 'user_id';

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

    // 设置授权方式
    public function setAuthType($type = 'session')
    {
        $this->authType = $type;
        return $this;
    }

    // 设置登录方式
    public function setLoginType($type = 'session')
    {
        $this->authType = $type;
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

    // 用户进行手机号和密码登录
    public function login($mobile, $password = '')
    {
        $code    = 412;
        $message = '账号或者密码错误';
        $data    = [];
        if ($this->loginType == 'id') {
            $user = User::find($mobile); // 此时 $mobile 表示用户id
        } else {
            $user = User::where('mobile', $mobile)->first();
        }

        if (!$user) {
            return [
                'code'    => 404,
                'message' => '该手机号尚未注册',
            ];
        }
        if ($this->useApiToken && $this->loginType != 'id') {
            if ($user && Hash::check($password, $user->password)) {
                // 创建api token
                $authInfo = $user->createToken($this->authName);
                $token    = $authInfo->accessToken;
//                $clientId = $authInfo->client_id;
                if ($token) {
                    $code    = 200;
                    $message = '登录成功';
                    $data    = [
                        'access_token' => $token,
                        'token_type'   => 'Bearer',
                        //                        'client_id'            => $clientId
                    ];
                } else {
                    return [
                        'code'    => 404,
                        'message' => '登录失败',
                        'data'    => $data,
                    ];
                }
            }
        } else {
            if ($this->loginType == 'id') {
                // 创建api token
                $token = $user->createToken($this->authName)->accessToken;
                if ($token) {
                    $code    = 200;
                    $message = '登录成功';
                    $data    = [
                        'access_token' => $token,
                        'token_type'   => 'Bearer',
                    ];
                } else {
                    return [
                        'code'    => 404,
                        'message' => '登录失败',
                        'data'    => $data,
                    ];
                }
            } else {
                $remember = false; // 是否记住密码
                if (auth($this->authName)->attempt(['mobile' => $mobile, 'password' => $password], $remember)) {
                    $code    = 200;
                    $message = '登录成功';
                }
            }
        }
        // 检查关联企业
        if ($code == 200) {
            if ($this->useApiToken) {
                // 如果是 api token 方式，需要手动修改 header 头 让auth() 可以获取当前用户信息
                request()->headers->set('Authorization', "Bearer " . $token);
            }

            $user         = auth($this->authName)->user();
            $data['user'] = $user;
            // 判断 用户可以关联的企业
            $enterpriseList = collect($user->enterprises);
            if ($enterpriseList->count() == 1) {
                $user->enterprise_id = $enterpriseList->first()->id;
                $user->save();
            }
            $data['enterprises'] = $enterpriseList;
        }
        return [
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ];

    }

    // 用户退出
    public function logout()
    {
        auth($this->authName)->logout();
        return [
            'code'    => 200,
            'message' => '退出成功',
        ];
    }

    // 注册
    public function register($data)
    {
        try {
            // 注销包含字段
            $contains = ['username', 'nickname', 'mobile', 'gender', 'email', 'password'];
            if (!Arr::has($data, $contains)) {
                return [
                    'code'    => 412,
                    'message' => '请求参数错误',
                ];
            }

            $data = Arr::only($data, $contains);
            $user = new User([
                'username' => $data['username'],
                'nickname' => $data['nickname'],
                'mobile'   => $data['mobile'],
                'gender'   => $data['gender'],
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $user->save();
            return [
                'code'    => 200,
                'message' => '注册成功',
            ];
        } catch (Exception $err) {
            if ($err->getCode() == 2300) {
                return [
                    'code'    => 200,
                    'message' => '该账号已经注册过了',
                ];
            }
            return [
                'code'    => 500,
                'message' => '出错啦，请稍后再试！',
            ];
        }

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
