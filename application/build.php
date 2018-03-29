<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
  // 生成应用公共文件
  '__file__' => ['common.php', 'config.php', 'database.php'],

  // 定义demo模块的自动生成 （按照实际定义的文件名生成）
  /*
  'demo' => [
	'__file__' => ['common.php'],
	'__dir__' => ['behavior', 'controller', 'model', 'view'],
	'controller' => ['Index', 'Test', 'UserType'],
	'model' => ['User', 'UserType'],
	'view' => ['index/index'],
  ],
  */
  // 自动生成模块时，需将根目录下的 build.php 文件移至 application 下 再在根目录下执行 php think build 命令
  // 其他更多的模块定义
  'common' => [
    '__dir__' => ['model'],
	'model' => ['Category', 'Admin'],
  ],
  'admin' => [
    '__dir__' => ['controller', 'view'],
	'controller' => ['Index'],
	'view' => ['index/index'],
  ],
  'api' => [
    '__dir__' => ['controller', 'view'],
	'controller' => ['Index', 'Image'],
  ],
  'bis' => [
    '__dir__' => ['controller', 'view'],
	'controller' => ['Register', 'Login']
  ],
];
