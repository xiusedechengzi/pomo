<?php
namespace Admin\Controller;

/**
 * 后台业务管理控制器
 * @author cl
 */

class FormatsController extends AdminController {
	public function index(){
		$list = M('Formats')->select();
		// var_dump($list);
		// die();
		$this->assign('list',$list);
		$this->display();
	}

	public function edit(){
		if(IS_POST){
			$data['id'] = I('id');
			$data['name'] = I('name');
			$Formats = D('Formats');
			$res = $Formats->update($data);
			if($res){
				if(empty($data['id'])){
					$this->success('添加成功');
				}else{
					$this->success('编辑成功');
				}
			}else{
				$error = $Formats->getError();
				if(empty($data['id'])){
					$this->error(empty($error) ? '添加失败！' : $error);
				}else{
					$this->error(empty($error) ? '修改失败！' : $error);
				}	
			}
		}else{
			$id = I('id');
			$Formats = M('Formats')->where('id='.$id)->find();
			$this->assign('list',$Formats);
			$this->display();
		}
	}

	public function del(){
		$id = I('id');
		if(empty($id)){
			$this->error('请选择所需删除的类别');
		}
		$res = M('Formats')->where('id='.$id)->delete();
		if($res){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}