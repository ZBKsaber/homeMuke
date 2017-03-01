<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index($type=''){
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
        /**
         * 生成首页页面静态化
         */
         if ($type == 'buildHtml') {
             if (!getLoginUsername()) {
                 return $this -> error('您没有权限访问该页面');
             }
             return $this -> buildHtml('index',HTML_PATH,'Index/index');
         }else{
             $this->display();
         }
    }
    /**
     * 通过后台生成首页缓存
     */
    public function build_html(){
        $res = $this -> index('buildHtml');
        if ($res) {
            return show(1,'首页缓存生成成功');
        }
    }
    /**
     * 通过定时任务生成首页缓存
     */
     public function crontab_build_html(){
         if (!APP_CRONTAB != 1) {
             die('the_file_must_exec_crontab');
         }
         $result = D('Basic')->select();
         if (!$result['cacheindex']) {
             die('系统没有设置开启自动生成首页缓存');
         }
         $this -> index('buildHtml');
     }
}
