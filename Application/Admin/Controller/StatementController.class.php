<?php
namespace Admin\Controller;

/**
 * 后台pomo报表管理控制器
 * @author cl
 */

class StatementController extends AdminController {

	public function userinfo(){
		$pageindex = $_GET["p"];
		if (empty($pageindex)||$pageindex=="0") {
			$pageindex=1;
		}
		$pagesize = 10;
		$keys = I('keys');
		if(is_numeric($keys)){
            $map['id|ads_title'] = array(intval($keys),array('like','%'.$keys.'%'),'_multi'=>true);
        } else {
            $map['ads_title'] = array('like', '%'.(string)$keys.'%');
        }
		$list = M('Frontuser')->where($map)->page($pageindex,$pagesize)->select();
		// foreach ($list as $key => &$value) {
		// 	if(!empty($value)){
		// 		if($value['is_pomo'] == 0){
		// 			$value['is_pomo'] = '购物中心广告';
		// 		}else{
		// 			$value['is_pomo'] = '全平台广告';
		// 		}
		// 		if($value['mall_id']){
		// 			$mall_id = $value['mall_id'];
		// 			$mall_id = explode(',',$mall_id);
		// 			foreach ($mall_id as $k => $val) {
		// 	            $name[] = M('mall')->where('id='.$val)->getField('name');
		// 	        }
		// 	        $value['mall_id'] = implode(',',$name);
		// 		}else{
		// 			$value['mall_id'] = '全平台广告';
		// 		}
		// 	}
		// }
		$count = M('ads')->where($map)->count();
		if($count > $pagesize) {
            $page = new \Think\Page($count, $pagesize);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        // var_dump($list);
        // var_dump($count);
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
			$ad = M('ad')->where('id='.$id)->find();
			$mall = M('mall')->field('id,name')->select();
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
	public function ucheck(){
		$info=I('post.info');
        $lasttime=I('post.lasttime');
        $hobby=I('post.favorite');
        if($info!=''){
            $user=D('Users');
            $data=$user->getAll($info,$lasttime,$hobby);
            //print_r($data);die;
            $this->assign('info',$data) ;
            $this->display();
        }else{
            $this->display();
        }

	}
}