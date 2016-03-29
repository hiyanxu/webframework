<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Behavior;

class LoginController extends Controller{
	
	/*
	显示登录方法 
	*/
	public function showLogin(){  //将登录和首页的显示分开，LoginController不去继承AdminController，IndexController中去继承AdminController
		if(session("?loginuser")){
			//$this->display("index");
			//session("loginuser",null);
			//若当前存在session，则证明当前人已经登录，则可以跳转至index页面
			//session("loginuser",null);
			//echo "<script>alert('".session("loginuser")."')</script>";
			$this->redirect("Index/index");
			

		}
		else{
			echo "<script>alert('".session("loginuser")."')</script>";
			$this->display("login");  //否则表示当前没有人登录，则可以显示login页面，让用户去填入信息登录
		}
		
	}

	/*
	登录判断方法
	*/
	public function login(){
		$data_post=I("post.");
		if($data_post["userName"]==""){
			echo "<script>alert('请输入用户名')</script>";
			return;
		}
		if($data_post['userPwd']==""){
			echo "<script>alert('请输入密码')</script>";
			return;
		}
		$row=M("user_account")->where("user_account='".$data_post["userName"]."' and user_pwd='".$data_post["userPwd"]."'")->select();
		
		if(!$row){
			$this->error("用户名或密码错误","showLogin",2);
			return;
		}		
		$login_ip=$_SERVER["REMOTE_ADDR"];
		$datetime=time();
		$data=array(
			"last_log_ip"=>$login_ip,
			"last_log_time"=>$datetime
		);
		M("user_account")->where("user_account='".$data_post["userName"]."' and user_pwd='".$data_post["userPwd"]."'")->save($data);
		session('loginuser',$data_post["userName"]);
		session('loginuserid',$row[0]["user_id"]);
		session('loginuserroleid',$row[0]['role_id']);
		session('loginaccount',$row[0]['user_account_id']);
		if($data_post['userName']!="admin"){
			//当前不是admin的情况下，我们要把所有的权限取出来并存入session中
			$access_ids=M("role_access")->where("role_id='".$row[0]['role_id']."'")->field("access_id")->select();  //获取当前所有的权限id
			$access_urls=array();
			foreach ($access_ids as $key => $value) {
				# code...将所有的该id对应的url操作字段获取出来
				$get_row=M("access")->where("access_id='".$value['access_id']."'")->field("access_url")->select();
				$access_urls[]=$get_row[0];
			}
			//var_dump($access_urls);die();
			session("access_urls",$access_urls);
		}
		//B('Admin\Behavior\AuthCheck');
		$this->redirect("Index/index");
		//$this->display("index");

	
	}

	/*
	头部的显示
	*/
	public function head(){
		$loginuser=I("session.loginuser");
		$this->assign("loginuser",$loginuser);
		$this->display("head");
	}

	/*
	左边的显示
	*/
	public function left(){	//left仅仅用于做菜单显示权限，点击等权限交给行为类去做	
		if(session("loginuser")=="admin"){  //当前是admin，则给出所有菜单权限
			$menu_top_rows=$this->getmenu(0);  //获取所有的最顶级菜单
			$menu_second_rows=array();
			// foreach ($menu_top_rows as $key => $value) {
			// 	# code...
			// 	//var_dump($value["menu_id"]);
			// 	$menu_second_rows[$key]=$this->getmenu($value["menu_id"]);
				
			// }
			$menu_second_rows=M("menu")->where("parentid!=0 and ishidden=0")->order("sort asc")->field("menu_id,menu_name,menu_url,parentid")->select();
			//var_dump($menu_second_rows);
			$this->assign("menu_top_rows",$menu_top_rows);
			$this->assign("menu_second_rows",$menu_second_rows);
			$this->display("left");
		}
		else{
			echo "不好意思，我们要比对权限喽！";
		}
	}

	/*
	右边的显示
	*/
	public function right(){
		$this->display("right");
	}

	/*
	获取菜单的方法
	*/
	public function getmenu($parentid=0){
		$rows=M("menu")->where("parentid='".$parentid."' and ishidden=0")->field("menu_id,menu_name,parentid,menu_url")->order("sort asc")->select();
		return $rows;
	}

}