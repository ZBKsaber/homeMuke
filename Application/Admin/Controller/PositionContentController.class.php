<?php
/**
 * 后台推荐位内容管理相关
 */
namespace Admin\Controller;
use Think\Controller;
class PositionContentController extends CommonController {

    public function index(){
        $conds = array();
        $title = $_GET['title'];
        if($title){
            $conds['title'] = $title;
            $this -> assign('title',$title);
        }
        if ($_GET['position_id']) {
            $conds['position_id'] = intval($_GET['position_id']);
            $this -> assign('position_id',$conds['position_id']);
        }
        // 获取当前页数
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = 10; // 每页显示条数
        $conds['status'] = array('neq',-1);
        // 根据指定的条件,获取推荐位内容
        $positionCS = D('PositionContent')->getPositionC($conds,$page,$pageSize);

        // 获取指定内容的总条数
        $count = D('PositionContent')->getpositonCC($conds);
        $res = new \Think\Page($count,$pageSize); // 实例化分页类
        $pageres = $res -> show();
        //获取所有状态为正常的推荐位栏目
        $positions = M('Position')->field('id,name,create_time,status')
        ->where(array('status'=>1))
        ->order('id desc')
        ->select();
        $this -> assign('positions',$positions);
        $this -> assign('positionCS',$positionCS);
        $this -> assign('pageres',$pageres);
    	$this->display();
    }
}
