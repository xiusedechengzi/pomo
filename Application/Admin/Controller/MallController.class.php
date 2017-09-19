<?php
namespace Admin\Controller;

/**
 * 后台pomo购物中心管理控制器
 * @author cl
 */

class MallController extends AdminController {

	public function index()
	{
		$pageindex = $_GET["p"];
		if (empty($pageindex) || $pageindex == "0") {
			$pageindex = 1;
		}
		$pagesize = 10;
		$keys = I('keys');
		if (is_numeric($keys)){
			$map['id|name'] = array(intval($keys), array('like', '%' . $keys . '%'), '_multi' => true);
		} else {
			$map['name'] = array('like', '%' . (string)$keys . '%');
		}

		$newlist = array();

		// $name =  session('user_auth.username');
		if($keys){
			$newlist = M('mall')->where($map)->page($pageindex,$pagesize)->select();
			// var_dump($newlist);
			// echo 123;
			$count = M('mall')->where($map)->count();
		}else {
			// echo 123;
			// die();
			// var_dump($_SESSION);
			// $id = session('user_auth.uid');
			// $rules = M('Member')->where("uid = $id")->field('mallrules')->find();
			// $rulid = explode(',', $rules['mallrules']);
			// var_dump($rulid);
			$newlist = M('mall')->page($pageindex, $pagesize)->select();
			$count = M('mall')->where()->count();
			// var_dump($count);
			// echo "</br>";
			// var_dump($newlist);
			// $newlist = array();
			// foreach ($rulid as $v) {
				// $list[] = M('mall')->where("id=" . $v)->page($pageindex, $pagesize)->select();
			// }

			// $newlist = array();
			// foreach ($list as $v => $k) {
			// 	foreach ($k as $key => $vall) {
			// 		$newlist[] = $vall;
			// 	}
			// }
		}
			// $count = M('mall')->where($map)->count();
			// var_dump($count);
			if ($count > $pagesize) {
				$page = new \Think\Page($count, $pagesize);
				$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
				$this->assign('_page', $page->show());
			}
			// $this ->assign('list',$newlist);
			// var_dump($newlist);
			$this->assign('list',$newlist);
			$this->display();
	}
	public function edit(){
		if(IS_POST){
			$data['id'] = I('id');
			$data['city_id'] = I('city_id');
			$data['name'] = I('name');//购物中心
			$data['image'] = I('image');
			$data['address'] = I('address');
			$data['longitude'] = I('longitude');
			$data['latitude'] = I('latitude');
			$mall = D('mall');
			$res = $mall->update($data);
			if($res){
				if(empty($data['id'])){
					$this->success('添加成功');
				}else{
					$this->success('编辑成功');
				}
			}else{
				$error = $mall->getError();
				if(empty($data['id'])){
					$this->error(empty($error) ? '添加失败！' : $error);
				}else{
					$this->error(empty($error) ? '修改失败！' : $error);
				}	
			}
		}else{
			$id = I('id');
			$mall = M('mall')->where('id='.$id)->find();
			$city = M('city')->select();
			$this->assign('city',$city);
			$this->assign('list',$mall);
			$this->display();
		}
	}

	public function del(){
		$id = I('id');
		if(empty($id)){
			$this->error('请选择所需删除的购物中心');
		}
		$res = M('mall')->where('id='.$id)->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	public function image_upload(){
		$file_name = 'Mall';
		$result = imageUpload($file_name);
		$this->ajaxReturn($result,'JSON');
	}

	public function package(){
		$mall_id = I('id');
		$map['mall_id'] = $mall_id;
		$map['uid'] = 0;
		$list = M('user_package')->where($map)->select();
		$this->assign('mall_id',$mall_id);
		$this->assign('list',$list);
		$this->display();
	}
	public function packagedel(){
		$id = I('id');
		if(empty($id)){
			$this->error('请选择所需删除的组合包');
		}
		$res = M('user_package')->where('id='.$id)->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	//组合包以后放在一个单独的栏目 TODO by zl
	public function packageedit(){
		if(IS_POST){
			$data['mall_id'] = I('mall_id');
			$data['package_id'] = I('package_id');
			$data['des'] = I('des');
			$data['beg_time'] = strtotime(I('beg_time'));
			$data['end_time'] = strtotime(I('end_time'));
			$data['pass'] = I('data');
			$data['sell_num'] = I('sell_num');
			$data['limit_num'] = I('limit_num');


			$old_price = 0;
			$real_price = 0;
			foreach ($data['pass'] as $p) {
				$old_price = $old_price + floatval($p['sell_price']);
				$real_price = $real_price + floatval($p['real_price']);
			}

			//如果木有package_id则新增，反之修改
			if($data['package_id'] == ""){
				$maps['uid'] = 0;
				$maps['mall_id'] = $data['mall_id'];
				$maps['addtime'] = time();
				$maps['sell_price'] = $real_price;
				$maps['cost_price'] = $old_price;
				$maps['desc'] = $data['des'];
				$maps['beg_time'] = $data['beg_time'];
				$maps['end_time'] = $data['end_time'];
				$maps['sell_num'] = $data['sell_num'];
				$maps['limit_num'] = $data['limit_num'];

				$res = M('user_package')->add($maps);
				if($res){

					$data['package_id'] = $res;
					foreach ($data['pass'] as $p) {
						$map1['package_id'] = $data['package_id'];
						$map1['pass_id'] = $p['pass_id'];
						$map1['real_price'] = $p['real_price'];
						$map1['addtime'] = time();
						$res = M('package_pass')->add($map1);

						//加入组合包的数量将被冻结
						$pass = M('pass')->where("id=%d",$p['pass_id'])->field('now_num,frozen_num')->find();
						$map2['now_num'] = intval($pass['now_num']) - intval($data['sell_num']);
						$map2['frozen_num'] = intval($pass['frozen_num']) + intval($data['sell_num']);
						$res=M('pass')->where("id=%d",$p['pass_id'])->save($map2);
					}
					$this->ajaxReturn("ok");			
				}
			}else{
				$maps['uid'] = 0;
				$maps['mall_id'] = $data['mall_id'];
				$maps['addtime'] = time();
				$maps['sell_price'] = $real_price;
				$maps['cost_price'] = $old_price;
				$maps['desc'] = $data['des'];
				$maps['beg_time'] = $data['beg_time'];
				$maps['end_time'] = $data['end_time'];
				$maps['sell_num'] = $data['sell_num'];
				$maps['limit_num'] = $data['limit_num'];

				$res=M('user_package')->where('id=%d',$data['package_id'])->find();
				$old_sell_num = intval($res['sell_num']);

				$res=M('user_package')->where('id=%d',$data['package_id'])->save($maps);
				if($res){
					$res = M('package_pass')->where('package_id=%d',$data['package_id'])->delete();
					if($res){
						foreach ($data['pass'] as $p) {
							$map1['package_id'] = $data['package_id'];
							$map1['pass_id'] = $p['pass_id'];
							$map1['real_price'] = $p['real_price'];
							$map1['addtime'] = time();
							$res = M('package_pass')->add($map1);

							//加入组合包的数量将被冻结
							$pass = M('pass')->where("id=%d",$p['pass_id'])->field('now_num,frozen_num')->find();
							$map2['now_num'] = intval($pass['now_num']) - intval($data['sell_num']) + $old_sell_num;
							$map2['frozen_num'] = intval($pass['frozen_num']) + intval($data['sell_num']) - $old_sell_num;
							$res=M('pass')->where("id=%d",$p['pass_id'])->save($map2);
						}
						$this->ajaxReturn("ok");						
					}
				}				
			}
			
		}else{
			$id = I('id');
			$mall_id = I('mall_id');
			//获取这个商场下所有可用的卡券 add by zl 20170329
			$all_pass = M('pass')->where("mall_id=%d",$mall_id)->field('id,name,sell_price,sell_time,now_num')->select();
			$this->assign('all_pass',$all_pass);
			//add end
			
			//如果存在id，则需要返回默认信息
			if($id){
				$package = M('user_package')->where('id=%d',$id)->find();
				$package['beg_time'] = date("Y-m-d",$package['beg_time']);
				$package['end_time'] = date("Y-m-d",$package['end_time']);
				$this->assign('list',$package);
				$package_pass = M('package_pass')->where('package_id=%d',$id)->select();
				foreach ($package_pass as &$pp) {
					$pass_info = M('pass')->where("id=%d",$pp['pass_id'])->field('sell_price,sell_time,now_num')->find();
					if($pass_info){
						$pp['sell_price'] = $pass_info['sell_price'];
						$pp['sell_time'] = $pass_info['sell_time'];
						$pp['now_num'] = $pass_info['now_num'];
					}
					
				}
				$this->assign('pass_list',$package_pass);
			}
			
			$this->assign('mall_id',$mall_id);
			
			$this->display();
		}
	}

	public function packagepass(){
		$id = I('id');
		$list = M()
			->table('pomo_package_pass as pp')
			->join('left join pomo_pass as p on p.id=pp.pass_id')
			->where('pp.package_id='.$id)
			->field('pp.id,p.desc')
			->select();
		$this->assign('list',$list);
		$this->display();
	}
	public function packagepassdel(){
		$id = I('id');
		$res = M('package_pass')->where('id='.$id)->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}