<?php

namespace app\common\model;

use think\Model;

class Order extends Model
{
    // 自动填写时间戳字段, 也可以在 database.php 文件中设置
    protected $autoWriteTimestamp = true;

    public function add($data)
    {
        $data['status'] = 1;
        //$data['create_time'] = time();
        return $this->save($data);
    }
}