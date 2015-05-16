<?php
require_once 'login.php';
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
$wechatObj->responseMsg();
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
		
		$type = $postObj->MsgType;
                //echo $type;
		$customevent = $postObj->Event;
		//echo $customevent;	                

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
/***/			
		if($type=="event" and $customevent=="subscribe"){
		  $contentStr = "你发送“变”，我就变哟！！";
                  //echo $contentStr;
		  $msgType = "text";
                  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                  echo $resultStr;
		}

/**/            
		else if( !empty($keyword))
                {
              		$msgType = "text";
                        $contentStr = "你说“变”，我才变！！";
                      if($keyword == "变") { 
                	$contentStr = "看我变！";
                	
/**/                   
			$conn = mysql_connect("localhost".":"."3306","root",$db_password);
			mysql_select_db("smart_home",$conn);
			$result = mysql_query("select * from comd");
			$row = mysql_fetch_array($result);
                        $next_cmd = 1-(int)$row["cmd"];
                        $query =sprintf("update comd set cmd ='%s' where id=1",(string)$next_cmd);
                        mysql_query($query);
/**/
			}
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
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
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>
