<?php
namespace app\admins\controller;
use app\admins\controller\Basecontroller;
use app\admins\model\Logmodel;
/**
 * 日志接口
 */
class Logcontroller extends Accountcontroller
{
	
	function __construct()
	{
		parent::__construct();
		$this->db = new Logmodel(); 
	}

	//登录退出日志接口
	public function user_inout_log()
	{
       $res = $this->db->user_inout_log();
       if($res){
       	   echo $this->apiReturn(CODE_SUCCESS,"",$res);             
        }else{
           echo $this->apiReturn(CODE_ERROR,"暂无数据",[]); 
        }    
	}

	//操作日志接口
	public function user_allopt_log()
	{
       $res = $this->db->user_allopt_log();
       if($res){
       	   echo $this->apiReturn(CODE_SUCCESS,"",$res);             
        }else{
           echo $this->apiReturn(CODE_ERROR,"暂无数据",[]); 
        }    
	}
}