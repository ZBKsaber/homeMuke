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
     /**
      * 更改用户的登陆时间
      */
      public function updateLoginTime($id){
          if (!$id || !is_numeric($id)) {
              throw_exception('ID不合法');
          }
          $data = array();
          $data['lastlogintime'] = time();
          $res = $this -> _db -> where('admin_id='.$id) -> field('lastlogintime') -> save($data);
          return $res;
      }
      /**
       * 获取当日更新的用户数量
       */
       public function getLoginNum(){
           $initTime = mktime(0,0,0,date('m'),date('d'),date('Y'));
           $where = array();
           $where['lastlogintime'] = array('gt',$initTime);
           $res = $this -> _db -> where($where)->field('admin_id')->count('admin_id');
           return $res;
       }
}
