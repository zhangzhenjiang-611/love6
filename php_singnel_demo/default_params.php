<?php
//默认参数的函数
//error_reporting(E_ALL);
function demo($name='yanli',$age=18,$sex='girl',$email='156@qq.com'){
    echo "{$name}--------{$age}---------{$sex}-------{$email} <br/>";
    //echo $name;
}

demo("meizi",18,'girl','123@qq.com','11'); //实参个数多于形参，不会报错，少于会报错

demo(); //在形参里加默认值，防止报错
demo('xiaojiang');
demo('xiaojiang',19);
demo('xiaojiang',19,'boy');
demo('xiaojiang',19,'boy','520@qq.com');


function demo1($name,$age,$sex='girl',$email='156@qq.com'){
    echo "{$name}--------{$age}---------{$sex}-------{$email} <br/>";
    //echo $name;
}
//可以 参数中一部分给默认值，但是没有默认值的形参必须放在前面
demo1('liuyanli',22);
demo1('liuyanli',22,'girls');
demo1('liuyanli',22,'girls','521@qq.com');
//demo1('liuyanli');  //fatal error



//可变参数个数的函数
function demo2(){
    echo "###############<br>";
    $arr = func_get_args();  //获取外部所有的参数 返回数组
    var_dump($arr);
    $num = func_num_args(); //获取外部所有的参数的个数 返回int
    var_dump($num);

    $zhiding = func_get_arg(1);   //获取外部指定的参数
    var_dump($zhiding);
}
//demo2(1);
demo2(1,2,3); //不会报错