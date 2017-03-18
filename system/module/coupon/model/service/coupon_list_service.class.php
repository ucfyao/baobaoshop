<?php
class coupon_list_service extends service {
	public function _initialize() {
		$this->table = model('coupon_list');
		$this->coupon = model('coupon');
		$this->order = model('order/order');
		$this->member = model('member/member');
	}

	public function get_lists($params){
		$data['cid'] = $params['cid'];
		$result = $this->table->page($params['page'])->limit($params['limit'])->where($data)->select();
		if($result === FALSE){
			$this->error = $this->table->getError();
		}
		foreach ($result as $k => $v) {
			$result[$k]['sn'] = $this->order->where(array('coupon_code' => $v['code']))->getfield('sn');
			$result[$k]['username'] = $this->member->where(array('id' => $v['mid']))->getfield('username');
			$result[$k]['name'] = $this->coupon->where(array('id' => $v['cid']))->getfield('name');
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
	 * [fetch_by_id 查询用户拥有或可用的优惠券]
	 * @param  [type]  $mid     [会员id]
	 * @param  [type]  $end_time[结束时间]
	 * @param  integer $status  [状态]
	 * @param  [type]  $sku_list[订单中商品]
	 * @return [type]           [description]
	 */
	public function fetch_by_id($mid, $status=1, $sku_list='', $num=0) {
		$end_time = time();
		$data = array();
		$data['mid'] = $mid;
		if(in_array($status,array(1,2,3))){
			$data['status'] = $status;
			$data['end_time'] = array('GT',$end_time);
		}else if($status == 4){
			$where['status'] = 2;
			$where['end_time'] = array('LT',$end_time);
			$where['_logic'] = 'or';
			$data['_complex'] = $where;
		}
		$result = $this->table->where($data)->select();
		foreach ($result as $k => $v) {
			$coupon = model('coupon/coupon')->where(array('id' => $v['cid']))->find();
			$rules = json_decode($coupon['rules'], TRUE);
			$result[$k]['condition'] = sprintf("%.2f", $rules['condition']);
			$result[$k]['discount'] = sprintf("%.2f", $rules['discount']);
			$result[$k]['type_use'] = $coupon['type_use'];
			$result[$k]['describe'] = $coupon['describe'];
			$ids = explode(',',$coupon['sku_ids']);
			$assign_prices_total = 0;//指定商品的总价格
			$list_prices_total = 0;//原价商品的总价格
			$ids_prices_total = 0;//指定商品+仅原价的总价格
			foreach ($sku_list as $key => $value) {
				$is_ids = 0;
				$result[$k]['sku_list'] = $sku_list;
				if(in_array($key, $ids)){
					$is_ids = 1;//存在指定商品
					$assign_prices_total += $value['prices'];
				}
				if($coupon['type_buy'] == 1){//仅原价购买
					$prom_type = model('goods/goods_sku')->where(array('sku_id' => $key))->getField('prom_type');
					if($prom_type == 'goods'){
						if($value['_promos']){
							foreach ($value['_promos'] as $rule) {
								switch ($rule['type']) {
									case 'amount_discount':	// 满额立减
									case 'amount_give':		// 满额赠礼
										if($value['prices'] >= $rule['condition']){
											if($is_ids == 1) $ids_prices_total += $value['prices'];
											$is_prom = 0;
										}else{
											$list_prices_total += $value['prices'];
											$is_use = 1;
										}
										break;
									default :
										if($value['number'] >= $rule['condition']){
											if($is_ids == 1){
												$ids_prices_total += $value['prices'];
											}
											$is_prom = 0;
										}else{
											$list_prices_total += $value['prices'];
											$is_use = 1;
										}
										break;
								}
							}
						}else{
							$list_prices_total += $value['prices'];
							$is_use = 1;
						}						
					}else if($prom_type == 'time'){
						if($is_ids == 1) $ids_prices_total += $value['prices'];
						$is_prom = 0;//商品已参加其他活动
					}else{
						$list_prices_total += $value['prices'];
						$is_use = 1;//商品未参加其他活动
					}
				}else{
					$list_prices_total += $value['prices'];
				}
				if($is_ids == 1 && $is_use == 1){
					$des_list = 1;//商品满足指定商品且没有参加其他活动
				}
			}
			//$coupon['type_buy'] == 1仅原价购买
			//$coupon['type_buy'] == 0可促销价购买
			//$coupon['type_use'] == 1全店
			//$coupon['type_use'] == 2指定
			if($coupon['type_use'] == 1 && $coupon['type_buy'] == 0 && $num < $result[$k]['condition'] && $result[$k]['condition'] > 0 && $num > 0){//判断全店+可促销价是否达到金额
				unset($result[$k]);
			}
			if($coupon['type_use'] == 1 && $coupon['type_buy'] == 1 && $list_prices_total < $result[$k]['condition'] && $result[$k]['condition'] > 0 && $num > 0){//判断全店+仅原价是否达到金额
				unset($result[$k]);
			}
			if($coupon['type_use'] == 2 && $coupon['type_buy'] == 0 && $assign_prices_total < $result[$k]['condition'] && $result[$k]['condition'] > 0 && $num > 0){//判断是否达到金额
				unset($result[$k]);
			}
			if($coupon['type_use'] == 2 && $coupon['type_buy'] == 1 && ($assign_prices_total-$ids_prices_total) < $result[$k]['condition'] && $result[$k]['condition'] > 0 && $num > 0){//判断是否达到金额
				unset($result[$k]);
			}
			if($sku_list != '' && $coupon['type_use'] == 1 && $coupon['type_buy'] == 1 && $is_use != 1 && $is_prom == 0){
				unset($result[$k]);
			}
			if($sku_list != '' && $coupon['type_use'] == 2 && $coupon['type_buy'] == 1 && $des_list != 1){
				unset($result[$k]);
			}
			if($sku_list != '' && $coupon['type_use'] == 2 && $coupon['type_buy'] == 0 && $is_ids != 1){
				unset($result[$k]);
			}
		}
		return $result;
	}

	/**
	 * [delete 删除数据]
	 * @param  [type] $ids [description]
	 * @return [type]      [description]
	 */
	public function delete($ids) {
		if(empty($ids)) {
			$this->error = lang('_param_error_');
			return FALSE;
		}
		$_map = array();
		if(is_array($ids)) {
			$_map['id'] = array("IN", $ids);
		} else {
			$_map['id'] = $ids;
		}
		$result = $this->table->where($_map)->delete();
		if($result === FALSE) {
			$this->error = $this->table->getError();
			return FALSE;
		}
		return TRUE;
	}
	/**
	 * [change_sort 改变状态]
	 * @param  [array] $params [优惠券id]
	 * @return [boolean]     [返回更改结果]
	 */
	public function change_status($params){
		if((int)$params['id'] < 1){
			$this->error = lang('_PARAM_ERROR_');
			return FALSE;
		}
		$data = array();
		$data['status'] = $params['status'];
		$data['id'] = $params['id'];
		$result = $this->table->update($data);
		if(!$result){
    		$this->error = lang('_OPERATION_FAIL_');
    	}
    	return TRUE;
	}
	
	/**
	 * [create_card 生成卡号]
	 * @param  [type]  $coupon_id[优惠券活动id]
	 * @param  [type]  $num    	 [生成数量]
	 * @param  string  $length 	 [编码长度]
	 * @param  integer $status 	 [优惠券状态]
	 * @param  string  $mid    	 [用户id]
	 * @return [type]          	 [description]
	 */
	public function create_card($coupon_id,$num = 1,$length=8,$status=0,$mid){
		$activity = $this->coupon->where(array('id' => $coupon_id))->find(); //获取活动的领取类型和领取数量
		if(!$activity){
			$this->error = lang('no_coupon','coupon/language');
			return FALSE;
		}
		if($activity['num'] == 0){
			$this->error = lang('not_coupon','coupon/language');
			return FALSE;
		}
		if($activity['type_time'] == 1){//活动范围时间
			$start_time = $activity['start_time'];
			$end_time = $activity['end_time'];
		}else{//领后几日失效时间
			$start_time = time();
			$end_time = time()+($activity['time']*60*60*24);
		}
		if($mid){
			$mid_count = $this->table->where(array('mid' => $mid,'cid' => $coupon_id))->count(); //获取用户领取数量
			if($activity['receive_num'] <= $mid_count){
				$this->error = lang('receive_limit','coupon/language');
				return FALSE;
			}
		}		
		if($mid > 0 && $activity['num'] > 0){ //用户领取 对应优惠券
			$coupon = $this->table->where(array('cid' => $coupon_id,'status' => 0))->find();
			$data = array();
			$data['start_time'] = $start_time;
			$data['end_time'] = $end_time;
			$data['id'] = $coupon['id'];
			$data['mid'] = $mid;
			$data['status'] = $status;
			$this->table->update($data);
			$this->coupon->where(array('id' => $coupon_id))->setDec('num',$num);
			return TRUE;
		}else{
			//生成优惠券
			$mid = $mid ? $mid : 0;
			for ($i = 0; $i < $num; $i++){
				$charid = strtoupper(md5($mid.strtotime(now).rand(0,$num).$i)); 
				$data[$i]['code'] = substr($charid,0,$length);
				$data[$i]['cid'] = $coupon_id;
				$data[$i]['mid'] = $mid;
				$data[$i]['status'] = $status;
				if($mid > 0){
					$data[$i]['start_time'] = $start_time;
					$data[$i]['end_time'] = $end_time;
				}
	     	}
	     	$result = $this->table->addAll($data);
			if($result === FALSE){
				$this->error = $this->table->getError();
				return FALSE;
			}
			return TRUE;
		}		
	}

	/**
	*[goods_coupon 商品优惠券]
	*/
	public function goods_coupon($mid=0,$sku_ids){
		$result = $this->coupon->select();
		foreach ($result as $k => $v) {
			$rules = json_decode($v['rules'], TRUE);
			$result[$k]['discount'] = $rules['discount'];
			$result[$k]['condition'] = $rules['condition'];
			if($mid > 0){
				$data = array();
				$data['mid'] = $mid;
				$data['cid'] = $v['id'];
				$user_num = $this->table->where($data)->count();				
			}
			if($v['receive_num'] <= $user_num){
				$result[$k]['status'] = 0;
			}else{
				$result[$k]['status'] = 1;
			}		
			if($v['type_time'] == 1 && $v['end_time'] <= time()){
				unset($result[$k]);
			}
			$ids = explode(",",$v['sku_ids']);
			$is_in = 0;//存在
			$dis_in = 0;//不存在
			foreach ($sku_ids AS $sku_id) {
				if($sku_id > 0 && in_array($sku_id,$ids) && $v['type_use'] == 2){
					$is_in = 1;
				}
				if($sku_id > 0 && !in_array($sku_id,$ids) && $v['type_use'] == 2){
					$dis_in = 1;
				}
			}
			if($v['type_use'] == 2 && $is_in == 0 && $dis_in == 1){				
				unset($result[$k]);
			}
		}
		return $result;
	}

	/**
	 * [remind_coupons 优惠券提醒]
	 * @return [type] [description]
	 */
	public function remind_coupons(){
		//获取开启了提醒的活动优惠券
		$coupon = model('coupon')->where(array('remind' => 1))->select();
		foreach ($coupon as $k => $v) {
			$rules = json_decode($v['rules'], TRUE);
			$setting = cache('setting', '', 'common');
			$replace = array(
				'{name}'	=> $v['name'],
				'{discount}'=> $rules['discount'],
				'{time}' 	=> $v['time'],
				'{describe}'=> $v['describe'],
				'{site_name}'=> $setting['site_name'],
			);
			if($v['time'] == 0){
				$content = '您在'.$setting['site_name'].'的优惠券'.$v['name'].'，金额：'.$rules['discount'].'，只剩最后不到一天了。抓紧时间，用券更划算！'.$v['describe'];
			}else{
				$content = '您在'.$setting['site_name'].'的优惠券'.$v['name'].'，金额：'.$rules['discount'].'，只剩最后'.$v['time'].'天了。抓紧时间，用券更划算！'.$v['describe'];
			}			
			$template_replace = $this->template_replace();
			$format_data = array();
			foreach ($replace as $key => $value) {
				if(!empty($value)){
					$format_data[$template_replace[$key]] = $value;
				}
			}
			//提醒类型
			$type_remind = json_decode($v['type_remind'], ture);
			$coupon_id = $v['id'];
			foreach ($type_remind as $key => $value) {
				switch ($value) {
					case '邮件':
						//组装需要提醒的数据
						$email_map = array();
						$email_map['cid'] = $coupon_id;
						$email_map['end_time'] = array('between',array(1,time()+$v['remind_time']*60*60*12));
						$email_map['email_remind'] = 0;
						$remind = $this->table->where($email_map)->select();
						foreach ($remind as $key => $value) {
							$email_data['to'] = model('member')->where(array('id' => $value['mid']))->getField('email');
							$email_data['subject'] = str_replace(array_keys($replace), $replace, '{site_name}');
							$email_data['body'] = str_replace(array_keys($replace), $replace, $content);
							$email_data['body'] = str_replace('./uploadfile/','http://'.$_SERVER['HTTP_HOST'].'/uploadfile/',$email_data['body']);
							$email_params = unit::json_encode($email_data);
							$email = model('notify/queue','service')->add('email','send',$email_params,100);
							if($email == 1){
								$email_param['id'] = $value['id'];
								$email_param['end_time'] = array('between',array(1,time()+$v['remind_time']*60*60*12));
								$email_param['email_remind'] = 0;
								$this->table->where($email_param)->setField('email_remind',1);
							}
						}
						break;
					case '站内信':
						$message_map = array();
						$message_map['cid'] = $coupon_id;
						$message_map['end_time'] = array('between',array(1,time()+$v['remind_time']*60*60*12));
						$message_map['ids_remind'] = 0;
						$remind = $this->table->where($message_map)->select();
						foreach ($remind as $key => $value) {
							$message_data['mid'] = $value['mid'];
							$message_data['title'] = str_replace(array_keys($replace), $replace, '{site_name}');
							$message_data['content'] = str_replace(array_keys($replace), $replace, $content);
							$message_params = unit::json_encode($message_data);
							$message = model('notify/queue','service')->add('message','send',$message_params,100);
							if($message == 1){
								$message_param['id'] = $value['id'];
								$message_param['end_time'] = array('between',array(1,time()+$v['remind_time']*60*60*12));
								$message_param['ids_remind'] = 0;
								$this->table->where($message_param)->setField('ids_remind',1);
							}
						}
						break;
					case '微信':
						
						break;
					case '短信':
						$sms_map = array();
						$sms_map['cid'] = $coupon_id;
						$sms_map['end_time'] = array('between',array(1,time()+$v['remind_time']*60*60*12));
						$sms_map['sms_remind'] = 0;
						$remind = $this->table->where($sms_map)->select();
						foreach ($remind as $key => $value) {
							$mobile = model('member')->where(array('id' => $value['mid']))->getField('mobile');
							$sms_data['mobile'] = $mobile;
							$sms_data['tpl_id'] = 225;
							$sms_data['tpl_vars'] = $this->format_sms_data($format_data);
							$sms_params = unit::json_encode($sms_data);
							$sms = model('notify/queue','service')->add('sms','send',$sms_params,100);
							if($sms == 1){
								$sms_param['id'] = $value['id'];
								$sms_param['end_time'] = array('between',array(1,time()+$v['remind_time']*60*60*12));
								$sms_param['sms_remind'] = 0;
								$this->table->where($sms_param)->setField('sms_remind',1);
							}
						}
						break;
					default:
						break;
				}
			}
		}
	}

	/**
	 * [template_replace 替换模版内容]
	 * @return [type] [description]
	 */
	public function template_replace(){
		$replace = array(
			'{site_name}' 	=> '{商城名称}',
			'{name}' 		=> '{优惠券}',
			'{discount}' 	=> '{金额}',
			'{time}' 		=> '{天数}',
			'{describe}' 	=> '{描述}',
		);
		return $replace;
	}

	public function format_sms_data($data){
		foreach ($data as $k => $v) {
			if(preg_match('/\{(.+?)\}/', $k)){
				$_data[preg_replace('/\{(.+?)\}/','$1',$k)] = $v;
			}
		}
		return $_data;
	}

	/**
	 * [api_create_card 生成优惠券api]
	 * @param  integer $coupon_id [优惠券活动id]
	 * @param  integer $num       [生成数量]
	 * @param  integer $length    [编码长度]
	 * @return bool               [TRUE or FALSE]
	 */
	public function api_create_card($coupon_id = 0, $num = 1, $length = 8){
		if($coupon_id < 1){
			$this->error = lang('_param_error_');
			return FALSE;
		}
		
		// 获取活动信息
		$activity = $this->coupon->where(array('id' => $coupon_id))->find(); 
		if(!$activity){
			$this->error = lang('no_coupon','coupon/language');
			return FALSE;
		}

		// 生成优惠券
		$data = array();
		for ($i = 0; $i < $num; $i++){
			$charid = strtoupper(md5(strtotime(now).rand(0,$num).$i)); 
			$data[$i]['code'] = substr($charid,0,$length);
			$data[$i]['cid'] = $coupon_id;
			$data[$i]['status'] = 0;
     	}
     	$this->table->addAll($data);
		return TRUE;
	}

	/**
	 * [api_receive 领取api]
	 * @param  integer $coupon_id [优惠券活动id]
	 * @param  integer $num       [生成数量]
	 * @param  integer $mid       [会员id]
	 * @return bool               [TRUE or FALSE]
	 */
	public function api_receive($coupon_id = 0, $num = 1, $mid = 0){
		if($coupon_id < 1 || $mid < 1){
			$this->error = lang('_param_error_');
			return FALSE;
		}

		// 获取活动信息
		$activity = $this->coupon->where(array('id' => $coupon_id))->find(); 
		if(!$activity){
			$this->error = lang('no_coupon','coupon/language');
			return FALSE;
		}

		if($activity['type_time'] == 1){// 活动范围时间
			$start_time = $activity['start_time'];
			$end_time = $activity['end_time'];
		}else{// 领后几日失效时间
			$start_time = time();
			$end_time = time()+($activity['time']*60*60*24);
		}

		// 匹配优惠券
		$coupons = $this->table->where(array('cid' => $coupon_id,'status' => 0))->limit($num)->select();
		foreach ($coupons as $k => $v) {
			$data = array();
			$data['start_time'] = $start_time;
			$data['end_time'] = $end_time;
			$data['id'] = $v['id'];
			$data['mid'] = $mid;
			$data['status'] = 1;
			$this->table->update($data);
		}
		return TRUE;
	}
}
