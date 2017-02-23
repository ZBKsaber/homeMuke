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

    public function add(){
        if ($_POST) {
            // 判断非空字段
            if(!isset($_POST['name']) || !$_POST['name']){
                return show(0,'推荐名不能为空');
            }
            if(!isset($_POST['description']) || !$_POST['description']){
                return show(0,'描述不能为空');
            }
            $_POST['create_time'] = time();
            $cid = M('position')->add($_POST);
            if ($cid) {
                return show(1,'添加成功');
            }
            return show(0,'添加失败');
        }
        $this -> display();
    }
}
