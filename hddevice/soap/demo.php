<?php
include_once("ez_sql_core.php");
include_once("ez_sql_mysql.php");
include_once("ez_sql_mssql.php");
$sql = "select * from view_patient_registration where dept_code='0102010' and reg_type='".iconv("utf8","gbk",'普通')."' and is_jz=0 and withdraw_flag='0'";
$db1 = new ezSQL_mssql('sa', 'Founder123', 'chisdb_dev', '172.168.0.189');  
$list3 = $db1->get_results($sql);
$db1->debug();

?>