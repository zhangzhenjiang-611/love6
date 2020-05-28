<?php
namespace Home\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
require_once __DIR__ . '/Log.php';
class DrugClController extends Controller {
public function index()
	{
		$this->display();
	}
}