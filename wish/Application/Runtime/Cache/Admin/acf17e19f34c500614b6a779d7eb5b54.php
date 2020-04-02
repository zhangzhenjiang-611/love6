<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/Public/admin/Css/public.css" />
</head>
<body>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>用户名称</th>
            <th>上一次登录时间</th>
            <th>上一次登录IP</th>
            <th>锁定状态</th>
            <th>用户所属组</th>
            <th>操作</th>
        </tr>
        <?php if(is_array($user)): foreach($user as $key=>$v): ?><tr>
                 <td><?php echo ($v["id"]); ?></td>
                 <td><?php echo ($v["username"]); ?></td>
                 <td><?php echo (date('Y-m-d H:i:s',$v["logintime"])); ?></td>
                 <td><?php echo ($v["loginip"]); ?></td>
                 <td>
                     <?php if($v['lock']): ?>锁定
                         <?php else: ?>
                        不锁定<?php endif; ?>
                 </td>
                 <td>
                     <?php if($v['username'] == C('RBAC_SUPERADMIN')): ?>超级管理员
                         <?php else: ?>
                         <ul>
                             <?php if(is_array($v["role"])): foreach($v["role"] as $key=>$val): ?><li><?php echo ($val["name"]); ?>(<?php echo ($val["remark"]); ?>)</li><?php endforeach; endif; ?>
                         </ul><?php endif; ?>
                 </td>
                     <td><a href="">锁定</a></td>
             </tr><?php endforeach; endif; ?>


    </table>

</body>
</html>