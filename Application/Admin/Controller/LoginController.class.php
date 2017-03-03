<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * use Common\Model 这块可以不需要使用，框架默认会加载里面的内容
 */
class LoginController extends Controller {

    public function index(){
        if(session('admin_user')){
            return $this -> redirect('index/index');
        }
        return $this->display();
    }
    /**
     * 检测登录用户的信息
     */
    public function check(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if(!trim($username)){
            return show(0,'用户名不能为空');
        }
        if(!trim($password)){
            return show(0,'密码不能为空');
        }
        $ret = D('Admin') -> getAdminByUsername($username);
        if (!$ret) {
            return show(0,'该用户不存在');
        }
        if($ret['password'] != getMd5Password($password)){
            return show(0,'密码错误');
        }
        // 获取用户id,更新用户的最后登陆时间
        $admin_id = $ret['admin_id'];
        try {
            D('Admin') -> updateLoginTime($admin_id);
        } catch (Exception $e) {
            return show(0,'ID合法');
        }
        session('admin_user',$ret);
        return show(1,'登录成功');
    }

    public function loginout(){
        session('admin_user',null);
        $this -> redirect('/admin.php?c=login');
    }

}
