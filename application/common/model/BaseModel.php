<?php
namespace app\common\model;

use think\Model;

/**
  * BaseModel 公共的模型层
  */

class BaseModel extends Model
{
    // 自动填写时间戳字段, 也可以在 database.php 文件中设置
    protected $autoWriteTimestamp = true;

    public function add($data)
    {
        $data['status'] = 0;
        $this->save($data);
        return $this->id;
    }

    public function updateById($data, $id)
    {
        return $this->allowField(true)->save($data, ['id' => $id]);
    }
}
