<?php
/**
  * 微信聊天测试脚本
  */
//定义token值和你在微信公众平台开发者里设置的token值需要保持一致，我这偷懒没改还用的默认的weixin
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
                      //根据用户输入信息通过小黄鸡的聊天函数返回输出结果
                    $contentStr = $this->chat($keyword);
                    //这边官方代码用的sprintf的用意就是顺序输出对应的值到上面的$textTpl里[%s],大家特别留意这边的$fromUsername是输出到<ToUserName>中，意思是把获取的用户名现在作为被发送人来输出，如果英语还行的话from，to应该很容易理解，嘿嘿
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }else{
                    echo "哥们，别输个空内容呀！我不会读心哇！";
                }

        }else {
            echo "";
            exit;
        }
    }
    
    /**
     * 这是一个签名验证函数
     * @return [boolen] [验证结果]
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
     * 封装的一个小黄鸡函数
     * @param  [string] $keyword [用户输入数据]
     * @return [string]          [curl处理后的小黄鸡回复数据]
     */
     private function chat($keyword){
        $url = "http://www.simsimi.com/talk.htm?lc=ch";
      
        //这个curl是因为官方每次请求都有唯一的COOKIE，我们必须先把COOKIE拿出来，不然会一直返回“HI”
      
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
           
        // 这个CURL就是模拟发起请求咯，直接返回的就是JSON
     
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urll);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_REFERER, "http://www.simsimi.com/talk.htm?lc=ch");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        $content = curl_exec($ch);
        curl_close($ch);
           
        //输出json
        //print_r($content);
        $json = json_decode($content,true);
        if (!empty($json) && $json['result']==100){
         return $json['response'];
        }
    }   
}
