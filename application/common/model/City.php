<?php

namespace app\Common\model;

use think\Model;

class City extends Model
{
    public function getNormalCitysByParentId($parentId = 0)
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

    public function getNormalCitys()
    {
        $data = [
            'status' => 1,
            'parent_id' => ['gt', 0],   // gt 表示大于标签
        ];

        $order = ['id' => 'desc'];

        return $this->where($data)->order($order)->select();
    }
}
