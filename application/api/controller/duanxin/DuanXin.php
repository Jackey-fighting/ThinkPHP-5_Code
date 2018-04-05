<?php
namespace app\api\controller\duanxin;

use think\Controller;
use app\api\controller\duanxin\DuanXinCommon;
use think\Cache;
use think\Log;
use app\api\controller\Common;
/**
* 短信操作类
*/
class DuanXin extends Common
{
	  //发送短信
    public function SendDuanXin(){
    	//halt('222');
    	set_time_limit(0);
        header('Content-Type: text/plain; charset=utf-8');

        $phoneNum = '18825083581';
        $rand = ceil(mt_rand(1000, 99999));
        $response = DuanXinCommon::sendSms($rand, $phoneNum);
        echo "发送短信(sendSms)接口返回的结果:\n\r";
        print_r($response);
        if ($response->Code == 'OK') {
        	Cache::set($phoneNum, $rand, config('aliyun.identify_time'));
        	echo '已经发短信到 '.Cache::get($phoneNum).' 手机号。';
        	//写入日志
        	Log::info($this->logMessage($response->Message, $response->RequestId, $response->Code));
        }else{
        	echo '短信发送失败,请查看你的手机号是否正确。';
        	//写入日志
        	Log::error($this->logMessage($response->Message, $response->RequestId, $response->Code));
        }
      }   
        /**
        *阿里云的短信信息写入日志
		*@param string $message 阿里云返回的message
		*@param string $requestId 阿里云返回的requestId
		*@param string $code 阿里云返回的code
		*@return string 返回信息
        */
        public function logMessage($message, $requestId, $code){
        	return 'alidayu短信发送状态： -------------\n'
        	.'  Message: '.$message
        	.'  RequestId: '. $requestId
        	.'  Code: '.$code;
        }

        
        //并把短信以手机号为名存到缓存 or mysql 根据具体需求而定
   
}