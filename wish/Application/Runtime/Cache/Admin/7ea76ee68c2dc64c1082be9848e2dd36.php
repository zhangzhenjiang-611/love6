<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/Public/admin/Css/public.css" />
    <link rel="stylesheet" href="/Public/admin/Css/node.css" />
    <script type="text/javascript" src="/Public/Js/jquery-1.7.2.min.js"></script>
</head>
<body>
         <div id="wrap">
             <a href="<?php echo U('addNode');?>" class="add-app"> 添加应用</a>
             <?php if(is_array($node)): foreach($node as $key=>$app): ?><div class="app">
                     <p>
                         <strongq><?php echo ($app["title"]); ?></strongq>
                         【<a href="<?php echo U('addNode',array('pid'=>$app['id'],'level'=>2));?>">添加控制器</a>】
                         【<a href="">修改</a>】
                         【<a href="">删除</a>】
                     </p>
                     <?php if(is_array($app["child"])): foreach($app["child"] as $key=>$action): ?><dl>
                             <dt>
                                 <strong><?php echo ($action["title"]); ?></strong>
                                【 <a href="<?php echo U('addNode',array('pid'=>$action['id'],'level'=>3));?>">添加方法</a>】
                             </dt>
                             <?php if(is_array($action["child"])): foreach($action["child"] as $key=>$method): ?><dd>
                                     <span><?php echo ($method["title"]); ?></span>
                                    【<a href="">修改</a>】
                                    【<a href="">删除</a>】
                                 </dd><?php endforeach; endif; ?>
                         </dl><?php endforeach; endif; ?>
                 </div><?php endforeach; endif; ?>
         </div>

</body>
</html>