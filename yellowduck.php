<?php
/**
  * ΢��������Խű�
  */
//����tokenֵ������΢�Ź���ƽ̨�����������õ�tokenֵ��Ҫ����һ�£�����͵��û�Ļ��õ�Ĭ�ϵ�weixin
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}
class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

          //extract post data
        if (!empty($postStr)){
               
                 $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";             
                if(!empty( $keyword ))
                {
                      $msgType = "text";
                      //�����û�������Ϣͨ��С�Ƽ������캯������������
                    $contentStr = $this->chat($keyword);
                    //��߹ٷ������õ�sprintf���������˳�������Ӧ��ֵ�������$textTpl��[%s],����ر�������ߵ�$fromUsername�������<ToUserName>�У���˼�ǰѻ�ȡ���û���������Ϊ������������������Ӣ�ﻹ�еĻ�from��toӦ�ú�������⣬�ٺ�
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }else{
                    echo "���ǣ������������ѽ���Ҳ�������ۣ�";
                }

        }else {
            echo "";
            exit;
        }
    }
    
    /**
     * ����һ��ǩ����֤����
     * @return [boolen] [��֤���]
     */
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];    
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * ��װ��һ��С�Ƽ�����
     * @param  [string] $keyword [�û���������]
     * @return [string]          [curl������С�Ƽ��ظ�����]
     */
     private function chat($keyword){
        $url = "http://www.simsimi.com/talk.htm?lc=ch";
      
        //���curl����Ϊ�ٷ�ÿ��������Ψһ��COOKIE�����Ǳ����Ȱ�COOKIE�ó�������Ȼ��һֱ���ء�HI��
      
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        list($header, $body) = explode("/r/n/r/n", $content);
        preg_match("/set/-cookie:([^/r/n]*)/i", $header, $matches);
        //curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        $cookie = $matches[1];
      
      
        $urll = 'http://www.simsimi.com/func/req?msg=' .$keyword. '&lc=ch';
           
        // ���CURL����ģ�ⷢ�����󿩣�ֱ�ӷ��صľ���JSON
     
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urll);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_REFERER, "http://www.simsimi.com/talk.htm?lc=ch");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        $content = curl_exec($ch);
        curl_close($ch);
           
        //���json
        //print_r($content);
        $json = json_decode($content,true);
        if (!empty($json) && $json['result']==100){
         return $json['response'];
        }
    }   
}
