<?php
/**
 * 后台推荐位内容管理相关
 */
namespace Admin\Controller;
use Think\Controller;
class PositionContentController extends CommonController {

    public function index(){
        $conds = array();
        $title = trim($_GET['title']);
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
        $positions = D('Position')->getNormalPositions();
        foreach ($positionCS as &$v) {
            foreach ($positions as $k) {
                if ($v['position_id'] == $k['id']) {
                    $v['position_name'] = $k['name'];
                }
            }
        }
        $this -> assign('positions',$positions);
        $this -> assign('positionCS',$positionCS);
        $this -> assign('pageres',$pageres);
    	$this->display();
    }

    public function add(){
        if ($_POST) {
            if (!isset($_POST['position_id']) || !$_POST['position_id']) {
                return show(0,'推荐位ID不能为空');
            }
            if (!isset($_POST['title']) || !$_POST['title']) {
                return show(0,'推荐位标题不能为空');
            }
            if (!$_POST['url'] && !$_POST['news_id']) {
                return show(0,'url和文章id不能同时为空');
            }
            if (!isset($_POST['thumb']) || !$_POST['thumb']) {
                if ($_POST['news_id']) {
                    $res = D('News')->find($_POST['news_id']);
                    if ($res && is_array($res)) {
                        $_POST['thumb'] = $res['thumb'];
                    }else{
                        return show(0,'文章id不存在');
                    }
                }else{
                    return show(0,'图片不能为空');
                }
            }
            if ($_POST['id']) {
                return $this -> save($_POST);
            }
            try {
                $id = D('PositionContent')->insert($_POST);
                if ($id) {
                    return show(1,'添加成功');
                }
                return show(0,'添加失败');
            } catch (Exception $e) {
                return show(0,$e->getMessage());
            }

        }else{
            $positions = D('Position')->getNormalPositions();
            $this -> assign('positions',$positions);
            $this -> display();
        }
    }
    public function edit(){
        $id = $_GET['id'];
        $position = D('PositionContent')->find($id);
        $positions = D('Position')->getNormalPositions();
        $this -> assign('vo',$position);
        $this -> assign('positions',$positions);
        $this -> display();
    }
    public function save($data){
        $id = $data['id'];
        unset($data['id']);
        try {
            $resId = D('PositionContent')->updateById($id,$data);
            if($resId == false){
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        } catch (Exception $e) {
            return show(0,$e->getMessage());
        }
    }
    public function setStatus(){
        $data = array(
            'id' => intval($_POST['id']),
            'status' => intval($_POST['status']),
        );
        return parent::setStatus($data,'PositionContent');
    }
    public function listorder(){
        return parent::listorder('PositionContent');
    }
}
