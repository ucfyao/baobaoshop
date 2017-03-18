<?php

class plugin_goods_sales_hook
{
    /**
     * 查询购买记录处
     * @param $lists
     */
    public function order_sku_records(&$result)
    {
        if ($_GET['id']) {
            $map['sku_id'] = array('in', sku_ids($_GET['id']));
        }

        if ($_GET['sku_id']) {
            $spu_id = spu_id($_GET['sku_id']);
            $map['sku_id'] = array('in', sku_ids($spu_id));
        }
        $order_list = model('virtual_sales')->where($map)->order('time DESC')->select();
        if (!$order_list) return false;
        foreach ($order_list as $k => $v) {
            $result['count']++;
            $sku_info = model('goods_sku')->find($v['sku_id']);
            $item['username'] = cut_str($name, 1, 0).'**'.cut_str($v['member_name'], 1, -1);
            $item['sku_price'] = $sku_info['shop_price'];
            $item['buy_nums'] = $v['number'];
            $item['dateline'] = date('Y-m-d H:i:s', $v['time']);
            $item['_sku_spec'] = format_spec(json_decode($sku_info['spec'], true));
            $result['lists'][] = $item;
            array_multisort($result['list'],'dateline');
        }
    }
}

function format_spec($data)
{
    $spec = '';
    foreach ($data as $value) {
        $spec .= "{$value['name']}：{$value['value']} ";
    }
    return $spec;
}

function sku_ids($spu_id)
{
    return model('goods_sku')->where("spu_id = {$spu_id}")->getField('sku_id',true);
}

function spu_id($sku_id)
{
    return model('goods_sku')->where("sku_id = {$sku_id}")->getField('spu_id');
}