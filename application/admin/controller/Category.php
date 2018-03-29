<?php

namespace app\admin\controller;

use think\Controller;

class Category extends Controller
{
  private $obj;

  public function _initialize()
  {
	$this->obj = model('Category');
  }

  public function index()
  {
    $parentId = input('param.parent_id', 0, 'intval');
	$categorys = $this->obj->getFirstCategorys($parentId);
	return $this->fetch('', [
	  'categorys' => $categorys,
	]);
  }

  // 返回添加视图
  public function add()
  {
    $categorys = $this->obj->getNormalFirstCategory();
	return $this->fetch('', [
	  'categorys' => $categorys,
	]);
  }

  // 添加数据
  public function save()
  {
    //print_r($_POST);
	//print_r(input('post.'));
	//print_r(request()->post());
	if (!request()->isPost()) {
	  $this->error('请求失败');
	}
	$data = input('post.');
	$validate = validate('Category');
	if (!$validate->scene('add')->check($data)) {
	  $this->error($validate->getError());
	}
	if (!empty($data['id'])) {
	  return $this->update($data);
	}
	// 把 $data 提交到 model 层
	$res = $this->obj->add($data);
	if ($res) {
	  $this->success('分类添加成功');
	} else {
	  $this->error('分类添加失败');
	}
  }

  // 返回编辑视图
  public function edit($id = 0)
  {
    if (intval($id) < 1) {
      $this->error('参数不合法');
	}

	$category = $this->obj->get($id);
	$categorys = $this->obj->getNormalFirstCategory();
	return $this->fetch('', [
	  'categorys' => $categorys,
	  'category' => $category,
	]);
  }

  // 更新数据
  public function update($data)
  {
    $res =  $this->obj->save($data, ['id' => intval($data['id'])]);
    if ($res) {
      $this->success('更新成功');
	} else {
      $this->error('更新失败');
	}
  }

  // 排序逻辑
  public function listorder($id, $listorder)
  {
    $res = $this->obj->save(['listorder'=>$listorder], ['id'=>$id]);
    if ($res) {
      $this->result($_SERVER['HTTP_REFERER'], 1, 'success');
	} else {
	  $this->result($_SERVER['HTTP_REFERER'], 0, 'fail');
	}
  }

  // 修改状态
  public function status()
  {
    $data = input('get.');
    $validate = validate('Category');
    if (!$validate->scene('status')->check($data)) {
      $this->error($validate->getError());
	}
	$res = $this->obj->save(['status'=>$data['status']], ['id'=>$data['id']]);
	if ($res) {
	  $this->success('success');
	} else {
	  $this->error('fail');
	}
  }
}
