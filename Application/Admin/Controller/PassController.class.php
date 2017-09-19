<?php
namespace Admin\Controller;

/**
 * 后台pass管理控制器
 * @author wt
 */

class PassController extends AdminController {
	public function index()
	{
		// echo 1;
		// die();
		$pageindex = $_GET["p"];
		if (empty($pageindex) || $pageindex == "0") {
			$pageindex = 1;
		}
		$pagesize = 10;
		$keys = I('keys');
		if (is_numeric($keys)) {
			$map['id|name'] = array(intval($keys), array('like', '%' . $keys . '%'), '_multi' => true);
		} else {
			$map['name'] = array('like', '%' . (string)$keys . '%');
		}
		// $name = session('user_auth.username');
		// if ($name == 'admin') {
			$list = M('voucher_activity')->where($map)->page($pageindex, $pagesize)->order('id desc')->select();
			/*var_dump($list);
			echo 123;*/
		// } else {
		// 	/*echo 321;
		// 	var_dump($_SESSION);*/
		// 	$id = session('user_auth.uid');
		// 	$rules = M('Member')->where("uid = $id")->field('mallrules')->find();
		// 	$storer = M('Member')->where("uid = $id")->field('storerules')->find();
		// 	if ($rules) {
		// 		$rulid = explode(',', $rules['mallrules']);
		// 		$store1 = '';
		// 		foreach ($rulid as $r) {
		// 			$store = M('Pass')->where('mall_id=%d', $r)->field('id')->select();
		// 			if ($store){
		// 				foreach ($store as $s) {
		// 					$store1 = $store1 . $s['id'] . ',';
		// 				}
		// 			}
		// 		}
		// 	}
		// 	if ($storer['storerules']) {
		// 		$store = $store1 . $storer['storerules'];
		// 	} else {
		// 		$store = $store1 . $store['storerules'];
		// 	}
		// 	echo $store;
		// 	$map['id'] = array('in', $store);
		// 	$list = M('Pass')->where($map)->page($pageindex, $pagesize)->select();
		// }


		$count = M('voucher_activity')->where()->count();
		// echo $count;
		if ($count > $pagesize) {
			$page = new \Think\Page($count, $pagesize);
			$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
			$this->assign('_page', $page->show());
		}

		// var_dump($list);
		// die();
		$this->assign('list',$list);
		$this->display();
	}

	public function edit(){
		if(IS_POST){
			//print_r(I('all_num'));exit();
			$data['id'] = I('id');
			//如果是新增，则添加总数量，当前数量，冻结数量
			if($data['id']){
				$pass_id = $data['id'];
				//$oldpackage = M('pass')->where('id='.$pass_id)->getField('package_id');
				//TODO如果是编辑，数量也可以修改，但是不能低于当前以往总数减去当前总数
				$data['all_num'] = intval(I('all_num'));
				$pass = M('pass')->where('id=%d',$data['id'])->find();
				if( $data['all_num'] < ( intval($pass['all_num']) - intval($pass['now_num']) ) ){

					$this->error('发行总数量不得低于当前冻结数量与已销售数量的总和!');
				}else{
					$data['now_num'] = intval($pass['now_num']) - (intval($pass['all_num']) - $data['all_num']);
				}

			}else{
				$data['all_num'] = I('all_num');
				$data['now_num'] = $data['all_num'];
				$data['frozen_num'] = 0;				
			}
			$data['name'] = I('name');
			$data['mall_id'] = I('mall_id');
			$data['sell_time'] = I('sell_time');
			$data['store_id'] = I('store_id');
			$data['desc'] = I('desc');
			$data['cost_price'] = I('cost_price');
			$data['sell_price'] = I('sell_price');
			$data['edge'] = I('edge');
			$data['valid_start_time'] = I('valid_start_time');
			$data['valid_end_time'] = I('valid_end_time');
			$data['limit_num'] = I('limit_num');

			$data['unable_date'] = I('unable_date');
			$data['able_time'] = I('able_time');
			$data['requirement'] = I('requirement');
			$data['tips'] = I('tips');
			$data['type'] = I('type');
			if($data['type'] == 1){
				if($data['sell_price'] < 0.01){
					$this->error('收费卡最低收费0.01元');
				}
			}
			if($data['type'] == 3){
				$data['sell_price'] = 0.00;
				// $data['cost_price'] = 0.00;
			}
			$data['price_man'] = I('price_man');
			$data['price_jian'] = I('price_jian');
			$data['format'] = I('format');
			$data['color'] = I('color');
			$data['personal'] = I('personal');
			$data['sift'] = I('sift');
			// $data['package_id'] = I('package_id');
			$data['scene'] = implode(',',I('scene'));
			$data['category'] = implode(',',I('category'));
			$data['search_keys'] = I('search_keys');
			$pass = D('pass');
			$res = $pass->update($data);
			if($res){
				
				$this->success('编辑成功');
			}else{
				$error = $pass->getError();
				if(empty($data['id'])){
					$this->error(empty($error) ? '添加失败！' : $error);
				}else{
					$this->error(empty($error) ? '修改失败！' : $error);
				}	
			}
		}else{
			$id = I('id');
			$pass = M('pass')->where('id='.$id)->find();
			$format = M('format')->select();
			$color = M('color')->select();
			$mall = M('mall')->field('id,name')->select();
			$store = M('store')->field('id,name')->select();
			$package = M('user_package')->field('id,desc')->where('uid=0')->select();
			$scene = M('scene')->field('id,name')->select();
			$category = M('PassCategory')->field('id,name')->select();
			$this->assign('category',$category);
			$this->assign('scene',$scene);
			$this->assign('package',$package);
			$this->assign('color',$color);
			$this->assign('store',$store);
			$this->assign('mall',$mall);
			$this->assign('format',$format);
			$this->assign('list',$pass);
			$this->display();
		}
	}

	public function del(){
		$id = I('id');
		if(empty($id)){
			$this->error('请选择所需删除的卡券');
		}
		$res = M('pass')->where('id='.$id)->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	//根据购物中心id选择店铺
	public function selectStore(){
		$mall_id = $_POST['mall'];
		$store = M('store')->where('mall_id='.$mall_id)->field('id,name')->order('id')->select();
		if($store){
			$this->ajaxReturn($store,"JSON");
		}else{
			$this->ajaxReturn('',"JSON");

		}
	}
	//根据购物中心id选择店铺
	public function selectPackage(){
		$mall_id = $_POST['mall'];
		$package = M('user_package')->where('uid = 0 and mall_id='.$mall_id)->field('id,desc')->order('id')->select();
		if($package){
			$this->ajaxReturn($package,"JSON");
		}else{
			$this->ajaxReturn('',"JSON");

		}
	}

	//活动特殊扣率申请
	public function special_deduction(){
		if(IS_POST){
			$maps['pass_id'] = I("pass_id");
			$maps['deduction'] = trim(I("deduction"));
			$maps['beg_time']  = strtotime(I("beg_time"));
			$maps['end_time'] = strtotime(I("end_time"));
			$maps['reason'] = trim(I("reason"));

			foreach ($maps as $m) {
				if($m == ""){
					$this->error('申请特殊扣率每个栏目必须填写，不能为空');
				}
			}

			$pass = M('pass')->where("id=%d",$maps['pass_id'])->find();
			$res = M('pass_sd')->where("pass_id=".$pass['id'])->find();
			if($res['status'] == '1'){
				$this->error('申请成功的活动不得重复申请');
			}

			if(($maps['beg_time'] < strtotime($pass['valid_start_time'])) || ($maps['beg_time'] > strtotime($pass['valid_end_time'])) || ($maps['end_time']  < strtotime($pass['valid_start_time'])) || ($maps['end_time'] > strtotime($pass['valid_end_time'])) ||  ($maps['beg_time'] >= $maps['end_time']) ){

				$this->error('请填写正确的时间');
			}

			$maps['addtime'] = time();
			$maps['status'] = 0;
			$maps['apply_member'] = is_login();

			$res = M('pass_sd')->add($maps);
			if($res){
				$this->success('特殊扣率申请成功，请耐心等待后台审核！');
			}else{
				$this->error('系统错误');
			}


		}else{
			$pass_id = I("id"); 
			$pass = M('pass')->where("id=%d",$pass_id)->find();

			$map['status'] = array('in','0,1');
			$map['pass_id'] = $pass_id;
			$sd = M('pass_sd')->where($map)->find();
			if($sd){
				$sd['end_time'] = date("Y-m-d",$sd['end_time'] );
				$sd['beg_time'] = date("Y-m-d",$sd['beg_time'] );
				//print_r($pass);exit();
				$this->assign('sd',$sd);
			}
			//print_r($sd);exit();
			$this->assign('pass',$pass);
			$this->display();
		}
	}
}