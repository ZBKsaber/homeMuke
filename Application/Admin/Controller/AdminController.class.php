<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

/**
 * basic基本配置
 */
class AdminController extends CommonController{

    public function index(){
        // 获取管理员列表
        $list = D('Admin') -> select();
        $this -> assign('list',$list);
        $this -> display();
    }

    /**
     * 添加管理员
     */
     public function add(){
         if ($_POST) {
             $post = $_POST;
             if (!$post['username'] || !isset($post['username'])) {
                 return show(0,'用户名不能为空');
             }
             // 查询用户名是否已经存在
             $res = D('Admin') -> getAdminByUsername($post['username']);
             if ($res) {
                return show(0,'用户名已经存在');
             }
             if (!$post['password'] || !isset($post['password'])) {
                 return show(0,'密码不能为空');
             }
             if (!$post['realname'] || !isset($post['realname'])) {
                 return show(0,'真实姓名不能为空');
             }
             // 给用户密码加密
             $password = md5($post['password'].C('MD5_PRE'));
             $post['password'] = $password;
             // 把用户数据插入数据库
             $data = M('Admin') -> create($post);
             $res = M('Admin') -> add();
             if ($res !== false) {
                 return show(1,'添加成功');
             }
             return show(0,'添加失败');
         }else{
             $this -> display();
         }
     }

    /**
     * 管理员个人中心
     */
     public function personal(){
         // 根据用户名获取管理员信息
         $info = D('Admin') -> getAdminByUsername($_SESSION['admin_user']['username']);
         $this -> assign('info',$info);
         $this -> display();
     }
}
