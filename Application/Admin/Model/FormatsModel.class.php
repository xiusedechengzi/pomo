<?php
namespace Admin\Model;
use Think\Model;

/**
 * 类别模型
 * @author cl
 */

class FormatsModel extends Model {
    protected $_validate = array(
        array('name', 'require', '名称不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );
	// protected $_auto = array(
 //        array('addtime', NOW_TIME, self::MODEL_INSERT),
 //    );
	
	public function update($data){
	    $data = $this->create($data);
	    if(!$data){ //数据对象创建错误
	    	return false;
	    }	
	    /* 添加或更新数据 */
	    if(empty($data['id'])){
	        $res = $this->add();
	    }else{
	        $res = $this->save();
	    }
	    return $res;
	}

	//获取所有项的名字和id
	public function getAll(){
		$data = $this->field('id,name')->order('id desc')->select();
		return $data;
	}

}