<?php

/**
 * 公用的函数
 */
 function show($status,$message,$data=array()){
     $result = array(
         'status' => $status,
         'message' => $message,
         'data' => $data,
     );
     exit(json_encode($result));
 }

 function getMd5Password($password){
     return md5($password.C('MD5_PRE'));
 }

 function getMenuType($type){
     return $type == 1 ? '后端菜单' : '前端导航';
 }

 function status($s){
     if($s == 0){
         $str = '关闭';
     }elseif($s == 1){
         $str = '正常';
     }elseif($s == -1){
         $str = '删除';
     }
     return $str;
 }
 function getAdminMenuUrl($nav){
     $url = '/admin.php?c='.$nav['c'].'&a='.$nav.['a'];
     if($nav['f'] == 'index'){
         $url = '/admin.php?c='.$nav['c'];
     }
     return $url;
 }

 function getActive($navc){
     $c = strtolower(CONTROLLER_NAME);
     if(strtolower($navc) == $c){
         return 'class="active"';
     }
     return '';
 }

 function showKind($status,$data){
     header('Content-type:application/json;charset=UTF-8');
     if($status == 0){
         exit(json_encode(array('error'=>0,'url'=>$data)));
     }
     exit(json_encode(array('error'=>1,'message'=>'上传失败')));
 }
// 获取栏目名称
 function getCatName($navs,$id){
     foreach ($navs as $nav) {
         $navList[$nav['menu_id']] = $nav['name'];
     }
     return isset($navList[$id]) ? $navList[$id] : '';
 }
// 获取来源名称
function getCopyFromById($id){
    $copyFrom = C('COPY_FROM');
    return $copyFrom[$id] ? $copyFrom[$id] : '';
}

// 查看是否有缩略图
function isThumb($thumb){
    if ($thumb) {
        return '<span style="color:green">有</span>';
    }
    return '无';
}

 // 获取当前登录管理员的函数
 function getLoginUsername(){
     return $_SESSION['admin_user']['username'] ? $_SESSION['admin_user']['username'] : '';
 }

 /**
  * 实例化分页类
  */
  function getPageStyle($count,$pageSize){
      $res = new \Think\Page($count,$pageSize);
      $res->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录 第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
      $res->setConfig('prev', '上一页');
      $res->setConfig('next', '下一页');
      $res->setConfig('last', '末页');
      $res->setConfig('first', '首页');
      $res->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
      $res->lastSuffix = false;//最后一页不显示为总页数
      $pageres = $res -> show();
      return $pageres;
  }
