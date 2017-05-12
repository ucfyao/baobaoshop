<?php

    if (!defined('IN_ADMIN'))
        exit('Access Denied');
    
    $plugins = cache('plugins');
    $plugins = $plugins[$_GET['mod']];


    if (IS_POST) {
        if ($_GET['mod'] == 'auto_comment'){
            if ($_GET['action'] == 'setting') {
                if (!preg_match('/^\+?[1-9][0-9]*$/', $_POST['setting']))
                    showmessage('天数为整数');
                cache('auto_comment', $_POST, 'plugin');
                showmessage('配置已经保存。');
            }else {
                $set_time = get_setting_time();
                $lists = order_validate($set_time);
                foreach ($lists['order'] as $k => $v) {
                    $params['tid'] = $v['tid'];
                    add_comment($params);
                }
                showmessage(lang('comment_succsss','auto_comment#language'));
            }
        }else{
            showmessage(lang('error','auto_comment#language'));
        }
    } else {
        $set_time = get_setting_time();
        if ($_GET['action'] == 'setting') {
            include(PLUGIN_PATH . PLUGIN_ID . '/template/auto_add.php');
        }else{
            $lists = order_validate($set_time);
            include(PLUGIN_PATH . PLUGIN_ID . '/template/auto_comment.php');
        }
    }

    /**
     * 获取状态中文信息
     * @param  string $ident 标识
     * @return [string]
     */
    function ch_status($ident) {
        $arr = array(
                'cancel'        => '已取消',
                'recycle'       => '已回收',
                'delete'        => '已删除',
                'create'        => '创建订单',
                'load_pay'      => '待付款',
                'pay'           => '已付款',
                'load_confirm'  => '待确认',
                'part_confirm'  => '部分确认',
                'all_confirm'   => '已确认',
                'load_delivery' => '待发货',
                'part_delivery' => '部分发货',
                'all_delivery'  => '已发货',
                'load_finish'   => '待收货',
                'part_finish'   => '部分完成',
                'all_finish'    => '已完成',
                'receive'       => '已收货',

                // 前台时间轴
                'time_cancel'   => '取消订单',
                'time_recycle'  => '回收订单',
                'time_create'   => '提交订单',
                'time_pay'      => '确认付款',
                'time_confirm'  => '确认订单',
                'time_delivery' => '商品发货',
                'time_finish'   => '确认收货',
            );
        return $arr[$ident];
    }
    /*
     *订单自动筛选
     */
    function order_validate($set_time){

        //求出今天0点的时间
        $data = date('Y-m-d',time());
        $data_map = explode('-',$data);
        $y = $data_map[0];
        $m = $data_map[1];
        $d = $data_map[2];
        $todayTime= mktime(0,0,0,$m,$d,$y);
        //规定天的时间戳
        $auto_times = $set_time *24*3600;

        $sqlmap = array(
            "delivery_status"=>2,
            "delivery_id"=>array("gt",0),
            "iscomment"=>0
            );
        $lists = model('order_sku')->where($sqlmap)->field('sku_id,spu_id,order_sn,buyer_id,id as tid,delivery_id')->select();
        $i = 0;
        foreach ($lists as $k => $v) {
            $sql['id'] = $v['delivery_id'];
            $receive_time = model('order_delivery')->where($sql)->getField('receive_time');
            if($receive_time>0){
                if(($receive_time+$auto_times)<$todayTime){
                    $list['order'][$i] = $v;
                    $list['order'][$i]['over_day'] = ceil(($todayTime-$receive_time-$auto_times)/(24*3600));
                    $list['order'][$i]['receive_time'] = $receive_time;
                    ++$i;
                }
            }
        }
        $list['count'] = count($list['order']);
        return $list;
    }

    function add_comment($params) {
        $r = model('order/order_sku','service')->detail((int) $params['tid']);
        if($r === false) {
            $error = model('order/order_sku','service')->error;
            showmessage($error);
            return false;
        }
        if($r['iscomment'] == 1) {
            showmessage(lang('repeat_publish','auto_comment#language'));
            return false;
        }
        $params['spu_id'] = model('goods_sku')->where(array('sku_id'=>$r['sku_id']))->getField('spu_id');
        $params['sku_id'] = $r['sku_id'];
        $params['order_sn'] = $r['order_sn'];
        runhook('comment_add',$params);
        $result = update($params);
        if(!$result) {
            return false;
        }
        return true;
    }

    function get_setting_time(){
        $auto_comment = cache('auto_comment', '', 'plugin');
        //参数是设定的天数
        $set_time = $auto_comment['setting'];
        if(!$set_time){
            $set_time = 7;
            $auto_comment['setting'] = 7;
            $auto_comment = cache('auto_comment', $auto_comment, 'plugin');
        }
        return $set_time;
    }

    function update($params) {
        $params['id'] = (int) $params['id'];
        $params['mid'] = 0;
        $params['spu_id'] = (int) $params['spu_id'];
        $params['order_sn'] = $params['order_sn'];
        $params['content'] = '系统默认好评';
        $params['mood'] = 'positive';
        if($params['spu_id'] < 1 || $params['sku_id'] < 1) {
            showmessage(lang('goods_info_error','auto_comment#language'));
            return false;
        }
        if(empty($params['order_sn'])) {
            showmessage(lang('order_sn_error','order#language'));
            return false;
        }
        if(!in_array($params['mood'], array('positive','neutral','negative'))) {
            showmessage(lang('evaluate_info_error','auto_comment#language'));
            return false;
        }
        if(strlen($params['content']) < 5) {
            showmessage(lang('evaluate_content_empty','auto_comment#language'));
            return false;
        }
        if($params['imgs'] && is_array($params['imgs'])) {
            $attachment = implode(',', $params['imgs']);
            $params['imgs'] = json_encode($params['imgs']);
        }
        runhook('comment_update',$params);
        $result = model('comment')->update($params);
        if($result === false) {
            $error = model('comment')->getError();
            showmessage(lang('evaluate_content_empty','auto_comment#language'));
            return false;
        }
        /* 操作附件 */
        if($attachment) model('attachment/attachment','service')->attachment($attachment);
        /* 处理订单商品表 */
        model('order_sku')->where(array('id' => $params['tid']))->setField('iscomment', 1);
        return true;
    }