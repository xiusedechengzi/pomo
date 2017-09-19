<?php
namespace Admin\Model;
use Think\Model;

/**
 * 广告模型
 * @author wt
 */

class AdModel extends Model {
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('image', 'require', '广告图不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );
	protected $_auto = array(
        array('addtime', NOW_TIME, self::MODEL_INSERT),
    );
	
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

}