<?php
namespace Home\Controller;
use Think\Controller;
class CatController extends CommonController {
    public function index(){
        $id = intval($_GET['id']);
        if (!$id) {
            return $this -> error('ID不存在');
        }
        $nav = D('Menu') -> find($id);
        if (!$nav || $nav['status'] != 1) {
            return $this -> error('栏目ID不存在');
        }
        // 获取广告位
        $advNews = D('PositionContent')->select(array('status'=>1,'position_id'=>5),2);
        // 获取排行
        $rankNews = $this -> getRank();
        // 分页处理
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = 5;
        $conds = array(
            'thumb'  => array('neq',''),
            'catid' => $id,
        );
        $conds['status'] = array('neq',-1);
        $news = D('News') -> getNews($conds,$page,$pageSize);
        $count = D('News') -> getNewsCount($conds);

        // 调用自定义分页函数
        $pageres = getPageStyle($count,$pageSize);

        // $res = new \Think\Page($count,$pageSize);
        // $pageres = $res -> show();

        $this -> assign('result',array(
            'advNews' => $advNews,
            'rankNews' => $rankNews,
            'catId' => $id,
            'listNews' => $news,
            'pageres' => $pageres,
        ));
        // var_dump($news);exit;
        $this -> display();
    }
}
