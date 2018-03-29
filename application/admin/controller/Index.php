<?php

namespace app\admin\controller;

use Map;
use phpmailer\Email;
use think\Controller;

class Index extends Controller
{
  public function index()
  {
	return $this->fetch();
  }

  // 地图测试
  public function map()
  {
    // Map::getLngLat('北京昌平沙河地铁');
	// <img style="margin:20px" width="280" height="140" src="{:url('index/map')}"/>
	return Map::staticimage('北京昌平沙河地铁');
  }

  // 邮件测试方法
  public function email()
  {
	Email::send('gegewv@outlook.com', 'TP5邮件测试', 'TP5 Email Test Success');
  }

  public function welcome()
  {
	return "欢迎来到O2O主后台首页!";
  }
}
