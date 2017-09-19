<?php
namespace Admin\Controller;

/**
 * 后台场景管理控制器
 * @author wt
 */

class SceneController extends AdminController {
	public function index(){
		$list = M('Scene')->select();
		$this->assign('list',$list);
		$this->display();
	}

	public function edit(){
		if(IS_POST){
			$data['id'] = I('id');
			$data['name'] = I('name');
			$Scene = D('Scene');
			$res = $Scene->update($data);
			if($res){
				if(empty($data['id'])){
					$this->success('添加成功');
				}else{
					$this->success('编辑成功');
				}
			}else{
				$error = $Scene->getError();
				if(empty($data['id'])){
					$this->error(empty($error) ? '添加失败！' : $error);
				}else{
					$this->error(empty($error) ? '修改失败！' : $error);
				}	
			}
		}else{
			$id = I('id');
			$Scene = M('Scene')->where('id='.$id)->find();
			$this->assign('list',$Scene);
			$this->display();
		}
	}

	public function del(){
		$id = I('id');
		if(empty($id)){
			$this->error('请选择所需删除的场景');
		}
		$res = M('Scene')->where('id='.$id)->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}