<?php
/**
 * 后台推荐位管理相关
 */
namespace Admin\Controller;
use Think\Controller;
class PositionController extends CommonController {

    public function index(){
        // 获取所有推荐位
        $positions = M('Position')->field('id,name,create_time,status')->select();
        $this -> assign('positions',$positions);
    	$this->display();
    }
}
