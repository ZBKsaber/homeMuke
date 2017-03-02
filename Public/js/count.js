/**
 * 计数器JS文件
 */
 var newsId = {};
 $('.news_conut').each(function(i){
     newsId[i] = $(this).attr('news-id');
 });
 // 调试
 // console.log(newsId);

 var url = '/index.php?c=index&a=getCount';
 $.post(url,newsId,function(result){
     if(result.status == 1){
         var counts = result.data;
        $.each(counts,function(news_id,count){
            $('.node-'+news_id).html(count);
        });
     }
 },'JSON');
