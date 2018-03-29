<?php

namespace app\bis\controller;

use think\Controller;
use Map;
use phpmailer\Email;

class Register extends Controller
{
    public function index()
    {
        // 获取一级城市的数据
        $citys = model('City')->getNormalCitysByParentId();
        // 获取一级栏目的数据
        $categorys = model('Category')->getNormalCategoryByParentId();
        return $this->fetch('', [
            'citys' => $citys,
            'categorys' => $categorys,
        ]);
    }

    public function add()
    {
        if (!request()->isPost()) {
            $this->error('请求错误');
        }
        // 获取表单的值
        // 1. 第一个参数：获取方法
        // 2. 第二个参数：默认值，当没有数据时的值
        // 3. 第三个参数：过滤方法，把获取的内容过滤，可防XSS攻击，脚本攻击
        $data = input('post.', '', 'htmlentities');
        // 检验数据
        $validate = validate('Bis');
        if (!$validate->scene('add')->check($data)) {
            $this->error($validate->getError());
        }

        // 获取经纬度
        $lnglat = Map::getLngLat($data['address']);
        if (empty($lnglat) || $lnglat['status'] != 0 || $lnglat['result']['precise'] != 1) {
            $this->error('无法获取数据，或者匹配的地址不精确');
        }

        // 判定提交的用户是否存在
        $accountResult = model('BisAccount')->get(['username' => $data['username']]);
        if ($accountResult) {
            $this->error('该用户已存在，请重新分配');
        }

        // 商户基本信息入库
        $bisData = [
            // htmlentities() 防XSS攻击 可以把内容转换成html格式
//	        'name' => htmlentities($data['name']),
            'name' => $data['name'],
            'city_id' => $data['city_id'],
            'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'] . ',' . $data['se_city_id'],
            'logo' => $data['logo'],
            'licence_logo' => $data['licence_logo'],
            'description' => empty($data['description']) ? '' : $data['description'],
            'bank_info' => $data['bank_info'],
            'bank_name' => $data['bank_name'],
            'bank_user' => $data['bank_user'],
            'faren' => $data['faren'],
            'faren_tel' => $data['faren_tel'],
            'email' => $data['email'],
        ];

        $bisId = model('Bis')->add($bisData);

        // 总店相关信息检验

        // 总店相关信息入库
        $data['cat'] = '';
        if (!empty($data['se_category_id'])) {
            $data['cat'] = implode('|', $data['se_category_id']);
        }

        $locationData = [
            'bis_id' => $bisId,
            'name' => $data['name'],
            'logo' => $data['logo'],
            'tel' => $data['tel'],
            'contact' => $data['contact'],
            'category_id' => $data['category_id'],
            'category_path' => $data['category_id'] . ',' . $data['cat'],
            'city_id' => $data['city_id'],
            'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'] . ',' . $data['se_city_id'],
            'address' => $data['address'],
            'api_address' => $data['address'],
            'open_time' => $data['open_time'],
            'content' => empty($data['content']) ? '' : $data['content'],
            'is_main' => 1,        // 1 => 表示的是总店信息
            'xpoint' => empty($lnglat['result']['location']['lng']) ? '' : $lnglat['result']['location']['lng'],
            'ypoint' => empty($lnglat['result']['location']['lat']) ? '' : $lnglat['result']['location']['lat'],
        ];

        $locationId = model('BisLocation')->add($locationData);

        // 账户相关信息检验

        // 自动生成密码的加盐字符串
        $data['code'] = mt_rand(100, 10000);
        $accountData = [
            'bis_id' => $bisId,
            'username' => $data['username'],
            'code' => $data['code'],
            'password' => md5($data['password'] . $data['code']),
            'is_main' => 1,        // 1 => 代表的是总管理员
        ];

        $accountId = model('BisAccount')->add($accountData);

        if (!$accountId) {
            $this->error('申请失败');
        }

        // 发送邮件
        $url = request()->domain() . url('bis/register/waiting', ['id' => $bisId]);
        $title = 'o2o 入驻申请通知';
        $content = "您提交的入住申请需等待平台审核， 您可以通过点击<a href='" . $url . "' target='_blank'>链接</a> 查看审核状态";
        Email::send($data['email'], $title, $content);

        $this->success('申请成功', url('register/waiting', ['id' => $bisId]));
    }

    public function waiting($id)
    {
        if (empty($id)) {
            $this->error('error');
        }
        $detail = model('Bis')->get($id);

        return $this->fetch('', [
            'detail' => $detail,
        ]);
    }
}
