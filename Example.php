<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/6/17
 * Time: 18:26
 */
require_once __DIR__.'/vendor/autoload.php';
date_default_timezone_set('PRC');

class Example
{
    //测试短信
    public function testSms()
    {
        /** 测试 */
        $app_id = 'xxxxxxxxxxxxxxxxxx';
        $app_secret = 'xxxxxxxxxxxxxxxxxx';
        $tpl_id = 'xxxxxxxxxxxxxxxxxx';

        $notify_obj = new Notify\Sms($app_id, $app_secret);
        $notify_obj->setTemplateId($tpl_id);
        $notify_obj->setAuthUser('xxxxxxxxxxxxxxxxxx');
        $notify_obj->setAuthPassword('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

        //群发+单发
        $url = 'http://xxxxxxxxxxxxxxxxxx/send-mass-msg';
        $sms_message = array(
            'aaaa', 'cccc', 'xxxxx', date('Y-m-d')
        );
        $notify_obj->setSmsApiUrl($url);
        $notify_obj->setSmsMessage($sms_message);
        $rs = $notify_obj->sendSms('xxxxxxxxxxxxxxxxxx');
        var_dump($rs);

        //批量
        $url = 'http://xxxxxxxxxxxxxxxxxx/send-batch-msg';
        $params = array(
            array(
                'mobiles' => 'xxxxxxxxxxxxxxxxxx',
                'templateParams' => array('aaaa', 'cccc', 'xxxx', date('Y-m-d'))
            )
        );
        $notify_obj->setSmsApiUrl($url);
        $rs = $notify_obj->sendBatchSms($params);
        var_dump($rs);

        //发送验证码
        $url = 'http://xxxxxxxxxxxxxxxxxx/send-code';
        $sms_message = array(
            'code' =>rand(1000,9999),
            'mobile' =>'xxxxxxxxxxxxxxxxxx',
        );
        $notify_obj->setUrlQueryArr($sms_message);
        $notify_obj->setSmsApiUrl($url);
        $rs = $notify_obj->sendSms($sms_message['mobile']);
        var_dump($rs);
    }

    //测试APP推送消息
    public function testAppPush()
    {

        /** 测试 */
        $app_id = 'xxxxxxxxxxxxxx';
        $app_secret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        $tpl_id = 7;

        $notify_obj = new Notify\AppPush($app_id, $app_secret);
        $notify_obj->setTemplateId($tpl_id);
        $notify_obj->setAuthUser('xxxxxxxxxxxxxx');
        $notify_obj->setAuthPassword('xxxxxxxxxxxxxx');

        //群发+单发
        $url = 'http://xxxxxxxxxxxxxx/cgi/notify/app/send-mass-msg';
        $message_arr = array(
            'app'=>0,
            'extraData'=>null,
            'startTime'=>null,
            'templateId'=>7,
            'templateParams'=>null,
            'uniqueKey'=>null,
            'userIds'=>array('5645664'),
        );

        $notify_obj->setApiUrl($url);
        $rs = $notify_obj->send($message_arr);
        var_dump($rs);

        //批量
        $url = 'http://xxxxxxxxxxxxxx/cgi/notify/app/send-batch-msg';

        $message_arr = array(
            'app'=>0,
            'extraData'=>null,
            'startTime'=>null,
            'templateId'=>7,
            'receivers'=>array(
                array(
                    'templateParams' => null,
                    'uniqueKey'=>null,
                    'userId'=>'5645664',
                )
            ),
        );

        $notify_obj->setApiUrl($url);
        $rs = $notify_obj->send($message_arr);
        var_dump($rs);

    }
}

$test = new Example();
//$test->testSms();
$test->testAppPush();