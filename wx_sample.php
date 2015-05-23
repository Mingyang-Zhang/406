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
            $latitude = trim($postObj->Latitude);		//地理位置纬度
            $longitude = trim($postObj->Longitude);	//地理位置经度
            $location_X = trim($postObj->Location_X);	//地理位置纬度
            $location_Y = trim($postObj->Location_Y);	//地理位置经度
            $scale = trim($postObj->Scale);			//地图缩放大小
            $label = trim($postObj->Label);			//描述信息
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
					$ak='h7p0MWaFlrnxopif9xVAwiv3';
					$home='116.329941,40.004112';//目标位置
					$waypoints=$home.';'.$longitude.','.$latitude;
					$distance =$this->getDistance($waypoints,$ak);  //获取距离
					$sql="update location set latitude=$latitude where id=0"; 
					mysql_query($sql); 
					$sql="update location set longitude=$longitude where id=0";
					mysql_query($sql);
					$sql="update location set distance=$distance where id=0";
					mysql_query($sql);
            		$res = mysql_query("select * from Press");
            		$contentStr=$latitude."\n".$longitude;
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
			else if($msgType=="text"){
				$res = mysql_query("select * from Press");
				while($rows = mysql_fetch_assoc($res)){
					if($keyword == "ok" && $rows[flag]==1){
						mysql_query("update Press set flag=0 where room=406");
						$msgType = "text";
						$contentStr = "好的，停止提醒";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}					
				}
            	if($keyword == "?" || $keyword == "？"){
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
			else if ($msgType=="location") 
			{
                $msgType = "text";           //回复消息的类型
                $contentStr = "纬度：".$location_X."\n";
                $contentStr = $contentStr."经度：".$location_Y."\n";
                $contentStr = $contentStr."地图缩放大小：".$scale."\n";
                $contentStr = $contentStr."描述信息：".$label;
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
        }else{
            echo "";
            exit;
    	}
    }
    
    
    function getDistance($waypoints,$ak)//调用百度API
    {
    	$url="http://api.map.baidu.com/telematics/v3/distance?waypoints=$waypoints&ak=$ak";
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$result = curl_exec($ch);
    	curl_close($ch);
    
    	$getObj=simplexml_load_string($result);
    
    	if($getObj->status=='Success')
    	{
    		return $getObj->results->distance;
    	}else
    		return "";
    }
}
?>
