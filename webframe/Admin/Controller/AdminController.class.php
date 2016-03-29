<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Behavior;

class AdminController extends Controller{
	/*
	类的构造函数
	该方法主要用于去调用行为扩展类的run()方法
	*/
	public function __construct(){
		parent::__construct();
		if(session("?loginuser")){  //表示当前人已经登录，则我们应该获取
			$loginuser=session("loginuser");  //获取当前登录人的session值
			if($loginuser!="admin"){  //只有在当前登录人不是admin的时候，我们才需要去获取当前人执行的请求方法，然后进行权限对比
				$con_name=CONTROLLER_NAME;
				$fun_name=ACTION_NAME;
				$url=$con_name."/".$fun_name;
				$access_urls=session("access_urls");  //将所有目前session中的权限获取出来
				
				//echo "<script>alert('".$url."')</script>";
				if($fun_name!="ajaxIndex"){				

					//echo "<script>alert('".$url."')</script>";
					if($url=="Index/index"||$url=="Index/head"||$url=="Index/left"||$url=="Index/right"){  //我们默认给出显示首页的三个方法的权限
						
					}
					else{
						$is_have="error";
						foreach ($access_urls as $key => $value) {
							# code...
							if(in_array($url, $value)){
								$is_have="ok";
								break;
							}
						}
						
						if($is_have=="error"){  //若最终为error，则表示当前没有该权限
							echo "<script>alert('您不具备该权限!')</script>";
							die();
						}
					}
				}
			}

		}
		else{
			$this->redirect("Login/showLogin");
		}
	}

	public function index(){
		$this->display();
	}

}

