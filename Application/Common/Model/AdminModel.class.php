<?php
namespace Common\Model;
use Think\Model;
class AdminModel extends Model{

    private $_db = '';

    public function __construct(){
        $this -> _db = M('admin');
    }

    /**
     * 通过用户名获取管理员信息
     */
    public function getAdminByUsername($username){
        $ret = $this -> _db -> where('username="'.$username.'"') -> find();
        return $ret;
    }

    /**
     * 查询管理员列表信息
     * @return array;
     */
     public function select(){
         $where = array();
         $where['status'] = array('neq',-1);
         $field = 'admin_id,username,lastlogintime,status,realname';
         return $this -> _db -> where($where) -> field($field) ->order('admin_id desc') -> select();
     }
}
