<?php

namespace Common\Model;
use Think\Model;

class PositionContentModel extends Model{
    private $_db = '';
    public function __construct(){
        $this -> _db = M('position_content');
    }

    /**
     * 插入数据
     */
     public function insert($data = array()){
         if(!is_array($data) || !$data){
             return 0;
         }
         return $this -> _db -> add($data);
     }
     /**
      * 根据id获取内容
      */
      public function find($id){
          $data = $this -> _db -> where('id='.$id)->find();
          return $data;
      }
     /**
      * 根据指定条件获取推荐位内容
      */
     public function getPositionC($data,$page,$pageSize){
         $conditions = $data;
         if (isset($data['title']) && $data['title']) {
             $conditions['title'] = array('like','%'.$data['title'].'%');
         }
         if (isset($data['position_id']) && $data['position_id']) {
             $conditions['position_id'] = intval($data['position_id']);
         }
         // 设置分页的偏移值
         $offset = ($page - 1) * $pageSize;

         $list = $this -> _db -> where($conditions)
            ->field('id,title,thumb,create_time,status,position_id,listorder')
            ->order('listorder desc,id desc')
            ->limit($offset,$pageSize)->select();
            return $list;
     }

     public function getpositonCC($data=array()){
         $conditions = $data;
         if (isset($data['title']) && $data['title']) {
             $conditions['title'] = array('like','%'.$data['title'].'%');
         }
         if (isset($data['position_id']) && $data['position_id']) {
             $conditions['position_id'] = intval($data['position_id']);
         }
          return $this -> _db -> where($conditions) -> count('id');
     }
     public function updateById($id,$data){
         if(!$id || !is_numeric($id)){
             throw_exception('ID不合法');
         }
         if(!$data || !is_array($data)){
             throw_exception('更新数据不合法');
         }
         return $this -> _db -> where('id='.$id)->save($data);
     }

     public function updateStatusById($id,$status){
         if(!is_numeric($status)){
             throw_exception('status不能为非数字');
         }
         if(!$id || !is_numeric($id)){
             throw_exception('ID不合法');
         }
         $data['status'] = $status;
         return $this -> _db -> where('id='.$id) -> save($data);
     }
     public function updateListorderById($id,$listorder){
         if (!$id || !is_numeric($id)) {
             throw_exception('ID不合法');
         }
         $data = array('listorder'=>intval($listorder));
         return $this -> _db -> where('id='.$id)->save($data);
     }
}
