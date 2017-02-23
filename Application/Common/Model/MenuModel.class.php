<?php

namespace Common\Model;
use Think\Model;

class MenuModel extends Model{
    private $_db = '';
    public function __construct(){
        $this -> _db = M('menu');
    }

    // 插入数据
    public function insert($data = array()){
        if(!$data || !is_array($data)){
            return 0;
        }
        return $this -> _db -> add($data);
    }

    /**
     * 获取相应条数的分页数据
     * @param $data  数据
     * @param $page  当前页数
     * @param $pageSize  每页显示条数
     */
     public function getMenus($data,$page,$pageSize=10){
        $data['status'] = array('neq','-1');
         // 获取每页的偏移值
        $offset = ($page - 1) * $pageSize;
        // 获取当前页的数据
        $list = $this -> _db ->where($data)
            -> field('menu_id,name,m,listorder,status')
            -> order('listorder desc,menu_id desc')
            -> limit($offset,$pageSize) -> select();
        return $list;
     }

     // 获取相应条数的总数
     public function getMenusCount($data=array()){
        $data['status'] = array('neq','-1');
        return $this -> _db -> where($data) -> count('menu_id');
     }

     public function find($id){
         if(!$id || !is_numeric($id)){
             return array();
         }
         return $this -> _db -> where('menu_id='.$id)->find();
     }

     public function updateMenuById($id,$data){
         if(!$id || !is_numeric($id)){
             throw_exception('ID不合法');
         }
         if(!$data || !is_array($data)){
             throw_exception('更新的数据不合法');
         }
         return $this -> _db -> where('menu_id='.$id) -> save($data);
     }

     public function updateStatusById($id,$status){
         if(!is_numeric($id) || !$id){
             throw_exception('ID不合法');
         }
         if(!is_numeric($status) || !$status){
             throw_exception('状态不合法');
         }

         $data['status'] = $status;
         return $this->_db->where('menu_id='.$id)->save($data);
     }

     public function updateMenuListorderById($id,$listorder){
         if(!$id || !is_numeric($id)){
             throw_exception('ID不合法');
         }

         $data = array(
             'listorder' => intval($listorder),
         );
         return $this -> _db -> where('menu_id='.$id)->save($data);
     }
     // 获取后台导航
     public function getAdminMenus(){
         $data = array(
             'status' => array('neq',-1),
             'type' => 1,
         );
         return $this -> _db ->where($data)
            -> field('name,m,c,f')
            -> order('listorder desc,menu_id desc') -> select();
     }

     // 获取前端导航
     public function getBarMenus(){
         $data = array(
             'status'=>array('neq',-1),
             'type'=>0,
         );
         $res = $this -> _db -> where($data)
            ->field('menu_id,name')
            ->order('listorder desc,menu_id desc')
            ->select();
        return $res;
     }
}
