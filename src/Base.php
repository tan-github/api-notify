<?php

namespace Notify;

/**
 * 消息通知类
 * User: Administrator
 * Date: 2021/6/17
 * Time: 16:37
 */

class Base
{
    //app ID
    private $app_id = '';
    //app 密钥
    private $app_secret = '';
    //接口地址
    private $api_url = '';
    //模板ID
    private $template_id = '';
    //信息
    private $message_arr = array();
    //接口认证用户名
    private $auth_user = '';
    //接口认证密码
    private $auth_password = '';
    //url query参数
    private $url_query_arr = array();


    /**
     * @return string
     */
    public function getTemplateId()
    {
        return $this->template_id;
    }

    /**
     * @param string $template_id
     */
    public function setTemplateId($template_id)
    {
        $this->template_id = $template_id;
    }

    /**
     * @return string
     */
    public function getAuthUser()
    {
        return $this->auth_user;
    }

    /**
     * @param string $auth_user
     */
    public function setAuthUser($auth_user)
    {
        $this->auth_user = $auth_user;
    }

    /**
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->auth_password;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->api_url;
    }

    /**
     * @param string $api_url
     */
    public function setApiUrl($api_url)
    {
        $this->api_url = $api_url;
    }

    /**
     * @return array
     */
    public function getMessageArr()
    {
        return $this->message_arr;
    }

    /**
     * @param array $message_arr
     */
    public function setMessageArr($message_arr)
    {
        $this->message_arr = $message_arr;
    }

    /**
     * @param string $auth_password
     */
    public function setAuthPassword($auth_password)
    {
        $this->auth_password = $auth_password;
    }

    /**
     * @return array
     */
    public function getUrlQueryArr()
    {
        return $this->url_query_arr;
    }

    /**
     * @param array $url_query_arr
     */
    public function setUrlQueryArr($url_query_arr)
    {
        $this->url_query_arr = $url_query_arr;
    }


    public function __construct($app_id, $app_secret, $api_url = '', $template_id = '')
    {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
        $this->api_url = $api_url;
        $this->template_id = $template_id;
    }


    /**
     * 请求头
     * @return array
     */
    protected function getHeader()
    {
        //随机字符串
        $nonce = uniqid();
        //时间戳
        $timestamp = time() * 1000;

        $signature = $this->getSignature($timestamp, $nonce);
        $header = array();
        $header[] = 'X-App-Id:' . $this->app_id;
        $header[] = 'X-Timestamp:' . $timestamp;
        $header[] = 'X-Nonce:' . $nonce;
        $header[] = 'X-Sign:' . $signature;
        $header[] = 'X-Basic-V:2';

        return $header;
    }

    /**
     * 签名
     * @param $timestamp
     * @param $nonce
     * @return string
     */
    private function getSignature($timestamp, $nonce)
    {
        $subfix = '';//发送验证码需要，其它不需要
        if ($this->url_query_arr) {
            ksort($this->url_query_arr, SORT_STRING);
            $subfix = http_build_query($this->url_query_arr);
        }

        $signature = base64_encode(hash_hmac("sha1", $this->app_id . $timestamp . $nonce . $subfix, $this->app_secret, true));

        return strtoupper(md5($signature));
    }

    /**
     * 调接口
     * @param $url
     * @param $data
     * @param array $header_arr
     * @return mixed
     */
    protected function postUrl($url, $data, $header_arr = array())
    {
        $data = json_encode($data);
        $init_header_arr = array("Content-type:application/json;charset='utf-8'", "Accept:application/json");
        if ($header_arr) {
            foreach ($header_arr as $header) {
                $init_header_arr[] = $header;
            }
        }

        $timeout = 5;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $init_header_arr);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);//5秒
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);//5秒
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $this->auth_user . ':' . $this->auth_password);
        $output = curl_exec($curl);
        curl_close($curl);

        return json_decode($output, true);
    }

}
