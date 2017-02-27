<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        // 获取排行
        $rankNews = $this -> getRank();
        try {
            // 获取首页大图数据
            $topPicNews = D('PositionContent')->select(array('status'=>1,'position_id'=>1),1);
            //首页三小图
            $topSmallNews = D('PositionContent')->select(array('status'=>1,'position_id'=>3),3);
            // 获取广告位
            $advNews = D('PositionContent')->select(array('status'=>1,'position_id'=>5),2);
            // 获取首页列表文章
            $listNews = D('News')->select(array('status'=>1,'thumb'=>array('neq','')),30);

        } catch (Exception $e) {
            return show(0,$e->getMessage());
        }
        $this -> assign('result',array(
            'topPicNews' => $topPicNews,
            'topSmallNews' => $topSmallNews,
            'listNews' => $listNews,
            'advNews' => $advNews,
            'rankNews' => $rankNews,
            'catId' => 0,
        ));
        $this->display();
    }
}
