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
}
