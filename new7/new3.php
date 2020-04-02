<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/25
 * Time: 11:45
 */
//null运算符
//php5
 echo $id = isset($_GET['id']) ? $_GET['id'] : 0;

 //php7
echo "<br>";
echo $page = $_GET['page'] ?? 1;
