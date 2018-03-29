<?php
namespace app\common\model;

use think\Model;

class Deal extends BaseModel
{
    public function getNormalDeals($data = [])
    {
        $data['status'] = 1;
        $order = ['id' => 'desc'];

        $result = $this->where($data)->order($order)->paginate();

//        echo $this->getLastSql();exit();
        return $result;
    }

    /**
     * 根据分类和城市获取数据
     * @param $id 分类id
     * @param $cityId 城市id
     * @param int $limit 条数
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNormalDealByCategoryCityId($id, $cityId, $limit = 10)
    {
        $data = [
            'end_time' => ['gt', time()],
            'category_id' => $id,
            'city_id' => $cityId,
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

    /**
     * @param array $data
     * @param $orders
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getDealByConditions($data = [], $orders)
    {
        if (!empty($orders['order_sales'])) {
            $order['buy_count'] = 'desc';
        }
        if (!empty($orders['order_price'])) {
            $order['current_price'] = 'desc';
        }
        if (!empty($orders['order_time'])) {
            $order['create_time'] = 'desc';
        }
        $order['id'] = 'desc';

        $datas[] = "end_time > ".time();
        if (!empty($data['se_category_id'])) {
            $datas[] = " find_in_set(". $data['se_category_id'] .", 'se_category_id')";
        }
        if (!empty($data['category_id'])) {
            $datas[] = ' category_id = '. $data['category_id'];
        }
        if (!empty($data['city_id'])) {
            $datas[] = 'city_id = '.$data['city_id'];
        }
        $datas[] = 'status=1';

        $result = $this->where(implode(' AND ', $datas))->order($order)->paginate();
        // echo $this->getLastSql();exit();
        return $result;
    }
}
