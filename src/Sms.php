<?php

namespace Notify;

/**
 * 短信类
 * User: Administrator
 * Date: 2021/6/17
 * Time: 16:37
 */

class Sms extends Base
{
    /**
     * @return array
     */
    public function getSmsMessage()
    {
        $this->getMessageArr();
    }

    /**
     * @param array $sms_message
     */
    public function setSmsMessage($sms_message)
    {
        $this->setMessageArr($sms_message);
    }

    /**
     * @return string
     */
    public function getSmsApiUrl()
    {
        return $this->getApiUrl();
    }

    /**
     * @param string $sms_api_url
     */
    public function setSmsApiUrl($sms_api_url)
    {
        $this->setApiUrl($sms_api_url);
    }

    /**
     * 发送短信（批量）
     * @param $params
     * @return mixed
     */
    public function sendBatchSms($params)
    {
        $hearder_arr = $this->getHeader();

        $data_arr = array(
            'receivers' => $params,
            'templateId' => $this->getTemplateId(),
        );

        return $this->postUrl($this->getApiUrl(), $data_arr, $hearder_arr);
    }

    /**
     * 发送短信（群发+单发）
     * @param $phone_number
     */
    public function sendSms($phone_number)
    {
        $hearder_arr = $this->getHeader();

        $data_arr = array(
            'mobiles' => array($phone_number),
            'templateId' => $this->getTemplateId(),
            'templateParams' => $this->getSmsMessage()
        );

        $url = $this->getApiUrl();
        //发送验证码用
        if ($this->getUrlQueryArr()) {
            ksort($this->getUrlQueryArr(), SORT_STRING);
            $url .= '?' . http_build_query($this->getUrlQueryArr());
        }

        return $this->postUrl($url, $data_arr, $hearder_arr);
    }

}
