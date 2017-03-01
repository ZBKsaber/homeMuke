<?php
/**
 * 后台推荐位管理相关
 */
namespace Admin\Controller;
use Think\Controller;
class PositionController extends CommonController {

    public function index(){
        // 获取所有推荐位
        $positions = M('Position')->field('id,name,description,create_time,status')
            ->where('status=1')->select();
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
            if($_POST['id']){
                return $this -> save($_POST);
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
    /**
     * 显示更新的表单
     */
    public function edit(){
        // 获取要修改的数据
        $positionId = intval($_GET['id']);
        $position = D('Position')->find($positionId);
        $this -> assign('position',$position);
        $this -> display();
    }

    /**
     * 执行表单更新的操作
     */
     public function save($data){
         $positionId = intval($data['id']);
         unset($data['id']);
         try {
             $id = D('Position') -> updatePositionById($positionId,$data);
             if ($id === false) {
                 return show(0,'更新失败');
             }
             return show(1,'更新成功');
         } catch (Exception $e) {
             return show(0,$e->getMessage());
         }


     }
     /**
      * js ajax提交的删除数据的操作
      */
     public function setStatus(){
         $data = array(
             'id' => intval($_POST['id']),
             'status' => intval($_POST['status']),
         );
         return parent::setStatus($data,'Position');
     }
}
