<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function __construct(){
        header("Content-type: text/html; charset=utf-8");
        parent::__construct();
    }

    /**
     * @return获取排行的数据
     */
     public function getRank(){
         $conds['status'] = 1;
         $news = D('News') -> getRank($conds,10);
         return $news;
     }
     /**
      * 前端错误提示
      */
     public function error($message = ''){
         $message = $message ? $message : '系统错误';
         $this -> assign('message',$message);
         $this -> display('Index/error');
     }
}
