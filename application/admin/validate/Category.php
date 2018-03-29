<?php
namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
  /*
   * $rule 的参数解析
   * 第一个参数为：传递过来的数据的 key 值
   * 第二个参数为：条件|条件|....
   * (可选)第三个参数为：自定义提示|自定义提示|....
   */
  protected $rule = [
    ['id', 'number'],
    ['name', 'require|max:10', '分类名不能为空|分类名不能超过10个字符'],
	['parent_id', 'number'],
	['listorder','number'],
	['status', 'number|in:-1,0,1', '状态值必须是数字|状态值不合法'],
  ];

  /* 场景设置 */
  protected $scene = [
    'add' 		=> ['name', 'parent_id', 'id'],	// 添加
	'listorder' => ['id', 'listorder'],			// 排序
	'status'	=> ['id', 'status'],			// 状态
  ];
}