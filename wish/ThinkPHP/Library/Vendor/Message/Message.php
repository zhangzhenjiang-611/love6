<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/22
 * Time: 11:26
 */


class Message {
     protected $apiKey = '';            //apikey
     protected $apiSecret = '';         // api秘钥
     protected $templateId = '';        //模板id


    // 推送模版消息
    function getAuthParam() {
        $apiKey = $this->apiKey;
        $apiSecret = $this->apiSecret;
        $timestamp = time();
        return array(
            'apiKey' => $apiKey,
            'timestamp' => $timestamp,
            'sig' => md5($apiKey . $apiSecret . $timestamp),
        );
    }
    function sendTempleMessage($data) {
        $param['type'] = 'template';
        $param['templateId'] = $this->templateId;
        $param['topcolor'] = '#04a7f9';
        //模版消息跳转地址
        $param['url'] = $data['url'];
        $param['toUsers'] = $data['openid'];
        $param['data'] = $this->getCardTemplateData($data['title'],$data['points'],$data['info'],$data['remark']);
        $send = $this->sendTempleMsg($param);
    }

    //模版消息
    function getCardTemplateData($title,$points,$info,$remark) {
        $templateData = array(
            'first' => array(
                'value' => $title,
                'color' => '#0e356e'
            ),
            'orderMoneySum' => array(
                'value' => $points,
                'color' => '#2832e1'
            ),
            'orderProductName' => array(
                'value' => $info,
                'color' => '#0e356e'
            ),
            'Remark' => array(
                'value' => $remark,
                'color' => '#0e356e'
            )
        );
        return json_encode($templateData);
    }

    function sendDeliverTempleMessage($data) {
        $param['type'] = 'template';
        $param['templateId'] = 'N-SQ82B-F19ABF-hH84nt-2hacfucnKe7iynnQLyalY';
        $param['topcolor'] = '#04a7f9';
        //模版消息跳转地址
        $param['url'] = $data['url'];
        $param['toUsers'] = $data['openid'];
        $param['data'] = $this->getDeliverCardTemplateData($data['title'],$data['order_id'], $data['remark']);
        $send = $this->sendTempleMsg($param);
    }

    //模版消息
    function getDeliverCardTemplateData($title,$order_id,$remark) {
        $templateData = array(
            'first' => array(
                'value' => $title,
                'color' => '#0e356e'
            ),
            'OrderSn' => array(
                'value' => $order_id,
                'color' => '#2832e1'
            ),
            'OrderStatus' => array(
                'value' => '已发货',
                'color' => '#0e356e'
            ),
            'remark' => array(
                'value' => $remark,
                'color' => '#0e356e'
            )
        );
        return json_encode($templateData);
    }

    //set temple by one
    function sendTempleMsg($param) {
        $sendParam = array(
            'a' => 'Send',
            'm' => 'sendTemplate'
        );
        $sendParam = array_merge($this->getAuthParam(), $sendParam);
        $sendParam = array_merge($param, $sendParam);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'api.socialjia.com/index.php');
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, 1);
        $body = http_build_query($sendParam);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $httpInfo = curl_getinfo($curl);
        curl_close($curl);
        return $response;
    }

}
