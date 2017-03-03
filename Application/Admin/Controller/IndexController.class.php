<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {

    public function index(){
        // 获取今日登陆的用户数
        $num = D('Admin') -> getLoginNum();
        // 获取总共的文章数量
        $newCount = D('News') -> getNewsCount();
        // 获取推荐位数量
        $positionCount = D('Position') -> getPositionCount();
        // 获取阅读数最大的文章
        $maxRead = D('News') -> getMaxRead();
        $this -> assign('num',$num);
        $this -> assign('newCount',$newCount);
        $this -> assign('positionCount',$positionCount);
        $this -> assign('maxRead',$maxRead);
    	$this->display();
    }

    public function main() {
    	$this->display();
    }
}
