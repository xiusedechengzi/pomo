<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;


/**
 * 后台pomo控制器
 * @author cl
 */
class PomoController extends AdminController {

    /**
     * 后台首页
     * @author cl
     */
    public function index(){
        $pageindex = $_GET["p"];
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
        $list = M('mall')->field('id,city_id,name,image,addtime,longitude,latitude')->where($map)->page($pageindex,$pagesize)->select();
        $count = M('mall')->where($map)->count();
        if($count > $pagesize) {
            $page = new \Think\Page($count, $pagesize);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->assign('list',$list);
        $this->display();
    }

}
