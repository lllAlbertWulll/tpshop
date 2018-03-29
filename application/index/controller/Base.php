<?php
namespace app\index\controller;

use think\Controller;

class Base extends Controller
{
    public $city = '';
    public $account = '';

    public function _initialize()
    {
        // 获取城市的数据
        $citys = model('City')->getNormalCitys();

        // 获取用户的数据
        $user = $this->getLoginUser();

        // 获取首页分类的数据
        $cats = $this->getRecommendCats();

        $this->getCity($citys);
        $this->assign('citys', $citys);
        $this->assign('city', $this->city);
        $this->assign('user', $user);
        $this->assign('cats', $cats);
        // 动态加载不同控制器页面下的css
        $this->assign('controller', strtolower(request()->controller()));
        $this->assign('title', 'o2o团购网');
    }

    public function getCity($citys)
    {
        foreach ($citys as $city) {
            $city = $city->toArray();
            if ($city['is_default'] == 1) {
                $defaultuname = $city['uname'];
                break;  // 终止foreach
            }
        }

        $defaultuname = $defaultuname ? $defaultuname : 'nanchang';
        if (session('cityuname', '', 'o2o') && !input('get.city')) {
            $cityuname = session('cityuname', '', 'o2o');
        } else {
            $cityuname = input('get.city', $defaultuname, 'trim');

            session('cityuname', $cityuname, 'o2o');
        }
        $this->city = model('City')->where(['uname' => $cityuname])->find();
    }

    public function getLoginUser()
    {
        if (!$this->account) {
            $this->account = session('o2o_user', '', 'o2o');
        }
        return $this->account;

    }

    // 获取首页推荐当中的商品分类数据
    public function getRecommendCats()
    {
        $parentIds = $sedCatArr = $recomCats = [];

        // 获取一级分类数据
        $cats = model('Category')->getNormalRecommendCategoryByParentId(0,5);

        foreach ($cats as $cat) {
            $parentIds[] = $cat->id;
        }

        // 获取二级分类数据
        $sedCats = model('Category')->getNormalCategoryIdParentId($parentIds);

        foreach ($sedCats as $sedCat) {
            $sedCatArr[$sedCat->parent_id][] = [
                'id' => $sedCat->id,
                'name' => $sedCat->name,
            ];
        }

        // 联合一级分类和二级分类
        foreach ($cats as $cat) {
            // recomCats 代表是一级和二级数据， []第一个参数是一级分类的name，第二个参数是此一级分类下面的所有二级分类数据
            $recomCats[$cat->id] = [$cat->name, empty($sedCatArr[$cat->id]) ? [] : $sedCatArr[$cat->id]];
        }

        return $recomCats;
    }
}
