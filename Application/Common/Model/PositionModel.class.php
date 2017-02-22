<?php

namespace Common\Model;
use Think\Model;

class PositionModel extends Model{
    private $_db = '';
    public function __construct(){
        $this -> _db = M('Position');
    }

    /**
     * 获取状态为正常的推荐位栏目
     */
     public function getNormalPositions(){
         $conditions = array('status'=>1);
         $list = $this -> _db -> where($conditions) -> field('id,name') -> order('id desc') -> select();
         return $list;
     }
}
