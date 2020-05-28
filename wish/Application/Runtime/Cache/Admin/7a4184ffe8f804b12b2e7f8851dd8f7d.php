<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/Public/admin/Css/public.css" />
    <script src="/Public/admin/Js/jquery-1.7.2.min.js"></script>
</head>
<body>
<table class="table">
    <tr>
        <th>ID</th>
        <th>发布者</th>
        <th>内容</th>
        <th>发布时间</th>
        <th>操作</th>
    </tr>
    <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
        <td><?php echo ($v["id"]); ?></td>
        <td><?php echo ($v["username"]); ?></td>
        <td><?php echo (replace_phiz($v["content"])); ?></td>
        <td><?php echo (date('Y-m-d H:i:s',$v["time"])); ?></td>
        <td><a href="<?php echo u('delete',array('id'=>$v['id']));?>">删除</a></td>
        </tr><?php endforeach; endif; ?>
</table>
<div class="result page"><center><?php echo ($page); ?></center></div>
</body>
<script>

    var lastTime = new Date().getTime();
    var currentTime = new Date().getTime();
    var timeOut = 1 * 60 * 1000; //设置超时时间： 10分

    $(function(){

        /* 鼠标移动事件 */
        $(document).mouseover(function(){
            lastTime = new Date().getTime(); //更新操作时间

        });
    });

    function testTime(){
        currentTime = new Date().getTime(); //更新当前时间
        if(currentTime - lastTime > timeOut){ //判断是否超时
           $.ajax({
               url:<?php echo U('admin/Index/logout');?>

           })
        }
    }

    /* 定时器  间隔1秒检测是否长时间未操作页面  */
    window.setInterval(testTime, 1000);

</script>
</html>