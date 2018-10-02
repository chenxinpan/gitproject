<?php
/**
 * 人员根据权限登录，
 * 登录接口
 * auth:  陈鑫
 * 日期： 2019-10-22
 */
namespace app\admins\controller;
use app\admins\controller\Basecontroller;
use app\admins\model\Accountmodel;

class Accountcontroller extends Basecontroller
{

	function __construct()
	{		
		parent::__construct();
    	$this->db = new Accountmodel(); 
	}

    //用户登录,账号和密码
	public function login()
	{   
		$username = trim(input('username'));
		$password = trim(input('password'));
		$uname = $username;
		$ip = $_SERVER['REMOTE_ADDR'];
		$status = '1'; //1成功，0失败
		$status_descrption = '3'; //（1用户名、密码错误 2账号被禁用,3正常）
		$log_time = date('Y-m-d H:i:s');
		$flag = '1'; //代表登录
		$res = $this->db->userlogin($username,$password);
		if($res=='passwordDone'){
			$status = '0';
			$status_descrption = '1';
			//写入日志表
			$this->Inout_log($uname,$ip,$status,$status_descrption,$log_time,$flag);
			//密码错误
			echo $this->apiReturn(CODE_ERROR,"密码错误",$res);
		}else if($res == 'admin'){
			//写入日志表
			$this->Inout_log($uname,$ip,$status,$status_descrption,$log_time,$flag);
			//后台管理员
			echo $this->apiReturn(CODE_SUCCESS,"该用户可以访问权限控制",$res);
		}else if($res == 'userindex'){
			//写入日志表
			$this->Inout_log($uname,$ip,$status,$status_descrption,$log_time,$flag);
			//其它角色，
            echo $this->apiReturn(CODE_SUCCESS,"该用户可以访问前台页面",$res);
		}else if($res == 'noaccess'){
			$status = '0';
			$status_descrption = '1';
			//写入日志表
			$this->Inout_log($uname,$ip,$status,$status_descrption,$log_time,$flag);
			//未分配角色的用户
            echo $this->apiReturn(CODE_SUCCESS,"该用户未分配角色无权访问任何页面",$res);
		}else if($res == 'nouser'){
			$status = '0';
			$status_descrption = '1';
			//写入日志表
			$this->Inout_log($uname,$ip,$status,$status_descrption,$log_time,$flag);
			//不存在的用户
			echo $this->apiReturn(CODE_ERROR,"该用户不存在",$res);
		}else if($res == 'jinyong'){
			$status = '0';
			$status_descrption = '2';
			//写入日志表
			$this->Inout_log($uname,$ip,$status,$status_descrption,$log_time,$flag);
			//状态禁用的用户
			echo $this->apiReturn(CODE_ERROR,"该用户已被禁用",$res);
		}				

	}

	//后台退出系统
	public function logout()
	{   
		$uname = $_SESSION["adminuser"];
		$ip = $_SERVER['REMOTE_ADDR'];
		$status = '1'; //1成功，0失败
		$status_descrption = '3'; //（1用户名、密码错误 2账号被禁用,3正常）
		$log_time = date('Y-m-d H:i:s');
		$flag = '2'; //代表退出
		//写入日志表
		$this->Inout_log($uname,$ip,$status,$status_descrption,$log_time,$flag);
        unset($_SESSION["adminuser"]);
        //session_destroy();
        echo $this->apiReturn(CODE_SUCCESS,"跳转到登录页面",''); 
	}
    
    //前台退出系统
    public function indexlogout()
    {   
    	$uname = $_SESSION["indexuser"];
		$ip = $_SERVER['REMOTE_ADDR'];
		$status = '1'; //1成功，0失败
		$status_descrption = '3'; //（1用户名、密码错误 2账号被禁用,3正常）
		$log_time = date('Y-m-d H:i:s');
		$flag = '2'; //代表退出
		//写入日志表
		$this->Inout_log($uname,$ip,$status,$status_descrption,$log_time,$flag);
        unset($_SESSION["indexuser"]);
        //session_destroy();
        echo $this->apiReturn(CODE_SUCCESS,"跳转到登录页面",''); 
    }

	//前台用户登录后该用户的信息
	public function indexuser()
	{
        $uname =  $_SESSION['indexuser'];
		//$uname = 'margin';
        $res = $this->db->indexuser($uname);
       if($res){
       	 echo $this->apiReturn(CODE_SUCCESS,"",$res); 
       }else{
       	 echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
       }
	}
}