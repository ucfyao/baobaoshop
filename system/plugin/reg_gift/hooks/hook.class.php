<?php

class plugin_reg_gift_hook extends plugin
{
    public function after_register($member)
    {
        $config = cache('reg_gift', '', 'plugin');
        if (time() < (int)$config['start_time'] || time() > (int)$config['end_time']) return false;
        if ($config['exp'] > 0) model('member/member')->where("id={$member['id']}")->setInc('exp', $config['exp']);
        if ($config['money'] > 0) model('member/member')->where("id={$member['id']}")->setInc('money', $config['money']);


//        $arr = model('app')->where(array('identifier' => 'module.coupon', 'available' => 1))->find();
//        if ($arr) {
//            if ($config['coupon']) {
//                foreach ($config['coupon'] as $v) {
//                    if ($v['id']) {
//                        $userArr = model('coupon/coupon_list', 'service')->create_card($v['id'], 1, 8, 0, $mid);
//                    }
//                }
//            }
//
//        }
    }
}