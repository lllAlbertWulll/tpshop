<?php

namespace app\common\model;

use think\Model;

class Category extends Model
{
    // 自动填写时间戳字段, 也可以在 database.php 文件中设置
    protected $autoWriteTimestamp = true;

    public function add($data)
    {
        $data['status'] = 1;
        //$data['create_time'] = time();
        return $this->save($data);
    }

    public function getNormalFirstCategory()
    {
        $data = [
            'status' => 1,
            'parent_id' => 0,
        ];

        $order = [
            'id' => 'desc',
        ];

        return $this->where($data)->order($order)->select();
    }

    public function getFirstCategorys($parentId = 0)
    {
        $data = [
            'parent_id' => $parentId,        // 父级 id 等于 0
            'status' => ['neq', -1],        // 状态值不等于 -1
        ];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];

        $result = $this->where($data)->order($order)->paginate();

        return $result;
    }

    public function getNormalCategoryByParentId($parentId = 0)
    {
        $data = [
            'status' => 1,
            'parent_id' => $parentId,
        ];

        $order = [
            'id' => 'desc',
        ];

        return $this->where($data)->order($order)->select();
    }

    // 获取前五条主分类
    public function getNormalRecommendCategoryByParentId($id = 0, $limit = 5)
    {
        $data = [
            'parent_id' => $id,
            'status' => 1,
        ];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];

        $result = $this->where($data)->order($order);
        if ($limit) {
            $result = $result->limit($limit);
        }
        return $result->select();
    }

    // 根据主分类id获取子分类
    public function getNormalCategoryIdParentId($ids)
    {
        $data = [
            'parent_id' => ['in', implode(',', $ids)],
            'status' => 1,
        ];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];

        $result = $this->where($data)->order($order)->select();
        return $result;
    }
}