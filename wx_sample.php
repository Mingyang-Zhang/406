<?php

define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
//if (isset($_GET['echostr'])) {
//    $wechatObj->valid();
//}else{
    $wechatObj->responseMsg();
//}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

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

    public function responseMsg()
    {
        //$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //if (!empty($postStr)){
            //$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = "oSOIks7Vk48kGKcmR8dc7N8X9sQU";//$postObj->FromUserName;
            $toUsername = "gh_ac29a4b32890";//$postObj->ToUserName;
            $keyword = "hi"//trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            if($keyword == "hi"){
            	$conn = mysql_connect("localhost".":"."3306","root","thebestweare");
            	mysql_select_db("smart_home",$conn);
            	$res = mysql_query("select * from Press where pressure>10");
            	$contentStr="";
                while($rows = mysql_fetch_assoc($res)){
            		$contentStr=$contentStr." ".$rows[pressure];
            	}
            	$msgType = "text";
            	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            	echo $resultStr;
            }else if($keyword == "?" || $keyword == "？"){
                $msgType = "text";
                $contentStr = date("Y-m-d H:i:s",time());
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else if($keyword=="hihi"){
                $msgType = "text";
                $contentStr ="fromUsername:".$fromUsername."\ntoUsername:". $toUsername;
		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
		echo $resultStr;
	    }
        }else{
            echo "";
            exit;
        }
    }
}
?>
