<?php
/**
 * app 推送消息
 * User: Administrator
 * Date: 2021/6/23
 * Time: 14:34
 */

namespace Notify;

class AppPush extends Base
{
    /**
     * 发送消息 （群发+单发+批量）
     * @param $data_arr
     * @return mixed
     */
    public function send($data_arr)
    {
        $hearder_arr = $this->getHeader();

        return $this->postUrl($this->getApiUrl(), $data_arr, $hearder_arr);
    }

}