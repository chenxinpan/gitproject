<?php
/**
 * 首页
 * auth:  陈鑫
 * 日期： 2019-10-23
 */
namespace app\admins\controller;
use app\admins\controller\Basecontroller;
use app\admins\model\Indexmodel;
use think\Request;

class Indexcontroller extends Accountcontroller
{

	function __construct()
	{		
		parent::__construct();
		$this->db = new Indexmodel(); 
        $this->request = Request::instance();
	}

    /************用户列表***********/ 

    //用户列表
    public function userlist()
    {
    	$res = $this->db->userlist();
    	if($res){
			echo $this->apiReturn(CODE_SUCCESS,"",$res);		
		}else{
			echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
		}
    }
    
    //用户状态的修改
    public function userstatus()
    {
         $uid = intval(input('uid'));
         $flag = trim(input('flag'));
         $res = $this->db->userstatus($uid,$flag);         
         if($res){
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        }
    }

    //用户列表人员添加
    public function useradd()
    {   
      $rid = trim(input('rid'));
      $uname = trim(input('uname'));
      $upassword = trim(input('upassword'));
      $cname = trim(input('cname'));
      $id_card = trim(input('id_card'));
      $phone = trim(input('phone'));
      $user_description = trim(input('user_description'));
      $create_date = date("Y-m-d H:i:s",time());	
	  $res = $this->db->useradd($rid,$uname,$upassword,$cname,$id_card,$phone,$user_description,$create_date);
    	if($res){
			echo $this->apiReturn(CODE_SUCCESS,"",$res);		
		}else{
			echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
		}
    }

    //用户列表界面，添加用户时,用户选择角色下拉框
    public function roledisplay()
    {
    	$res = $this->db->roledisplay();
    	if($res){
			echo $this->apiReturn(CODE_SUCCESS,"",$res);		
		}else{
			echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
		}
    }

    //用户列表单个删除
    public function userdel()
    {    
    	$uid = trim(input('uid'));
    	$res = $this->db->userdel($uid);
    	if($res){
			echo $this->apiReturn(CODE_SUCCESS,"",$res);		
		}else{
			echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
		}
    }

    //用户列表批量删除
    public function userbigdel()
    {    
    	//数组形式传参
    	$suid = input('suid/a');  
    	$res = $this->db->userbigdel($suid);
    	//print_r($res);
    	if($suid){
			echo $this->apiReturn(CODE_SUCCESS,"",$suid);		
		}else{
			echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
		}
    }

    //用户列表编辑
    public function useredit()
    {    
    	//数组形式传参
    	$userdata = input('userdata/a');
    	$res = $this->db->useredit($userdata);
    	if($res){
			echo $this->apiReturn(CODE_SUCCESS,"",$res);		
		}else{
			echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
		}
    }
    
    /************用户列表***********/ 


    /************角色列表***********/ 

    //角色列表
    public function rolelist()
    {
    	$res = $this->db->rolelist();
        //对图片路径拼接上入口路径，成为绝对路径
         for($i=0;$i<count($res);$i++)
         {
            $res[$i]['role_img'] = URL_IMG.$res[$i]['role_img'];
            //$res[$i]['role_img'] = $_SERVER['DOCUMENT_ROOT'].$res[$i]['role_img'];
         }
        if($res){
    		echo $this->apiReturn(CODE_SUCCESS,"",$res);		
    	}else{
    		echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
    	}
    }
    
    //新增角色
    public function roleadd()
    { 
        $rname = trim(input('rname'));  //角色名
        $role_permission = input('smid/a'); //权限
        $role_description = trim(input('role_description'));  //角色描述
        $file = $this->request->file('file'); //角色图片文件
        if (empty($file)) 
        { 
             echo $this->apiReturn(CODE_EXIST,"请选择上传图片",$file); 
        }else{
            //移动到框架应用根目录/public/uploads/ 目录下 
            $info = $file->move('uploads/image'); 
            if($info)
            { 
                //图片上传成功 
                $img_name = $info->getSaveName(); 
                $role_img = '/uploads/image/'.$img_name;
                $res = $this->db->roleadd($rname,$role_description,$role_img,$role_permission);
                if($res)
                {
                        if($res=='nopass')
                        {
                            echo $this->apiReturn(CODE_EXIST,"已经存在该角色",$res); 
                        }else{
                            //新增成功
                            echo $this->apiReturn(CODE_SUCCESS,"",$res); 
                        }               
                }else{
                          echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
                     } 
            }else{ 
               //上传失败获取错误信息 
                echo $this->apiReturn(CODE_ERROR,"图片上传失败",$file->getError());
            }              
        }
          
    }
    
    //角色编辑
    public function roleedit()
    {   
        $rid = trim(input('rid'));  //角色id
        $smid = input('smid/a');   //权限集合
        $role_description = trim(input('role_description'));  //角色描述
        $res = $this->db->roleedit($rid,$smid,$role_description);
        if($res){
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        }
    }

    //角色删除
    public function roledel()
    {   
        $rid = trim(input('rid'));  //角色id
        $res = $this->db->roledel($rid);
        if($res){
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        }
    }
     
     //角色分配权限中的权限展示接口
    public function menudisplay()
    {   
        $res = $this->db->menudisplay();
        if($res){
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        }
    }
    
    //角色分配(给用户分配角色)--所有人员名称
    public function rolesend()
    {   
        $res = $this->db->rolesend();
        if($res){
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        }
    }
    
    //角色分配(给用户分配角色)--根据角色返回相应人员
    public function roleuser()
    {   
        $rid = trim(input('rid'));
        $res = $this->db->roleuser($rid);
        if($res){
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        }
    }
    
    //角色分配(给用户分配角色)--根据角色返回角色基本信息
    public function roleinfo()
    {   
        $rid = trim(input('rid'));
        $res = $this->db->roleinfo($rid);
        $res[0]['role_img'] = URL_IMG.$res[0]['role_img'];
        if($res){
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        }
    }
    
    //角色分配(给用户分配角色)
    public function roleto()
    {   
        $rid = trim(input('rid'));
        $suid = input('suid/a');
        if(!empty($suid)&&(isset($suid))){
            $res = $this->db->roleto($rid,$suid);
        }else{
            $res = $this->db->roleto1($rid);
        }       
        if($res){
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        }
    }

    /************角色列表***********/ 


    /************菜单列表***********/ 
    //菜单列表
    public function menulist()
    {
    	$res = $this->db->menulist();
    	if($res){
			echo $this->apiReturn(CODE_SUCCESS,"",$res);		
		}else{
			echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
		}
    }
    
     //菜单添加
    public function menuadd()
    {  
    	$mname = trim(input('mname'));
    	$menu_description = trim(input('menu_description'));
    	$res = $this->db->menuadd($mname,$menu_description);
    	if($res){
    		if($res=='nopass'){
    			echo $this->apiReturn(CODE_EXIST,"已经存在该菜单",$res);
    		}else{
    			echo $this->apiReturn(CODE_SUCCESS,"",$res);
    		}					
		}else{
			echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
		}
    }
    //菜单删除
    public function menudel()
    {
    	$mid = trim(input('mid'));
    	$res = $this->db->menudel($mid);
    	if($res){
			echo $this->apiReturn(CODE_SUCCESS,"",$res);		
		}else{
			echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
		}
    }

    //菜单编辑
    public function menuedit()
    {
    	$mid = trim(input('mid'));
        $mname = trim(input('mname'));
    	$menu_description = trim(input('menu_description'));
    	$res = $this->db->menuedit($mid,$mname,$menu_description);
    	if($res){
			echo $this->apiReturn(CODE_SUCCESS,"",$res);		
		}else{
			echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
		}
    }

    //权限分配(给角色分配权限)
    public function menusend()
    {   
        $rid = trim(input('rid'));
        $smid = input('smid/a');
        $res = $this->db->menusend($rid,$smid); 
        if($res){
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        } 
    }
    
    //权限分配(给角色分配权限)--根据角色返回相应权限
    public function rolemenu()
    {   
        $rid = trim(input('rid'));
        $res = $this->db->rolemenu($rid); 
        if($res){
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        } 
    }

    /************菜单列表***********/ 
    

    /************个人中心***********/ 
    
    public function selfinfo()
    {   
      //未解决session取值问题，跟前端vue打包后，session拿不到
      //已解决问题，本地访问改用ip访问，不要用localhost,保证统一
        $username = $_SESSION['adminuser']; 
        $res = $this->db->selfinfo($username);
        if($res){
            $res['role_img'] = URL_IMG.$res['role_img'];
            echo $this->apiReturn(CODE_SUCCESS,"",$res);        
        }else{
            echo $this->apiReturn(CODE_ERROR,"暂无数据",[]);
        }
    }

    /************个人中心***********/ 
}