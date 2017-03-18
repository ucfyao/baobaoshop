<?php

class plugin_promote_hook {

    /**
     * 该渠道推广所得点击量,微信点击量，
     */
    public function site_isclosed() {
        if (!cookie('promo_code')) {
            if ($_GET['promo_code']) {
                $list = model('promote')->where(array('identity' => $_GET['promo_code']))->find();
                cookie('promo_code', $_GET['promo_code'],99999999);
                if ($list) {
                    $date['id'] = $list['id'];
                    $date['click_num'] = $list['click_num'] + 1;
                    if (defined('IS_WECHAT')) {
                        $date['wx_num'] = $list['wx_num'] + 1;
                    }
                    model('promote')->update($date);
                }
            }
        }
    }

    /**
     * 该渠道推广所得会员注册数，注册用户id集合（逗号隔开），
     */
    public function after_register(&$mid) {
        if (cookie('promo_code')) {
            $list = model('promote')->where(array('identity' => cookie('promo_code')))->find();
            if ($list) {
                $date['id'] = $list['id'];
                $date['member_num'] = $list['member_num'] + 1;
                $arr = explode(',', $list['members']);
                $arr[] = $mid;
                $date['members'] = implode(",", $arr);
                model('promote')->update($date);
            }
        }
    }

    /**
     * 该渠道推广所得商品购买量，所得订单成交量，成交订单编号集合（逗号隔开）】
     */
    public function create_order(&$member) {
        if (cookie('promo_code')) {
            $list = model('promote')->where(array('identity' => cookie('promo_code')))->find();
            if ($list) {
                $order = model('order_sku')->where(array('order_sn' => $member['order_sn']))->select();
                if ($order) {
                    foreach ($order as $v) {
                        $list['good_num'] = $list['good_num'] + $v['buy_nums'];
                    }
                    $date['id'] = $list['id'];
                    $date['good_num'] = $list['good_num'];
                    $date['order_num'] = $list['order_num'] + 1;
                    $arr = explode(',', $list['orders']);
                    $arr[] = $member['order_sn'];
                    $date['orders'] = implode(",", $arr);
                    model('promote')->update($date);
                }
            }
        }
    }
 /**
     * 该渠道推广所得完成商品购买量，所得订单完成量，完成订单编号集合（逗号隔开）】
     */
    public function order_finish(&$sn) {
        if (cookie('promo_code')) {
            $list = model('promote')->where(array('identity' => cookie('promo_code')))->find();
            if ($list) {
                $order = model('order_sku')->where(array('order_sn' => $sn))->select();
                if ($order) {
                    foreach ($order as $v) {
                        $list['dealgood_num'] = $list['dealgood_num'] + $v['buy_nums'];
                    }
                    $date['id'] = $list['id'];
                    $date['dealgood_num'] = $list['dealgood_num'];
                    $date['dealorder_num'] = $list['dealorder_num'] + 1;
                    $arr = explode(',', $list['dealorders']);
                    $arr[] = $sn;
                    $date['dealorders'] = implode(",", $arr);
                    model('promote')->update($date);
                }
            }
        }
    }
}
