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
        //获取所有状态为正常的推荐位栏目
        $positions = M('Position')->field('id,name,create_time,status')
        ->where(array('status'=>1))
        ->order('id desc')
        ->select();
        $this -> assign('positions',$positions);
    	$this->display();
    }
}
