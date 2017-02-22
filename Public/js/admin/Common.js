/**
 * 菜单的添加按钮操作
 */
$('#button-add').click(function(){
    var url = SCOPE.add_url;
    window.location.href = url;
});

/**
 * form表单提交的数据
 */
 $('#singcms-button-submit').click(function(){
     // 把表单中的数据全部存到一个对象中
     var data = $('#singcms-form').serializeArray();
     postData = {};
     $(data).each(function(i){
        postData[this.name] = this.value;
     });
     // 将获取到的数据post给服务器
     url = SCOPE.save_url;
     jumpUrl = SCOPE.jump_url;
     $.post(url,postData,function(result){
        if(result.status == 1){
            //成功
            return dialog.success(result.message,jumpUrl);
        }else if(result.status == 0){
            // 失败
            return dialog.error(result.message);
        }
    },'JSON');
 });

 /**
  * 后台菜单修改
  */
 $('.singcms-table #singcms-edit').on('click',function(){
     // 获取要修改的id
     var id = $(this).attr('attr-id');
     var url = SCOPE.edit_url+'&id='+id;
     window.location.href=url;
 });

/**
 * 删除操作js
 */
 $('.singcms-table #singcms-delete').on('click',function(){
     // 获取要修改的id值
     var id = $(this).attr('attr-id');
     // 获取要删除的类型,比如menu
     var a = $(this).attr('attr-a');
     var message = $(this).attr('attr-message');
     // 获取要跳转的url地址
     var url = SCOPE.set_status_url;

     data = {};
     data['id'] = id;
     data['status'] = -1;

     layer.open({
         type:0,
         title:'是否提交?',
         btn:['yes','no'],
         icon:3,
         closeBtn:2,
         content:"是否确定"+message,
         scrollbar:true,
         yes:function(){
            // 执行相关跳转
            todelete(url,data);
         },
     });

 });

 function todelete(url,data){
     $.post(
         url,
         data,
         function(s){
            if(s.status == 1){
                return dialog.success(s.message,'');
                // 跳转相关页面
            }else{
                return dialog.error(s.message);
            }
         }
     ,'JSON');
 }

 /**
  * 排序操作
  */
  $('#button-listorder').click(function(){
      // 获取 listorder内容
      var data = $('#singcms-listorder').serializeArray();
      postData = {};
      $(data).each(function(i){
          postData[this.name] = this.value;
      });
      var url = SCOPE.listorder_url;
      $.post(url,postData,function(result){
          if(result.status == 1){
              // 成功
              return dialog.success(result.message,result['data']['jump_url']);
          }else if(result.status == 0){
              // 失败
              return dialog.error(result.message,result['data']['jump_url']);
          }
      },'JSON');
  });
  /**
   * 修改状态
   */
   $('.singcms-table #singcms-on-off').on('click',function(){
       var id = $(this).attr('attr-id');
       var status = $(this).attr('attr-status');
       var url = SCOPE.set_status_url;

       data = {};
       data['id'] = id;
       data['status'] = status;

       layer.open({
           type:0,
           title:'是否提交?',
           btn:['yes','no'],
           icon:3,
           closeBtn:2,
           content:"是否确定更改状态",
           scrollbar:true,
           yes:function(){
               // 执行相关跳转
               todelete(url,data);
           },
       });
   });

   /**
    * 推送JS相关
    */
    $('#singcms_push').click(function(){
        // 获取要推送栏目的id
        var id = $('#select_push').val();
        if(id == 0){
            return dialog.error('请选择推荐位');
        }
        push = {};
        postData = {};
        $("input[name='pushcheck']:checked").each(function(i){
            push[i] = $(this).val();
        });
        postData['push'] = push;
        postData['position_id'] = id;
        var url = SCOPE.push_url;
        $.post(url,postData,function(result){
            if(result.status == 1){
                return dialog.success(result.message,result['data']['jumpUrl']);
            }
            if(result.status == 0){
                return dialog.error(result.message);
            }
        },'json');
    });
