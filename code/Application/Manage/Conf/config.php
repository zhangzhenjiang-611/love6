<?php
return array(
    //RBAC认证配置信息
    'RBAC_SUPERADMIN' => 'admin',//超级管理员名称，对应用户表中某一用户名 username
    'ADMIN_AUTH_KEY' => 'superadmin',//超级管理员识别

    'USER_AUTH_ON' => true, //是否需要认证
    'USER_AUTH_TYPE' => 1, //认证类型 1为登陆之后认证，2是实时时认证
    'USER_AUTH_KEY' => 'uid', //认证识别号  此名称可以自己取
    //'REQUIRE_AUTH_MODULE' =>   //需要认证模块
    'NOT_AUTH_CONTROLLER' => 'Login,Rbac',//无需认证模块  和上成重复，只用一个  写不需要认证的控制器名 例 'Index,Exit'
    'NOT_AUTH_ACTION' => 'leftMenu,check,businessStatistics' ,//无需认证操作  模块里的方法
    //'USER_AUTH_GATEWAY' =>  //认证网关,此处可以不使用
    'RBAC_ROLE_TABLE' => 'ss_role', //角色表名称
    'RBAC_USER_TABLE' => 'ss_role_user', //用户表名称
    'RBAC_ACCESS_TABLE' => 'ss_access', //权限表名称
    'RBAC_NODE_TABLE' => 'ss_node', //节点表名称
    'SHOW_PAGE_TRACE' => true,


 


    
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'logic', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'ss_', // 数据库表前缀  
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 调试模式
    
);