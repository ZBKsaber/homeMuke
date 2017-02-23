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
            ->order('listorder desc,id desc')
            ->limit($offset,$pageSize)->select();
            return $list;
     }
}
