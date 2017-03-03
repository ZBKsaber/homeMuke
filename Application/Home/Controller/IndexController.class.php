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

     public function getCount(){
         if (!$_POST) {
             return show(0,'没有任何内容');
         }
         $newsIds = array_unique($_POST);
         try {
             $list = D('News') -> getNewsByNewsIdIn($newsIds);
         } catch (Exception $e) {
             return show(0,$e->getMessage());
         }
         if (!$list) {
             return show(0,'notdatas');
         }
         $data = array();
         foreach ($list as $k => $v) {
             $data[$v['news_id']] = $v['count'];
         }
         return show(1,'success',$data);

     }
     /**
      * 页面拔取
      */
      public function curl(){
        //  // 1. 初始化一个cURL会话
        // $ch = curl_init();
        //
        // // 2. 设置请求选项, 包括具体的url
        // curl_setopt($ch, CURLOPT_URL, "https://news.cnblogs.com/n/564088/");
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_HEADER, 0);
        //
        // // 3. 执行一个cURL会话并且获取相关回复
        // $response = curl_exec($ch);
        // // 获取标题
        // // $regex1 = '/<title>(.*?)<\/title>.*?_blank"><img src="(.*?)"><\/a>.*?<\/p><\/div><div>(.*?)<div id="click_div">/ism';
        // // $res = preg_match_all($regex1, $response, $matches);
        //
        // // 4. 释放cURL句柄,关闭一个cURL会话
        // curl_close($ch);
        // var_dump($response);
        $info = array();
        $info['catid'] = 3;
        $info['keywords'] = "暂无";
        $info['description'] = "暂无";
        $info['status'] = 1;#5674ed
        $info['title_font_color'] = '#5674ed';
        $info['copyfrom'] = 0;
        $info['username'] = 'admin';
        for ($i=563033; $i > 563000; $i--) {
            $curl = curl_init();
            $url = 'https://news.cnblogs.com/n/'.$i;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
            $data = curl_exec($curl);
            //检查是否404（网页找不到）
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($httpCode == 404) {
                continue;
            }
            // 获取抓取的内容
            $regex1 = '/<title>(.*?)<\/title>.*?<div id="news_body">(.*?)<\/div><!--end: news_body -->/ism';
            $res = preg_match_all($regex1, $data, $matches);
            // var_dump($matches);exit;
            if (!$res) {
                continue;
            }
            // 匹配出首张图作为缩略图
            $regex2 = '/<img src="(.*?)" alt="/ism';
            $thumb = preg_match_all($regex2,$matches['2']['0'],$thumb2);
            // var_dump($thumb2);exit;
            $info['thumb'] = 'https:'.$thumb2['1']['0'];
            // var_dump($info['thumb']);exit;
            $info['title'] = $matches['1']['0'];
            $info['small_title'] = $i;
            $result = D('News') -> insert($info);
            $infoc['news_id'] = $result;
            $infoc['content'] = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $matches['2']['0']); ;
            $jieguo = D('NewsContent')->insert($infoc);
            if ($jieguo) {
                echo '完成'.$i.'<br>';
            }
            // var_dump($result);
            // var_dump($matches);
        }
        curl_close($curl);
      }
}
