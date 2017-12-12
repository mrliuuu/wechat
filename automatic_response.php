<?php
/**
 * Created by PhpStorm.
 * User: 19205
 * Date: 2017/12/12
 * Time: 11:12
 */
// //最简单的验证方式
// echo $_GET["echostr"];

//验证是否来自于微信
class wechat_php
{
    function checkWeixin()
    {
        //微信会发送4个参数到我们的服务器后台 签名 时间戳 随机字符串 随机数

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET["echostr"];
        $token = "hahaha";

        // 1）将token、timestamp、nonce三个参数进行字典序排序
        $tmpArr = array($nonce, $token, $timestamp);
        sort($tmpArr, SORT_STRING);

        // 2）将三个参数字符串拼接成一个字符串进行sha1加密
        $str = implode($tmpArr);
        $sign = sha1($str);

        // 3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
        if ($sign == $signature) {
            echo $echostr;
        }
    }
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if(!empty($postStr))
        {
            $postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
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
            if(!empty($keyword))
            {
                $msgType = "text";
                $contentStr = "谢谢你关注我！";
                $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                echo $resultStr;
            }
            else
            {
                echo "请输入关键字";
            }
        }
        else{
            echo "";
            exit;
        }
    }
}
$wechatObj = new wechat_php();
//$wechatphp->checkWeixin();
$wechatObj->responseMsg();
?>