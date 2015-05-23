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
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $conn = mysql_connect("localhost".":"."3306","root","thebestweare");
            mysql_select_db("smart_home",$conn);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $msgType = $postObj->MsgType;			//消息类型
            $event = $postObj->Event;			//事件类型
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            if($msgType=="event"){
				if(strtolower($event)=="location") {           	
            	$res = mysql_query("select * from Press");
            	$contentStr="";
                while($rows = mysql_fetch_assoc($res)){
            		if($rows[pressure]>400 && $rows[flag]==0){
            			mysql_query("update Press set flag=1 where room=406");
            			$msgType = "text";
            			$contentStr = "您已在".date("Y-m-d H:i:s",time())."重新添满。";
            			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            			echo $resultStr;
            		}
            		else if($rows[pressure]<100 && $rows[flag]==1){
            			$msgType = "text";
            			$contentStr = "物资不足，请尽快购置。。。";
            			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            			echo $resultStr;
            		}
            	}
            	$msgType = "text";
            	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            	echo $resultStr;
				}
            }
			else{
				$res = mysql_query("select * from Press");
				if($keyword == "ok" && $rows[flag]==1){
					mysql_query("update Press set flag=0 where room=406");
					$msgType = "text";
					$contentStr = "好的，停止提醒";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;					
				}
            	else if($keyword == "?" || $keyword == "？"){
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
			}
        }else{
            echo "";
            exit;
    	}
    }
}
?>
