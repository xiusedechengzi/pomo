<?php
namespace Admin\Controller;

/**
 * 后台pomo购物中心管理控制器
 * @author cl
 */

class SignController extends AdminController {

	public function index(){
		// echo 123;
		// die();
		$pageindex = $_GET["p"];
		//file_put_contents('111.txt', json_encode($pageindex ));
		if (empty($pageindex)||$pageindex=="0") {
			$pageindex=1;
		}
		$pagesize = 10;
		$keys = I('keys');
		if(is_numeric($keys)){
            $map['id|name'] = array(intval($keys),array('like','%'.$keys.'%'),'_multi'=>true);
        } else {
            $map['name'] = array('like', '%'.(string)$keys.'%');
        }

        // $mall_id = I('mall_id');
        // if($mall_id > 0){
        // 	$map['mall_id'] = $mall_id;
        // }

		// $name =  session('user_auth.username');
		// if($name == 'admin'){
			$list = M('sign')->where($map)->page($pageindex,$pagesize)->select();
		// }else {
		// 	$id = session('user_auth.uid');
		// 	$rules = M('Member')->where("uid = $id")->field('mallrules')->find();
		// 	$storer = M('Member')->where("uid = $id")->field('storerules')->find();
		// 	if ($rules) {
		// 		$rulid = explode(',', $rules['mallrules']);
		// 		$store1 = '';
		// 		foreach ($rulid as $r) {
		// 			$store = M('store')->where('mall_id=%d', $r)->field('id')->select();
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
		// 	$map['id'] = array('in', $store);
		// 	$list = M('Store')->where($map)->page($pageindex, $pagesize)->select();
		// 	}
			// var_dump($list);

			$count = M('sign')->where($map)->count();
			if($count > $pagesize) {
				$page = new \Think\Page($count, $pagesize);
				$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
				$this->assign('_page', $page->show());
			}
			// var_dump($list);
			// die();

		$this->assign('list',$list);
		$this->display();
	}

	public function edit(){
		if(IS_POST){
			$data['id'] = I('id');
			$data['title'] = I('title');//购物中心
			$data['image'] = I('image');
			$data['is_pomo'] = I('is_pomo');
			$data['mall_id'] = implode(',',I('mall_id'));
			$ad = D('ad');
			$res = $ad->update($data);
			if($res){
				if(empty($data['id'])){
					$this->success('添加成功');
				}else{
					$this->success('编辑成功');
				}
			}else{
				$error = $ad->getError();
				if(empty($data['id'])){
					$this->error(empty($error) ? '添加失败！' : $error);
				}else{
					$this->error(empty($error) ? '修改失败！' : $error);
				}	
			}
		}else{
			$id = I('id');
			$ad = M('store')->where('id='.$id)->find();
			$mall = M('mall')->field('id,name')->select();
			// var_dump($ad);
			$this->assign('mall',$mall);
			$this->assign('list',$ad);
			$this->display();
		}
	}

	public function del(){
		$id = I('id');
		if(empty($id)){
			$this->error('请选择所需删除的广告');
		}
		$res = M('ad')->where('id='.$id)->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	public function image_upload(){
		$file_name = 'Ad';
		$result = imageUpload($file_name);
		$this->ajaxReturn($result);
	}

	public function test(){
		$id = '1,2';
		if($id){
	        $id = explode(',',$id);
	        foreach ($id as $key => $value) {
	            $name[] = M('mall')->where('id='.$value)->getField('name');
	        }
	        $name = implode(',',$name);
	    }else{
	        $name = '未分配购物中心';
	    }
	    print_r($name);die;
	}
}