<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        try {
            // 获取首页大图数据
            $topPicNews = D('PositionContent')->select(array('status'=>1,'position_id'=>1),1);
            //首页三小图
            $topSmallNews = D('PositionContent')->select(array('status'=>1,'position_id'=>3),3);
        } catch (Exception $e) {
            return show(0,$e->getMessage());
        }
        $this -> assign('result',array(
            'topPicNews' => $topPicNews,
            'topSmallNews' => $topSmallNews,
        ));
        $this->display();
    }
}
