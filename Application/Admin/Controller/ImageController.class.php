<?php
/**
 * 图片相关
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;
class ImageController extends CommonController {
    private $_uploadObj;
    public function __construct(){

    }
    // 针对上传图片按钮
    public function ajaxuploadimage(){
        $res = D('UploadImage') -> imageUpload();
        if ($res === false) {
            return show(0,'上传失败','');
        }
        return show(1,'上传成功',$res);
    }
    // 针对编辑器的图片上传
    public function kindupload(){
        $upload = D('UploadImage');
        $res = $upload -> upload();
        if($res === false){
            return showKind(1,'上传失败');
        }
        return showKind(0,$res);
    }
}
