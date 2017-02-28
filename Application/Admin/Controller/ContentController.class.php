<?php
/**
 * 后台文章管理相关
 */
namespace Admin\Controller;
use Think\Controller;
class ContentController extends CommonController {

    public function index(){
        $conds = array();
        $title = $_GET['title'];
        if($title){
            $conds['title'] = $title;
            $this -> assign('title',$title);
        }
        if ($_GET['catid']) {
            $conds['catid'] = intval($_GET['catid']);
            $this -> assign('catid',$conds['catid']);
        }
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = 10;
        $conds['status'] = array('neq',-1);
        $news = D('News') -> getNews($conds,$page,$pageSize);
        $count = D('News') -> getNewsCount($conds);

        // 调用自定义分页函数
        $pageres = getPageStyle($count,$pageSize);
        // 获取推荐位栏目
        $positions = D('Position') -> getNormalPositions();
        $this -> assign('news',$news);
        $this -> assign('pageres',$pageres);
        $this -> assign('positions',$positions);
        // 获取所有的前端导航
        $this -> assign('webSiteMenu',D('Menu')->getBarMenus());
    	$this->display();
    }

    public function add() {
        if ($_POST) {
            if(!isset($_POST['title']) || !$_POST['title']){
                return show(0,'标题不存在');
            }
            if(!isset($_POST['small_title']) || !$_POST['small_title']){
                return show(0,'短标题不存在');
            }
            if(!isset($_POST['catid']) || !$_POST['catid']){
                return show(0,'栏目不存在');
            }
            if(!isset($_POST['keywords']) || !$_POST['keywords']){
                return show(0,'关键字不存在');
            }
            if(!isset($_POST['content']) || !$_POST['content']){
                return show(0,'内容不存在');
            }
            // 判断是否是修改文章操作
            if ($_POST['news_id']) {
                return $this -> save($_POST);
            }
            $newsId = D('News')->insert($_POST);
            if($newsId){
                $newsContentData['content'] = $_POST['content'];
                $newsContentData['news_id'] = $newsId;
                $cId = D('NewsContent') -> insert($newsContentData);
                if($cId){
                    return show(1,'添加成功');
                }
                return show(1,'主表添加成功,附表添加失败');
            }else{
                return show(0,'添加失败');
            }
        } else {
            // 导航菜单
            $webSiteMenu = D('Menu') -> getBarMenus();
            // 获取颜色
            $titleFontColor = C('TITLE_FONT_COLOR');
            // 获取来源
            $copyFrom = C('COPY_FROM');

            $this -> assign('webSiteMenu',$webSiteMenu);
            $this -> assign('titleFontColor',$titleFontColor);
            $this -> assign('copyFrom',$copyFrom);
            $this -> display();
        }

    }

    public function edit(){
        $newsId = $_GET['id'];
        if (!$newsId) {
            $this -> redirect('/admin.php?c=content');
        }
        $news = D('News')->find($newsId);
        if(!$news){
            $this -> redirect('/admin.php?c=content');
        }
        $newsContent = D('NewsContent') -> find($newsId);
        if($newsContent){
            $news['content'] = $newsContent['content'];
        }
        // 获取栏目
        $webSiteMenu = D('Menu') -> getBarMenus();

        $this -> assign('webSiteMenu',$webSiteMenu);
        $this -> assign('titleFontColor',C('TITLE_FONT_COLOR'));
        $this -> assign('copyFrom',C('COPY_FROM'));

        $this -> assign('news',$news);
        $this -> display();
    }

    public function save($data){
        $newsId = $data['news_id'];
        unset($data['news_id']);

        try {
            $id = D('News') -> updateById($newsId,$data);
            $newsContentData['content'] = $data['content'];
            $condId = D('NewsContent') -> updateNewsById($newsId,$newsContentData);
            if ($id === false || $condId === false) {
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        } catch (Exception $e) {
            return show(0,$e->getMessage());
        }

    }
    // 修改文章的状态
    public function setStatus(){
        try {
            if($_POST){
                $id = $_POST['id'];
                $status = $_POST['status'];
                if(!$id){
                    return show(0,'ID不存在');
                }
                $res = D('News') -> updateStatusById($id,$status);
                if ($res) {
                    return show(1,'操作成功');
                }else{
                    return show(0,'操作失败');
                }
            }
            return show(0,'没有提交内容');
        } catch (Exception $e) {
            return show(0,$e->getMessage());
        }
    }
    // 文章的排序
    public function listorder(){
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        try {
            if($listorder){
                foreach ($listorder as $newsId => $v) {
                    // 执行更新操作
                    $id = D('News') -> updateNewsListorderById($newsId,$v);;
                    if($id === false){
                        $errors[] = $newsId;
                    }
                }
                if($errors){
                    return show(0,'排序失败-'.implode(',',$errors),array('jump_url'=>$jumpUrl));
                }
                return show(1,'排序成功',array('jump_url'=>$jumpUrl));
            }
        } catch (Exception $e) {
            return show(0,$e->getMessage());
        }
        return show(0,'排序数据不存在',array('jump_url'=>$jumpUrl));

    }

    // 文章推荐位推送
    public function push(){
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $positionId = intval($_POST['position_id']);
        $newsId = $_POST['push'];
        if (!$newsId || !is_array($newsId)) {
            return show(0,'请选择推荐的文章');
        }
        if (!$positionId) {
            return show(0,'请选择推荐位');
        }
        try {
            $news = D('News')->getNewsByNewsIdIn($newsId);
            if(!$news){
                return show(0,'没有相关内容');
            }
            foreach ($news as $new) {
                $data = array(
                    'position_id' => $positionId,
                    'title' => $new['title'],
                    'thumb' => $new['thumb'],
                    'news_id' => $new['news_id'],
                    'status' => 1,
                    'create_time' => $new['create_time'],
                );
                $pisition = D('PositionContent')->insert($data);
            }
        } catch (Exception $e) {
            return show(0,$e->getMessage());
        }
        return show(1,'推荐成功',array('jumpUrl'=>$jumpUrl));
    }
}
