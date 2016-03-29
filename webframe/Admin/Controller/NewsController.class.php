<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\NewsCateModel;
use Admin\Model\NewsWorkflowModel;
use Admin\Model\OrgModel;
use Admin\Model\CateModel;
use Admin\Model\NewsModel;

class NewsController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	列表页显示
	*/
	public function index(){
		$this->display("index");  
	}

	/*
	信息分类列表页
	*/
	public function cateIndex(){
		$this->display("cateIndex");
	}

	/*
	分类选择操作
	*/
	public function cateSelect(){
		$cate_rows=M("category")->where("parentid=0 and ishidden=0")->field("cate_id,cate_name")->select();  //只取出最顶级菜单
		$this->assign("cate_rows",$cate_rows);
		$this->display("cateSelect");
	}

	/*
	分类选择入库
	*/
	public function cateSelectInsert(){
		$data_post=I("post.");  //获取所有post过来的数据
		$data=array(
			"cate_id"=>$data_post['cate_id'],
			"isenable"=>$data_post['isenable']
		);
		if($data_post['isenable']=="0"){
			$this->_setCateEnable(1);
		}
		$obj=new NewsCateModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	将数据库中是否可用的状态进行变换的方法
	*/
	public function _setCateEnable($isenable){
		$obj=new NewsCateModel();
		$return=$obj->setCateEnable($isenable);
	}

	/*
	信息分类获取列表数据的方法	
	*/
	public function newsCateAjaxIndex(){
		$order=I("post.sort")." ".I("post.order");
		if(I("post.sort")&&I("post.order")){
			$data=M()->table(array("category"=>"cate","news_category"=>"news_cate"))
			->field("cate.cate_name,news_cate.isenable,news_cate.news_cate_id")
			->where("cate.cate_id=news_cate.cate_id")->order($order)->limit(I("post.offset"),I("post.limit"))->select();
		}
		else{
			$data=M()->table(array("category"=>"cate","news_category"=>"news_cate"))
			->field("cate.cate_name,news_cate.isenable,news_cate.news_cate_id")
			->where("cate.cate_id=news_cate.cate_id")->limit(I("post.offset"),I("post.limit"))->select();
		}
		//var_dump($data);die();

		foreach ($data as $key => $value) {
			# code...
			$data[$key]['isenableText']=$value['isenable']=="0"?"<label style='color:#5CB85C;'>启用</label>":"<label style='color:red;'>禁用</label>";
		}
		$data_count=M()->table(array("category"=>"cate","news_category"=>"news_cate"))
			->field("cate.cate_name,news_cate.isenable,news_cate.news_cate_id")
			->where("cate.cate_id=news_cate.cate_id")->count();

		$return=array(
			"total"=>$data_count,
			"data"=>$data
		);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	设置禁用或启用的方法
	*/
	public function setNewsCateIsEnable(){
		$news_cate_id=I("post.id");
		$flag=I("post.flag");
		if($flag==0){  //当前表示要启用某个记录，则我们应该把其它的先全部设置为1
			$this->_setCateEnable(1);
		}
		$data=array(
			"isenable"=>$flag
		);
		$obj=new NewsCateModel();
		$return=$obj->editSave($news_cate_id,$data);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	删除某个所选分类
	正在启用状态的分类无法删除
	*/
	public function newsCatedel(){
		$id=I("post.id");  
		$rows=M("news_category")->where("news_cate_id='".$id."'")->field("isenable")->select();
		if($rows[0]["isenable"]==0){
			$return=array(
				"status"=>false,
				"msg"=>"该分类正在处于启用状态，无法删除"
			);
		}
		else{
			$obj=new NewsCateModel();
			$return=$obj->del($id);
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	工作流选取列表页显示
	*/
	public function workflowIndex(){
		$this->display("workflowIndex");
	}

	/*
	工作流选取页面显示
	*/
	public function workSelect(){
		$workflow_rows=M("workflow")->field("workflow_id,workflow_name")->select();  //只取出最顶级菜单
		$this->assign("workflow_rows",$workflow_rows);
		$this->display("workSelect");
	}

	/*
	工作流选择入库操作
	*/
	public function workflowSelectInsert(){
		$data_post=I("post.");  //获取所有post过来的数据
		$data=array(
			"workflow_id"=>$data_post['workflow_id'],
			"isenable"=>$data_post['isenable']
		);
		if($data_post['isenable']=="0"){
			$this->_setWorkflowEnable(1);
		}
		$obj=new NewsWorkflowModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	设置全部工作流为禁用操作
	*/
	private function _setWorkflowEnable($isenable){
		$obj=new NewsWorkflowModel();
		$return=$obj->setCateEnable($isenable);
	}

	/*
	获取工作流列表数据
	*/
	public function newsWorkflowAjaxIndex(){
		$order=I("get.sort")." ".I("get.order");
		//var_dump($order);
		if(I("get.sort")&&I("get.order")){
			$data=M()->table(array("workflow"=>"wf","news_workflow"=>"news_wf"))
			->field("wf.workflow_name,news_wf.isenable,news_wf.news_workflow_id")
			->where("wf.workflow_id=news_wf.workflow_id")->order($order)->limit(I("post.offset"),I("post.limit"))->select();
			//var_dump($order);die();
		}
		else{
			$data=M()->table(array("workflow"=>"wf","news_workflow"=>"news_wf"))
			->field("wf.workflow_name,news_wf.isenable,news_wf.news_workflow_id")
			->where("wf.workflow_id=news_wf.workflow_id")->limit(I("post.offset"),I("post.limit"))->select();
		}
		//var_dump($data);die();
		

		foreach ($data as $key => $value) {
			# code...
			$data[$key]['isenableText']=$value['isenable']=="0"?"<label style='color:#5CB85C;'>启用</label>":"<label style='color:red;'>禁用</label>";
		}
		$data_count=M()->table(array("workflow"=>"workflow","news_workflow"=>"news_wf"))
			->field("workflow.workflow_name,news_wf.isenable,news_wf.news_workflow_id")
			->where("workflow.workflow_id=news_wf.workflow_id")->count();

		$return=array(
			"total"=>$data_count,
			"data"=>$data
		);
		//var_dump($return);die();
		$this->ajaxReturn($return,"JSON");
	}

	/*
	设置工作流启用或禁用的方法
	*/
	public function setNewsWorkflowIsEnable(){
		$news_workflow_id=I("post.id");
		$flag=I("post.flag");
		if($flag==0){  //当前表示要启用某个记录，则我们应该把其它的先全部设置为1
			$this->_setWorkflowEnable(1);
		}
		$data=array(
			"isenable"=>$flag
		);
		$obj=new NewsWorkflowModel();
		$return=$obj->editSave($news_workflow_id,$data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	工作流选取删除的方法
	*/
	public function newsWorkflowedel(){
		$id=I("post.id");  
		$rows=M("news_workflow")->where("news_workflow_id='".$id."'")->field("isenable")->select();
		if($rows[0]["isenable"]==0){
			$return=array(
				"status"=>false,
				"msg"=>"该分类正在处于启用状态，无法删除"
			);
		}
		else{
			$obj=new NewsWorkflowModel();
			$return=$obj->del($id);
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	信息添加页面显示
	*/
	public function add(){
		$loginuser=session("loginuser");  //获取当前登录人
		$loginuserid=session("loginuserid");  //获取当前登录人主键id
		$obj=new OrgModel();
		if($loginuser=="admin"){  //若当前登录人为admin，则给出所有的组织机构
			
			$org_rows=$obj->getOrgTreeRows();
		}
		else{
			$user_org=M("user")->where("user_id='".$loginuserid."'")->field("org_id")->select();
			$org_rows_last=M("organization")->where("org_id='".$user_org[0]['org_id']."'")->field("org_id,org_name")->select();
			
			$org_rows=$obj->getOrgTreeRows($user_org[0]['org_id']);  //获取子机构
			$org_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		}
		//var_dump($org_rows);die();
		$select_cate=M("news_category")->where("isenable=0")->field("cate_id")->select();
		$obj_cate=new CateModel();
		$cate_rows=$obj_cate->getCateTreeRows($select_cate[0]['cate_id']);

		$this->assign("cate_rows",$cate_rows);
		$this->assign("org_rows",$org_rows);

		$this->display("add");
	}

	/*
	信息添加操作
	*/
	public function insert(){
		$data_post=I("post.");

		$step_rows=$this->_getWorkflowStep();  //获取当前启用的工作流步骤
		$loginuserroleid=session("loginuserroleid");  //获取当前登录人的角色id
		$loginuser=session("loginuser");
		$workflow_select=M("news_workflow")->where("isenable=0")->field("workflow_id")->select();  //获取当前启用工作流
		$workflow_row=M("workflow")->where("workflow_id='".$workflow_select[0]['workflow_id']."'")->select();
		if($loginuser=="admin"){
			
			$workflow_step=$workflow_row[0]['steps'];  //若当前为admin添加，则信息直接到最高步骤，审核通过
			$ex_status=3;  //0：添加完成 1：正在审核中 2：审核未通过 3：审核通过
			$re_status=1;  //已发布
		}
		else{
			foreach($step_rows as $key=>$val){
				if($loginuserroleid==$val['role_id']){
					$step_current=$val['step_now'];  //获取当前正处于的步骤
					break;
				}
			}
			$workflow_step=$step_current;  //表示当前正在进行的步骤
			$step_total=$workflow_row[0]['steps'];  //得到当前启用工作流的步骤总数
			if($step_current==1){  //表示当前处于最底层步骤中
				$ex_status=0;
				$re_status=0;  //未发布
			}
			else if($step_current>1&&$step_current<$step_total){  //表示当前为处于中间的步骤的人添加的
				$ex_status=0;
				$re_status=0;  //未发布
			}
			else if($step_current==$step_total){  //表示当前是最高步骤的人添加的
				$ex_status=3;  //最高添加人添加的直接审核通过
				$re_status=1;  //0:未发布 1:已发布
			}
		}
		

		$data=array(
			"news_name"=>$data_post['news_name'],
			"news_time"=>strtotime($data_post['news_time']),
			"news_content"=>$data_post['news_content'],
			"news_cate_id"=>$data_post['news_cate_id'],
			"news_org_id"=>$data_post['news_org_id'],
			"addtime"=>time(),
			"add_account"=>session('loginaccount'),
			"workflow_step"=>$workflow_step,
			"ex_status"=>$ex_status,
			"re_status"=>$re_status,
			"isshow"=>0
		);
		//var_dump($data);die();
		$obj=new NewsModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	获取当前启用工作流的步骤情况
	*/
	private function _getWorkflowStep(){
		$workflow_select=M("news_workflow")->where("isenable=0")->field("workflow_id")->select();  //获取当前启用工作流
		$step_rows=M("workflowstep")->where("workflow_id='".$workflow_select[0]['workflow_id']."'")->field("step_now,role_id")->select();
		return $step_rows;
	}

	

}