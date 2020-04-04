<?php
namespace app\admin\controller;
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/3
 * Time: 11:16
 */
  class Index {
      public function index($who = '隔壁老王') {
          return $who.'555';
      }
  }