<?php
namespace app\index\controller;

class Detail extends Base
{
    public function index($id)
    {
        if (!intval($id)) {
            $this->error('ID不合法！');
        }

        // 根据id查询商品的数据
        $deal = model('Deal')->get($id);
        if (!$deal || $deal->status != 1) {
            $this->error('该商品不存在！');
        }

        // 获取分类信息
        $category = model('Category')->get($deal->category_id);
        // 获取分店信息
        $locations = model('BisLocation')->getNormalLocationInId($deal->location_ids);

        //
        $flag = 0;
        if ($deal->start_time > time()) {
            $flag = 1;

            $timedata = '';
            $dtime = $deal->start_time-time();
            $d = floor($dtime/(3600*24));   // floor() 向下取整
            if ($d) {
                $timedata .= $d."天 ";
            }
            $h = floor($dtime%(3600*24)/3600);   // floor() 向下取整
            if ($h) {
                $timedata .= $h."时 ";
            }
            $m = floor($dtime%(3600*24)%3600/60);   // floor() 向下取整
            if ($d) {
                $timedata .= $m."分钟 ";
            }
            $this->assign('timedata', $timedata);
        }

        return $this->fetch('', [
            'title' => $deal->name,
            'category' => $category,
            'locations' => $locations,
            'deal' => $deal,
            'overplus' => $deal->total_count-$deal->buy_count,
            'flag' => $flag,
            'mapstr' => $locations[0]['xpoint'].','.$locations[0]['ypoint'],
        ]);
    }
}
