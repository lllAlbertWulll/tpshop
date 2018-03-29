<?php
namespace app\index\controller;

use think\Controller;

class Index extends Base
{
    public function index()
    {
        // 获取首页大图 相关数据
        // 获取广告位相关的数据

        // 商品分类数据--美食推荐数据
        $datas = model('Deal')->getNormalDealByCategoryCityId(1, $this->city->id);

        // 获取4个子分类
        $meishicates = model('Category')->getNormalRecommendCategoryByParentId(1, 4);

        return $this->fetch('', [
            'datas' => $datas,
            'meishicates' => $meishicates,
        ]);
    }
}
