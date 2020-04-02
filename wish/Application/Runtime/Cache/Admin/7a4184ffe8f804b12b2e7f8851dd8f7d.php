<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/Public/admin/Css/public.css" />
    <script type="text/javascript" src="/Public/Js/jquery-1.7.2.min.js"></script>
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
</html>