<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TP后台管理平台</title>
    <!-- Bootstrap Core CSS -->
    <link href="Public/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="Public/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="Public/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="Public/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="Public/css/sing/common.css" />
    <link rel="stylesheet" href="Public/css/party/bootstrap-switch.css" />
    <link rel="stylesheet" type="text/css" href="Public/css/party/uploadify.css">
    <!-- 引入自定义分页样式css -->
    <link rel="stylesheet" type="text/css" href="Public/css/pageStyle.css">

    <!-- jQuery -->
    <script src="/Public/js/jquery.js"></script>
    <script src="/Public/js/bootstrap.min.js"></script>
    <script src="/Public/js/dialog/layer.js"></script>
    <script src="/Public/js/dialog.js"></script>
    <script type="text/javascript" src="/Public/js/party/jquery.uploadify.js"></script>
</head>

<body>
<div id="wrapper">
    <?php
 $navs = D('Menu')->getAdminMenus(); $index = 'index'; ?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <a class="navbar-brand" >ZBK内容管理平台</a>
    </div>
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-user"></i> <?php echo $_SESSION['admin_user']['username'];?><b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li>
                  <a href="/admin.php?c=admin&a=personal"><i class="fa fa-fw fa-user"></i> 个人中心</a>
                </li>
                <li class="divider"></li>
                <li>
                  <a href="/admin.php?m=admin&c=login&a=loginout"><i class="fa fa-fw fa-power-off"></i> 退出</a>
                </li>
            </ul>
        </li>
    </ul>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav nav_list">
            <li <?php echo (getActive($index)); ?>>
                <a href="admin.php"><i class="fa fa-fw fa-dashboard"></i> 首页</a>
            </li>
            <?php if(is_array($navs)): $i = 0; $__LIST__ = $navs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?><li <?php echo (getActive($nav["c"])); ?>>
                    <a href="<?php echo (getAdminMenuUrl($nav)); ?>"><i class="fa fa-fw fa-bar-chart-o"></i><?php echo ($nav["name"]); ?></a>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
</nav>

    <div id="page-wrapper">
        <div class="container-fluid" >
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li>
                          <i class="fa fa-dashboard"></i>  <a href="/admin.php?c=positionContent">推荐位内容管理</a>
                        </li>
                        <li class="active">
                            <i class="fa fa-table"></i>推荐位内容列表
                        </li>
                    </ol>
                </div>
            </div>
            <div style="margin-bottom:15px;">
                <button  id="button-add" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加
                </button>
            </div>
            <div class="row">
                <form action="/admin.php" method="get">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon">推荐位</span>
                            <select class="form-control" name="position_id">
                                <option value="">请选择推荐位</option>
                                <?php if(is_array($positions)): foreach($positions as $key=>$position): ?><option value="<?php echo ($position["id"]); ?>" <?php if($position["id"] == $position_id): ?>selected="selected"<?php endif; ?>><?php echo ($position["name"]); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="c" value="<?php echo (CONTROLLER_NAME); ?>"/>
                    <input type="hidden" name="a" value="<?php echo (ACTION_NAME); ?>"/>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input class="form-control" name="title" type="text" value="<?php echo ($title); ?>" placeholder="文章标题" />
                    <span class="input-group-btn">
                      <button id="sub_data" type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>
                    </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <h3></h3>
                    <div class="table-responsive">
                        <form id="singcms-listorder">
                            <table class="table table-bordered table-hover singcms-table">
                                <thead>
                                    <tr>
                                        <th width="14">排序</th><!--7-->
                                        <th>id</th>
                                        <th>标题</th>
                                        <th>时间</th>
                                        <th>封面图</th>
                                        <th>所属推荐位</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(is_array($positionCS)): foreach($positionCS as $key=>$positionC): ?><tr>
                                            <td><input size=4 type='text' name="listorder[<?php echo ($positionC["id"]); ?>]" value="<?php echo ($positionC["listorder"]); ?>"/></td>
                                            <td><?php echo ($positionC["id"]); ?></td>
                                            <td><?php echo ($positionC["title"]); ?></td>
                                            <td><?php echo (date("Y-m-d H:i:s",$positionC["create_time"])); ?></td>
                                            <td><?php echo (isThumb($positionC["thumb"])); ?></td>
                                            <td><?php echo ($positionC["position_name"]); ?></td>
                                            <td>
                                                <span  attr-status="<?php if($positionC['status'] == 0): ?>1<?php else: ?>0<?php endif; ?>"  attr-id="<?php echo ($positionC["id"]); ?>" class="sing_cursor singcms-on-off" id="singcms-on-off" ><?php echo (Status($positionC["status"])); ?></span>
                                            </td>
                                            <td>
                                                <span class="sing_cursor glyphicon glyphicon-edit" aria-hidden="true" id="singcms-edit" attr-id="<?php echo ($positionC["id"]); ?>" ></span>
                                                <a href="javascript:void(0)" id="singcms-delete"  attr-id="<?php echo ($positionC["id"]); ?>"  attr-message="删除">
                                                    <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                                </a>
                                            </td>
                                        </tr><?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </form>
                        <div>
                            <button id="button-listorder" type="button" class="btn btn-primary dropdown-toggle" ><span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>更新排序</button>
                        </div>
                        <nav>
                           <ul class="pagination">
                               <?php echo ($pageres); ?>
                           </ul>
                       </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    var SCOPE = {
        'edit_url' : '/admin.php?c=positionContent&a=edit',
        'set_status_url' : '/admin.php?c=positionContent&a=setStatus',
        'add_url' : '/admin.php?c=positionContent&a=add',
        'listorder_url' : '/admin.php?c=positionContent&a=listorder',
    }

</script>
        <script src="/Public/js/admin/common.js"></script>
    </body>
</html>