<?php
if (!defined('IN_ADMIN')) exit('Access Denied');


if (!IS_POST) {
    // 显示配置页面
    include(PLUGIN_PATH . PLUGIN_ID . '/template/index.php');
} else {
    $info = validate_param($_GET);

    foreach ($info['sku_ids'] as $k => $v) {
        for ($i = 0; $i < $info['order_number']; $i++) {
            $item['member_name'] = $info['names'][array_rand($info['names'])];
            $item['time'] = mt_rand($info['start_time'], $info['end_time']);
            $item['sku_id'] = $v;
            $item['number'] = $info['goods_number'];
            $list[] = $item;
        }
    }
    model('virtual_sales')->addAll($list);
    showmessage('虚拟销量订单添加完毕。');
}

/**
 * 验证提交的参数
 * @param $param
 */
function validate_param($param)
{
    if (!$param['start_time']) showmessage('开始时间必须设置。');
    if (!$param['end_time']) showmessage('结束时间必须设置。');
    if (!is_int(strtotime($param['start_time']))) showmessage('开始时间格式错误。');
    if (!is_int(strtotime($param['end_time']))) showmessage('结束时间格式错误。');
    if (!$param['order_number']) showmessage('销售单数必须设置。');
    if (!is_int((int)$param['order_number'])) showmessage('销售单数必须设置为数字。');
    if (!$param['goods_number']) showmessage('商品销售数量。');
    if (!is_int((int)$param['goods_number'])) showmessage('商品销售数量必须设置为数字。');
    if (!$param['names']) showmessage('会员名称列表必须设置。');
    if (!$param['sku_ids']) showmessage('销售商品必须选择。');

    $data['start_time'] = strtotime($param['start_time']);
    $data['end_time'] = strtotime($param['end_time']);
    $data['order_number'] = (int)$param['order_number'];
    $data['goods_number'] = (int)$param['goods_number'];
    $data['names'] = explode(',', $param['names']);
    $data['sku_ids'] = $param['sku_ids'];
    return $data;
}