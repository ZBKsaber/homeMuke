<?php

namespace Common\Model;
use Think\Model;

class NewsModel extends Model{
    private $_db = '';
    public function __construct(){
        $this -> _db = M('news');
    }
    // 插入数据
    public function insert($data = array()){
        if(!is_array($data) || !$data){
            return 0;
        }
        $data['create_time'] = time();
        $data['username'] = getLoginUsername();
        return $this -> _db -> add($data);
    }
    // 根据查询条件,查询指定的文章
    public function getNews($data,$page,$pageSize=10){
        $conditions = $data;
        if(isset($data['title']) && $data['title']){
            $conditions['title'] = array('like','%'.$data['title'].'%');
        }
        if(isset($data['catid']) && $data['catid']){
            $conditions['catid'] = intval($data['catid']);
        }
        $offset = ($page - 1)*$pageSize;
        $list = $this -> _db -> where($conditions)
            ->order('listorder desc,news_id desc')
            ->limit($offset,$pageSize)->select();
        return $list;
    }
    // 获取新闻的总条数
    public function getNewsCount($data = array()){
        $conditions = $data;
        if(isset($data['title']) && $data['title']){
            $conditions['title'] = array('like','%'.$data['title'].'%');
        }
        if(isset($data['catid']) && $data['catid']){
            $conditions['catid'] = intval($data['catid']);
        }
        return $this -> _db -> where($conditions) -> count();
    }
    // 根据id获取文章的内容
    public function find($id){
        if(!$id || !is_numeric($id)){
            return array();
        }
        $data = $this -> _db -> where('news_id='.$id)->find();
        return $data;
    }
    // 通过id值更新文章
    public function updateById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新数据不合法');
        }
        return $this -> _db -> where('news_id='.$id) -> save($data);
    }
    // 通过id更新文章的状态
    public function updateStatusById($id,$status){
        if(!is_numeric($status)){
            throw_exception('status不能为非数字');
        }
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data['status'] = $status;
        return $this -> _db -> where('news_id='.$id) -> save($data);
    }
    // 更新文章的排序
    public function updateNewsListorderById($id,$listorder){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data = array('listorder'=>intval($listorder));
        return $this -> _db -> where('news_id='.$id) -> save($data);
    }
    // 通过id数组集合,查询文章
    public function getNewsByNewsIdIn($newsIds){
        if(!is_array($newsIds)){
            throw_exception('参数不合法');
        }
        $data = array(
            'news_id' => array('in',implode(',',$newsIds)),
        );
        return $this -> _db -> where($data) -> select();
    }
}
