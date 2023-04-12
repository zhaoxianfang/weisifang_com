<?php

namespace Modules\Core\Services;

use zxf\sms\Sms;
use function dd;
use function env;

class SmsServices extends BaseService
{
    public function test()
    {
        die();
//        $serverType = 'ali';
//        $accessKeyId     = config('sms.' . $serverType . '.access_key_id');
//        $accessKeySecret = config('sms.' . $serverType . '.access_sey_secret');
//        $sign            = config('sms.' . $serverType . '.sign'); // 短信签名

        $accessKeyId     = env('SMS_ALI_KEY_ID');
        $accessKeySecret = env('SMS_ALI_KEY_SECRET');
        $sign            = env('SMS_ALI_SIGN'); // 短信签名

        // $template        = 'SMS_218278951'; // 验证码模板
        $template = 'SMS_218283798'; // 验证码模板

        // 多个手机号使用数组
        // $mobile = '18314440364';
        $mobile = [
            '18314440364',
            '18388050779'
        ];

        // 短信模板中用到的 参数 模板变量为键值对数组
        $params = [
            // "number" => rand(1000, 9999),
            "title"   => '我是标题',
            "content" => '我是内容'
        ];

        // 初始化 短信服务（阿里云短信或者腾讯云短信）
        $smsObj = Sms::instance($accessKeyId, $accessKeySecret, 'ali');

        // 发起请求
        // 需要注意，设置配置不分先后顺序，send后也不会清空配置
        $result = $smsObj->setMobile($mobile)->setParams($params)->setTemplate($template)->setSign($sign)->send();


        if ($result === true) {
            dd('短信发送成功，请注意查收');
            return [200, '短信发送成功，请注意查收', []];
        } else {

            // 做出处理
            $response = $smsObj->getResponse();
            dd($response);
            return [500, '短信发送失败：' . $response['message'], []];
        }

    }
}
