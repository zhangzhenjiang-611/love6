<html>
   <head>
       <title>简单的图形计算器</title>
       <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
   </head>
<body>
 <center>
     <h1>简单的图形计算器</h1>
     <a href="index.php?action=juxing">矩形</a> ||
     <a href="index.php?action=triangle">三角形</a>

 </center>
 <hr>
     <?php
     error_reporting(E_ALL & ~ E_NOTICE);
     //设置自动加载所需类文件
     function __autoload($classname){
         include strtolower($classname).".class.php";
     }
     //判断用户是否选择点击一个图形链接
     if(!empty($_GET['action'])){
         //第一步 创建形状的对象
         $classname = ucfirst($_GET['action']); //首字母转大写
         $shape = new $classname($_POST);

         //第二步 调用形状的对象中的界面view()
         $shape->view();

         //第三步 判断是否提交对应图形界面的表单
         if(isset($_POST['dosubmit'])){
             //计算面积和周长
             if($shape->confirm($_POST)){
                 echo $shape->name."的周长是：".$shape->zhou()."<br>";
                 echo $shape->name."的面积是：".$shape->area()."<br>";
             }

         }

         //第四步 查看用户输入的数据是否正确->计算面积和周长

     }else{
         //默认访问主程序
         echo "请选择图形";
     }

     ?>
</body>
</html>