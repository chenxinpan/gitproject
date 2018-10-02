<?php
/**
 * 封装接口统一返回数据格式(json数据格式)
 **/
namespace app\admins\controller;
use think\Controller;
use app\admins\model\Basemodel;

class Basecontroller extends Controller
{   
      
	function __construct()
	{		
		    parent::__construct();
        $this->_db = new Basemodel(); 
    	  session_start();
        if(empty($_SESSION['adminuser'])){
            unset($_SESSION['adminuser']);
            //session_destroy();
        }
		    header('Access-Control-Allow-Origin:*');
		    header("Access-Control-Allow-Methods:POST,GET");
		    header("Access-Control-Allow-Headers:x-requested-with,content-type");
        // 定义PHP 常用变量  
        define('CODE_SUCCESS',  200);
        define('CODE_ERROR',    300);
        define('CODE_EXIST',    400);
        define('NOT_ACCESS',    319);  
        //定义图片入口路径,根据情况可修改,属于配置路径
        define('URL_IMG','http://192.168.1.86/PermissionM/public');
	}  


	/**
	 * api统一返回数据格式
	 * @apiParam  code 返回代码默认200
	 * @apiParam  msg 返回提示语
	 * @apiParam  data 返回数据详情
	 */
	function apiReturn($code =200, $msg, $data)
	{
		$result  = array(
			'code' => $code, 
			"msg"  => $msg,
			"data" => $data,
		);
		return json_encode($result,JSON_UNESCAPED_SLASHES);
	}	
  
   //权限栏导航控制
   function navgition()
   {    
        //所有的权限名称
   	    $menu = [
                array('funcCodeName' =>"DT_LOG_GM","funcName"=>"操作日志","flag"=>"false"),
                array('funcCodeName' =>"DT_INDEX_GM","funcName"=>"系统首页","flag"=>"false"),
                array('funcCodeName' =>"DT_TRANS_GM","funcName"=>"交易统计","flag"=>"false"),
                array('funcCodeName' =>"DT_USER_GM","funcName"=>"用户中心","flag"=>"false")
            ];
        $username = $_SESSION['indexuser'];           
        //$username = 'admin';
        //得到该用户的权限名称
        $res = $this->_db->navgition($username);
        //如果存在权限
        if($res){
            $data = [];
            foreach ($res as $key => $value) 
            {
                array_push($data, $value['mname']);
            }
            $finalmenu = $menu;
            //将角色赋予的权限变成true
            for ($i=0;$i<count($data);$i++)
             {        
               for($j=0;$j<count($finalmenu);$j++) 
               {
                 if($finalmenu[$j]['funcName']==$data[$i])
                 {
                   //将flag变为true
                   $finalmenu[$j]['flag'] = "true";
                 }
                }  
            }
           echo $this->apiReturn(CODE_SUCCESS,"",$finalmenu);             
        }else{
           echo $this->apiReturn(CODE_ERROR,"该角色没有任何权限",[]); 
        }       
   }	 

   //公用方法--登录退出日志表信息插入  
   public function Inout_log($uname,$ip,$status,$status_descrption,$log_time,$flag)
   {
        $res = $this->_db->_Inout_log($uname,$ip,$status,$status_descrption,$log_time,$flag);
        return $res;
   }

}