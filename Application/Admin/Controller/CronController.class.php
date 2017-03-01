<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

/**
 * basic基本配置
 */
class CronController{

    public function dumpmysql(){
        $shell = "mysqldump -u".C("DB_USER")." ".C("DB_NAME").">E:/sql/cms".date("Ymd")."sql";
        // echo $shell;
        $m = M();
        $m -> execute($shell);
    }
}
