<?php

class coupon_service extends service {

    public function _initialize() {
        $this->table = model('coupon');
        $this->sku_db = model('goods/goods_sku');
        $this->coupon_list_table = model('coupon_list');
        $this->coupon_list_service = model('coupon_list', 'service');
    }

    public function get_lists($params) {
        $result = $this->table->page($params['page'])->limit($params['limit'])->select();
        foreach ($result as $k => $v) {
            $rules = json_decode($result[$k]['rules'], TRUE);
            $type_remind = json_decode($result[$k]['type_remind'], TRUE);
            $result[$k]['type_remind'] = implode('、', $type_remind);
            $result[$k]['condition'] = sprintf("%.2f", $rules['condition']);
            $result[$k]['discount'] = sprintf("%.2f", $rules['discount']);
            if ($v['num'] == -1) {
                $result[$k]['num'] = $this->coupon_list_table->where(array('cid' => $v['id']))->count();
            }
        }
        if ($result === FALSE) {
            $this->error = $this->table->getError();
        }
        return $result;
    }

    /**
     * [count 条数]
     * @param  [type] $sqlmap [sql条件]
     * @return [type]         [description]
     */
    public function count($sqlmap){
        return $this->table->where($sqlmap)->count();
    }

    /**
     * [fetch_by_id 查询单条数据]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function fetch_by_id($id) {
        $data['id'] = $id;
        $result = $this->table->where($data)->find();
        $result['rules'] = json_decode($result['rules'], TRUE);
        $result['type_remind'] = json_decode($result['type_remind'], TRUE);
        if ($result['sku_ids'] > 0) {
            $result['sku_ids'] = explode(",", $result['sku_ids']);
        }
        return $result;
    }

    /**
     * 更新活动
     * @param array $params 数组
     * @return bool
     */
    public function update($params = array()) {
        $data = $this->_format($params);
        if ($data === FALSE) {
            return FALSE;
        }
        $result = $this->table->update($data);
        $this->coupon_cache();
        if ($result === FALSE) {
            $this->error = $this->table->getError();
            return FALSE;
        }
        if ($data['num'] > 0 && empty($data['id'])) {
            $produce = $this->coupon_list_service->create_card($result, $data['num']);
            if ($produce === FALSE) {
                $this->error = lang('produce_coupon_error', 'coupon/language');
                $this->table->where(array('id' => $result))->delete();
                return FALSE;
            }
        }
        return $result;
    }

    /**
     * [_format 数据整理]
     * @param  array  $params [参数]
     * @return [type]         [description]
     */
    private function _format($params = array()) {
        if (empty($params)) {
            $this->error = lang('_param_error_');
            return FALSE;
        }
        if (strlen($params['name']) > 50) {
            $this->error = lang('name_limit', 'coupon/language');
            return FALSE;
        }
        if (strlen($params['describe']) > 225) {
            $this->error = lang('describe_limit', 'coupon/language');
            return FALSE;
        }
        if (floor($params['num']) != $params['num']) {
            $this->error = lang('num_type', 'coupon/language');
            return FALSE;
        }
        $params['num'] = (int) $params['num'];
        if ($params['num'] == 0 || $params['num'] < -1) {
            $this->error = lang('num_number_limit', 'coupon/language');
            return FALSE;
        }
        if (strlen($params['num']) > 8) {
            $this->error = lang('num_biggest_limit', 'coupon/language');
            return FALSE;
        }
        if (ceil($params['rules']['discount']) <= 0) {
            $this->error = lang('discount_limit', 'coupon/language');
            return FALSE;
        }
        if (strlen((int) $params['rules']['discount']) > 5) {
            $this->error = lang('discount_biggest_limit', 'coupon/language');
            return FALSE;
        }
        if (is_numeric($params['rules']['condition']) == FALSE) {
            $this->error = lang('condition_type', 'coupon/language');
            return FALSE;
        }
        //转整型为-1时，condition仅只有本身为整型符合
        if ((int) $params['rules']['condition'] == -1 && floor($params['rules']['condition']) != $params['rules']['condition']) {
            $this->error = lang('condition_limit', 'coupon/language');
            return FALSE;
        }
        //转整型为0时，condition仅只有大于0符合
        if (((int) $params['rules']['condition'] == 0 && $params['rules']['condition'] < 0) || $params['rules']['condition'] == 0 || $params['rules']['condition'] < -1) {
            $this->error = lang('condition_limit', 'coupon/language');
            return FALSE;
        }
        if (strlen((int) $params['rules']['condition']) > 5) {
            $this->error = lang('condition_biggest', 'coupon/language');
            return FALSE;
        }
        $params['receive_num'] = (int) $params['receive_num'];
        if ($params['receive_num'] <= 0) {
            $this->error = lang('receive_num_limit', 'coupon/language');
            return FALSE;
        }
        if ($params['receive_num'] > $params['num'] && $params['num'] > 0) {
            $this->error = lang('receive_num_error', 'coupon/language');
            return FALSE;
        }
        if (floor($params['time']) != $params['time']) {
            $this->error = lang('type_time_limit', 'coupon/language');
            return FALSE;
        }
        $params['time'] = (int) $params['time'];
        $params['type_time'] = (int) $params['type_time'];
        if ($params['type_time'] == 2 && $params['time'] <= 0) {
            $this->error = lang('type_time_limit', 'coupon/language');
            return FALSE;
        }
        if ($params['type_time'] == 2 && strlen($params['time']) > 3) {
            $this->error = lang('type_time_biggest', 'coupon/language');
            return FALSE;
        }
        if ($params['type_time'] == 1 && empty($params['start_time'])) {
            $this->error = lang('start_time_not_exist', 'coupon/language');
            return FALSE;
        }
        if ($params['type_time'] == 1 && empty($params['end_time'])) {
            $this->error = lang('end_time_not_exist', 'coupon/language');
            return FALSE;
        }
        if (isset($params['start_time']) && !empty($params['start_time'])) {
            $params['start_time'] = strtotime($params['start_time']);
        }
        if (isset($params['end_time']) && !empty($params['end_time'])) {
            $params['end_time'] = strtotime($params['end_time']);
        }
        if ($params['type_time'] == 1 && $params['start_time'] >= $params['end_time']) {
            $this->error = lang('start_end_error', 'coupon/language');
            return FALSE;
        }
        if (floor($params['remind']) != $params['remind']) {
            $this->error = lang('remind_time_limit', 'coupon/language');
            return FALSE;
        }
        $params['remind'] = (int) $params['remind'];
        $params['remind_time'] = (int) $params['remind_time'];
        if ($params['remind_time'] <= 0 && $params['remind'] == 1) {
            $this->error = lang('remind_time_limit', 'coupon/language');
            return FALSE;
        }
        if ($params['remind_time'] >= $params['time'] && $params['remind'] == 1 && $params['type_time'] == 2) {
            $this->error = lang('time_error', 'coupon/language');
            return FALSE;
        }
        $day = date('d', ($params['end_time'] - $params['start_time'])); //活动时间范围天数
        if ($params['remind_time'] >= (int) $day && $params['remind'] == 1 && $params['type_time'] == 1) {
            $this->error = lang('time_limit', 'coupon/language');
            return FALSE;
        }
        if (empty($params['type_remind']) && $params['remind'] == 1) {
            $this->error = lang('type_remind_not_exist', 'coupon/language');
            return FALSE;
        }
        $params['type_use'] = (int) $params['type_use'];
        if (empty($params['sku_ids']) && $params['type_use'] == 2) {
            $this->error = lang('sku_ids_not_exist', 'coupon/language');
            return FALSE;
        }
        if ($params['type_use'] == 1) {
            $params['sku_ids'] = '';
        } else {
            $params['sku_ids'] = implode(",", $params['sku_ids']);
        }
        $params['rules'] = json_encode($params['rules']);
        $params['type_remind'] = json_encode($params['type_remind']);

        $data = array();
        if ($params['id'])
            $data['id'] = $params['id'];
        $data['name'] = $params['name'];
        $data['describe'] = $params['describe'];
        $data['num'] = $params['num'];
        $data['rules'] = $params['rules'];
        $data['sku_ids'] = $params['sku_ids'] ? $params['sku_ids'] : 0;
        $data['type_use'] = $params['type_use'];
        $data['type_buy'] = $params['type_buy'] ? $params['type_buy'] : 0;
        $data['remind'] = $params['remind'];
        $data['remind_time'] = $params['remind_time'];
        $data['type_remind'] = $params['type_remind'];
        $data['receive_num'] = $params['receive_num'];
        $data['type_time'] = $params['type_time'];
        $data['time'] = $params['time'] ? $params['time'] : 0;
        $data['end_time'] = $params['end_time'] ? $params['end_time'] : 0;
        $data['start_time'] = $params['start_time'] ? $params['start_time'] : 0;
        return $data;
    }

    /**
     * [delete 删除]
     * @param  [type] $ids [description]
     * @return [type]      [description]
     */
    public function delete($ids) {
        if (empty($ids)) {
            $this->error = lang('_param_error_');
            return FALSE;
        }
        $_map = array();
        if (is_array($ids)) {
            $_map['id'] = array("IN", $ids);
        } else {
            $_map['id'] = $ids;
        }
        $result = $this->table->where($_map)->delete();
        if ($result === FALSE) {
            $this->error = $this->table->getError();
            return FALSE;
        } else {
            $map = array();
            if (is_array($ids)) {
                $map['cid'] = array("IN", $ids);
            } else {
                $map['cid'] = $ids;
            }
            $this->coupon_list_table->where($map)->delete();
            $this->coupon_cache();
        }
        return TRUE;
    }

    /**
     * [build_cache 生成优惠券活动缓存]
     * @return [type] [description]
     */
    public function coupon_cache() {
        $result = $this->table->select(); //所有活动
        foreach ($result as $k => $v) {
            $rules = json_decode($result[$k]['rules'], TRUE);
            $result[$k]['condition'] = sprintf("%.2f", $rules['condition']);
            $result[$k]['discount'] = sprintf("%.2f", $rules['discount']);
            if ($v['type_time'] == 1 && $v['end_time'] <= time()) {
                unset($result[$k]);
            }
            if ($v['num'] == 0) {
                unset($result[$k]);
            }
            if ($v['type_use'] == 2 && $v['sku_ids'] == 0) {
                unset($result[$k]);
            }
        }
        cache('coupon_activity', $result, 'module');
        return TRUE;
    }

    /**
     * [coupon_activity 优惠券活动缓存]
     */
    public function coupon_activity() {
        $result = cache('coupon_activity', '', 'module');
        foreach ($result as $k => $v) {
            $rules = json_decode($result[$k]['rules'], TRUE);
            $type_remind = json_decode($result[$k]['type_remind'], TRUE);
            $result[$k]['type_remind'] = implode('、', $type_remind);
            $result[$k]['type_coupon'] = $rules['type'];
            $result[$k]['condition'] = $rules['condition'];
            $result[$k]['discount'] = $rules['discount'];
        }
        return $result;
    }

    /**
     *
     * @param type $params
     * @return type
     * 注册送礼-获取优惠卷和总量
     */
    public function ajax_get_lists($params) {
        $sqlmap['_string'] = "(end_time > " . time() . ") or (end_time =0 and type_time=2)";
        $result = $this->table->page($params['page'])->limit($params['limit'])->where($sqlmap)->select();
        foreach ($result as $k => $v) {
            $rules = json_decode($result[$k]['rules'], TRUE);
            $type_remind = json_decode($result[$k]['type_remind'], TRUE);
            $result[$k]['type_remind'] = implode('、', $type_remind);
            $result[$k]['condition'] = sprintf("%.2f", $rules['condition']);
            $result[$k]['discount'] = sprintf("%.2f", $rules['discount']);
            if ($v['num'] == -1) {
                $result[$k]['num'] = $this->coupon_list_table->where(array('cid' => $v['id']))->count();
            }
        }
        $num = $this->table->where($sqlmap)->count();
        $list['list'] = $result;
        $list['count'] = $num;
        if ($list === FALSE) {
            $this->error = $this->table->getError();
        }
        return $list;
    }

}
