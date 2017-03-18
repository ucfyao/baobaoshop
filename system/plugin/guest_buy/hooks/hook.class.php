<?php

class plugin_guest_buy_hook extends plugin
{
    /**
     * 定义订单不必登录常量
     */
    public function pre_system()
    {
        define('NO_LOGIN', true);
    }

    /**
     * 编辑添加收货地址链接
     */
    public function global_footer()
    {
        if (!defined('NO_LOGIN')) return false;
        $member = model('member/member', 'service')->init();
        if ($member['id'] > 0) return false;
        return "<script>$(function () {
		$('.new-address').attr('href','plugin.php?id=guest_buy:address&skuids=" . $_GET['skuids'] . "');
    });</script>";
    }

    /**
     * 移动端重定义添加地址链接
     */
    public function wap_settlement_extra()
    {
        $referer = urlencode($_SERVER["REQUEST_URI"]);
        $html = "<script>
                $(function() {
                  $('a.settlement-address').attr('href','plugin.php?id=guest_buy:address&skuids={$_GET['skuids']}&referer={$referer}');
                })
                </script>";
        return $html;
    }
}
