<?php
class module_coupon_hook
{
	/**
	 * [cart_header_extra_info 购物车 优惠券领取]
	 */
	public function cart_header_extra_info() {
		$load = hd_load::getInstance();
		$carts = $load->librarys('View')->fetch('result');
		foreach ($carts['skus'][0]['sku_list'] as $k => $v) {
			$carts_goods_ids[] = $k;
		}
		$coupons = model('coupon/coupon_list','service')->goods_coupon($member_info['id'],$carts_goods_ids);//用户可以领取的优惠券
		if(!$coupons) return FALSE;
		$html = '';
		$html .= '<div class="fr margin-small-top coupon-receive">';
		$html .= '	<div class="coupon-receive-top">';
		$html .= '		<span>'.lang('receive_coupon','coupon/language').'</span>';
		$html .= '		<span class="arrow"></span>';
		$html .= '	</div>';
		$html .= '	<div class="coupon-receive-list">';
		$html .= '		<i class="arrow"></i>';
		$html .= '		<ul class="clearfix">';
		foreach ($coupons as $k => $v) {
			$html .= '<li><div class="fl margin-right amount strong">￥'.$v["discount"].'</div>';
			$html .= '<div class="fl">';
			if($v['condition'] == -1){
				$html .= '<p>'.lang('coupon','coupon/language').'，'.lang('cuts','coupon/language').$v["discount"].lang('yuan','coupon/language').'</p>';
			}else{
				$html .= '<p>'.lang('coupon','coupon/language').'，'.lang('up_to','coupon/language').$v['condition'].lang('cuts','coupon/language').$v['discount'].'</p>';
			}
			if($v['type_time'] == 1){
				$html .= '<p class="text-gray">'.date('Y.m.d',$v['start_time']).'-'.date('Y.m.d',$v['end_time']).'</p>';
			}else{
				$html .= '<p class="text-gray">'.date('Y.m.d',time()).'-'.date('Y.m.d',time()+($v['time']*60*60*24)).'</p>';
			}
			$html .= '</div><a href="javascript:;" class="fr button receive" data-id="'.$v['id'].'">领取</a></li>';	
		}
		$html .= '		</ul>';	
		$html .= '	</div>';
		$html .= '</div>';
		$html .= '<script>';
		$html .= '$(".receive").live("click",function(){';
		$html .= '	var id = $(this).data("id");';
		$html .= '	$.ajax({';
		$html .= '		url:"'.url("coupon/get_coupon/receive").'",';
		$html .= '		data:{id:id},';
		$html .= '		type:"GET",dataType:"json",';
		$html .= '		success:function(ret){';
		$html .= '			if(ret.status == 1) {';
		$html .= '				$.tips({';
		$html .= '					icon:"success",content:ret.message,';
		$html .= '					callback:function() {';
		$html .= '					}';
		$html .= '				});';
		$html .= '			} else {';
		$html .= '				$.tips({';
		$html .= '					icon:"error",content:ret.message,';
		$html .= '					callback:function() {';
		$html .= '						return FALSE;';
		$html .= '					}';
		$html .= '				});';
		$html .= '			}';
		$html .= '		return FALSE;';
		$html .= '		}';
		$html .= '	})';
		$html .= '})';
		$html .= '</script>';		
		return $html;
	}

	/**
	 * [settlement_middle_left 确认订单 优惠券选择]
	 */
	public function settlement_middle_left() {
		if(MODULE_NAME == 'order' && CONTROL_NAME == 'order' && METHOD_NAME == 'settlement'){
			$load = hd_load::getInstance();
			$carts = $load->librarys('View')->fetch('carts');
		}
		$member_info = model('member/member','service')->init();
		$coupons = model('coupon/coupon_list','service')->fetch_by_id($member_info['id'],1,$carts['skus'][0]['sku_list'],$carts['real_amount']);
		$html = '';
		$html .= '<dl class="settlement-lists coupon layout choose">';
		$html .= '	<dt>';
		$html .= '		<span class="choose-coupon text-sub"><i></i>'.lang('use_coupon','coupon/language').'</span>';
		$html .= '	</dt>';
		$html .= '	<dd class="coupon-content">';
		$html .= '	<select class="text-gray" data-model="coupon" name="coupon" data-code="">';
		$html .= '		<option value="0">'.lang('choose_coupon','coupon/language').'</option>';
		foreach ($coupons as $k => $v) {
			$html .= '<option value="'.$v["code"].'">';
			if($v['condition'] == -1){
				$html .= lang('cuts','coupon/language').$v["discount"].lang('yuan','coupon/language').'</option>';
			}else{
				$html .= lang('up_to','coupon/language').$v["condition"].lang('cuts','coupon/language').$v["discount"].'</option>';
			}
		}
		$html .= '</select></dd></dl>';
		$html .= '<script>';
		$html .= '$(function(){';
		$html .= '	$("[data-model=\'coupon\']").live("change", function(){';
		$html .= '		var coupons_sn = $("[data-model=\'coupon\'] option:selected").val();';
		$html .= '		$("[data-model=\'coupon\']").attr("data-code",coupons_sn);';
		$html .= '		$("name=coupon").attr("name",coupons_sn);';
		$html .= '		order_params.coupon = $("[data-model=\'coupon\'] option:selected").val();';
		$html .= '		hd_order._get(order_params);';
		$html .= '	});';
		$html .= '})';
		$html .= '</script>';
		return $html;	
	}

	public function settlement_down_right() {
		$html = '<p>'.lang('coupon_total','coupon/language').'：<span class="text-right text-mix" data-model="coupon_total">{$carts[coupon_total]}</span></p>';
		return $html;
	}

	/**
	 * [carts_extra 优惠券优惠总额 优惠金额]
	 * @param  [type] $carts  [订单信息]
	 */
	public function carts_extra(&$carts) {
		$coupon_total = 0;
		$code = Input::param('coupon');
		/*处理优惠券*/
		if($code){
			$coupon = model('coupon/coupon_list')->where(array('code'=>$code,'end_time'=>array('GT',time())))->find();
			$coupon['rules'] = json_decode(model('coupon/coupon')->where(array('id'=>$coupon['cid']))->getField('rules'),TRUE);
			if($coupon['rules']['type'] == "amount_discount" && $coupon['rules']['condition'] > sprintf("%.2f", max(0,$carts['sku_total'] + $carts['deliverys_total'] + $carts['invoice_tax']))){
				$this->error = lang('dissatisfy_coupon','coupon/language');
				return FALSE;
			}
			/*处理优惠券金额*/
			$coupon_total = $coupon['rules']['discount'];			
		}
		$carts['coupon_total'] = sprintf("%.2f", -$coupon_total);
		$carts['real_amount'] = sprintf("%.2f", max(0,$carts['real_amount'] + $carts['coupon_total']));
  	}

  	/**
  	 * [create_order 确认订单 优惠券绑定订单]
  	 */
  	public function create_order(&$member) {
  		$code = Input::param('coupon');
		model('coupon/coupon_list')->where(array('code'=> $code))->setField('status', 2);
		model('order/order')->where(array('sn'=> $member['order_sn']))->setField('coupon_code', $code);
	}

	/**
	 * [detail_goods_prom_info 商品详情 优惠数据]
	 */
	public function detail_goods_prom_info() {
		if(MODULE_NAME == 'goods' && CONTROL_NAME == 'index' && METHOD_NAME == 'detail'){
			$load = hd_load::getInstance();
			$goods = $load->librarys('View')->fetch('goods');
		}
		$coupons = model('coupon/coupon_list','service')->goods_coupon($member_info['id'],array($goods['sku_id']));//用户可以领取的优惠券
		if(!$coupons) return FALSE;
		$html = '';
		$html .= '<style>.detail-coupon{padding: 0 11px;}.coupon-receive{cursor: pointer;}</style>';
		$html .= '<div class="promotion-dom">';
		$html .= '	<div class="clearfix promotion-coupon">';
		$html .= '		<span class="promotion-coupon fl">&nbsp;&nbsp;&nbsp;'.lang('get','coupon/language').'&nbsp;&nbsp;&nbsp;&nbsp;'.lang('quan','coupon/language').' :</span>';
		$html .= '		<div class="fl promotion-wrap">';
		$html .= '			<ul>';
		$height = 20;
		foreach ($coupons as $k => $v) {
			$height += 20;
			$html .= '<li class="clearfix">';
			$html .= '<span class="promotion-font text-white fl detail-coupon" >'.lang('coupon','coupon/language').'</span>';
			if($v['condition'] == -1){
				$html .= '<span class="text-dot margin-left promotion-detail fl">'.lang('cuts','coupon/language').$v["discount"].lang('yuan','coupon/language');
			}else{
				$html .= '<span class="text-dot margin-left promotion-detail fl">'.lang('up_to','coupon/language').$v['condition'].lang('cuts','coupon/language').$v['discount'];
			}
			if($v['type_time'] == 1){
				$html .= '<a class="text-main margin-left coupon-receive" data-time="'.date('Y.m.d',$v['start_time']).'-'.date('Y.m.d',$v['end_time']).'" data-id="'.$v['id'].'">'.lang('receive_now','coupon/language').'</a>';
			}else{
				$html .= '<a class="text-main margin-left coupon-receive" data-time="'.date('Y.m.d',time()).'-'.date('Y.m.d',time()+($v['time']*60*60*24)).'" data-id="'.$v['id'].'">'.lang('receive_now','coupon/language').'</a>';
			}
			$html .= '</span></li>';
		}
		$html .= '			</ul>';
		$html .= '		</div>';
		$html .= '	</div>';
		$html .= '</div>';
		$html .= '<style>.promotion-dom{height: '.$height.'px !important;}</style>';
		$html .= '<script>';
		$html .= '$(".coupon-receive").live("click",function(){';
		$html .= '	var id = $(this).data("id");';
		$html .= '	$.ajax({';
		$html .= '		url:"'.url("coupon/get_coupon/receive").'",data:{id:id},';
		$html .= '		type:"GET",dataType:"json",';
		$html .= '		success:function(ret){';
		$html .= '			if(ret.status == 1) {';
		$html .= '				$.tips({';
		$html .= '					icon:"success",content:ret.message,';
		$html .= '					callback:function() {';
		$html .= '					}';
		$html .= '				});';
		$html .= '			} else {';
		$html .= '				$.tips({';
		$html .= '					icon:"error",content:ret.message,';
		$html .= '					callback:function() {';
		$html .= '						return FALSE;';
		$html .= '					}';
		$html .= '				});';
		$html .= '			}';
		$html .= '		return FALSE;';
		$html .= '		}';
		$html .= '	})';
		$html .= '})';
		$html .= '</script>';
		return $html;
	}

	/**
	 * [member_extra_lists 会员中心 我的优惠券]
	 */
	public function member_extra_lists(){
		$data = array();
	    $data['control_name'] = 'get_coupon';
	    $data['url'] = url("coupon/get_coupon/index");
	    $data['name'] = lang('my_coupon','coupon/language');
	    return $data;
	}

	/**
	 * [wap_member_index_extra_info 首页优惠券信息]
	 */
	public function wap_member_index_extra_info(){
		$data = array();
	    $data['url'] = url("coupon/get_coupon/index");
	    $data['name'] = lang('my_coupon','coupon/language');
	    $data['desc'] = lang('all_coupon','coupon/language');
	    $data['ico'] = __ROOT__.'system/module/coupon/statics/images/ico_coupon.png';
	    return $data;
	}

	/**
	 * [wap_goods_detail_extra 商品详情 优惠券信息]
	 */
	public function wap_goods_detail_extra(){
		$member_info = model('member/member','service')->init();
		if(MODULE_NAME == 'goods' && CONTROL_NAME == 'index' && METHOD_NAME == 'detail'){
			$load = hd_load::getInstance();
			$goods = $load->librarys('View')->fetch('goods');
		}
		$coupons = model('coupon/coupon_list','service')->goods_coupon($member_info['id'],array($goods['sku_id']));//用户可以领取的优惠券
		if(!$coupons){
			return FALSE;
		}
		$html = '';
		$html .= '<div class="border-bottom margin-top bg-white mui-clearfix mui-position coupin-show">';
		$html .= '	<div class="padding hd-h4 border-top mui-navigate-right mui-clearfix">';
		$html .= '	<span class="mui-pull-left margin-bottom padding-little-top"><em class="text-org">'.lang('get','coupon/language').'&nbsp;&nbsp;&nbsp;&nbsp;'.lang('quan','coupon/language').'</em>：</span>';
		foreach ($coupons as $k => $v) {
			if($k <=2){
				if($v['condition'] == -1){
					$html .= '<span class="margin-left border coupin-but hd-h6 mui-pull-left mui-block margin-bottom">'.lang('cuts','coupon/language').$v["discount"].lang('yuan','coupon/language').'</span>';
				}else{
					$html .= '<span class="margin-left border coupin-but hd-h6 mui-pull-left mui-block margin-bottom">'.lang('up_to','coupon/language').$v['condition'].lang('cuts','coupon/language').$v['discount'].'</span>';
				}
			}				
		}
		$html .= '</div></div>';
		return $html;
	}

	/**
	 * [wap_goods_detail_footer 商品详情 优惠券领取]
	 */
	public function wap_goods_detail_footer(){
		if(MODULE_NAME == 'goods' && CONTROL_NAME == 'index' && METHOD_NAME == 'detail'){
			$load = hd_load::getInstance();
			$goods = $load->librarys('View')->fetch('goods');
		}
		$coupons = model('coupon/coupon_list','service')->goods_coupon($member_info['id'],array($goods['sku_id']));//用户可以领取的优惠券
		if(!$coupons) return FALSE;
		$html = '';
		$html .= '<div class="coupin-slider-box">';
		$html .= '	<div class="coupin-slider border-top">';
		$html .= '		<div class="padding hd-h4 mui-clearfix coupin-head">';
		$html .= '			<span class="mui-pull-left">'.lang('coupon','coupon/language').'</span>';
		$html .= '			<span class="text-block mui-pull-right icon-close "></span>';
		$html .= '		</div>';
		$html .= '		<div class="padding-left padding-right padding-small-top padding-small-bottom border-top coupin-recive" style="overflow-y:scroll;">';
		foreach ($coupons as $k => $v) {
			if($k == 0){
				$html .= '<div class="border mui-clearfix">';
			}else{
				$html .= '<div class="border margin-top mui-clearfix">';
			}
			$html .= '<div class="mui-pull-left  w-90 border-right border-dashed border-pink padding-small-left padding-small-right height-layout">';
			$html .= '<span class="hd-h4 margin-small-top text-block mui-text-center">￥<strong class="hd-h3">'.$v["discount"].'</strong></span>';
			if($v['condition'] == -1){
				$html .= '<span class="hd-h6 text-block mui-text-center">'.lang('any_amount','coupon/language').'</span></div>';
			}else{
				$html .= '<span class="hd-h6 text-block mui-text-center text-ellipsis">'.lang('up_to','coupon/language').'￥'.$v['condition'].lang('can_use','coupon/language').'</span></div>';
			}
			$html .= '<div class="layout ml-90 height-layout padding-left text-position padding-small-right">';
			$html .= '<span class="text-block hd-h4 height-layout layout mr-80">';
			$html .= '<span class="text-block margin-top text-ellipsis layout">'.$v['name'].'</span>';
			if($v['type_time'] == 1){
				$html .= '<p class="hd-h6 text-ellipsis layout coupon_time">'.date('Y.m.d',$v['start_time']).'-'.date('Y.m.d',$v['end_time']).'</p></span>';
			}else{
				$html .= '<p class="hd-h6 text-ellipsis layout coupon_time">'.date('Y.m.d',time()).'-'.date('Y.m.d',time()+($v['time']*60*60*24)).'</p></span>';
			}
			$html .= '<span class="text-block height-layout btn-position">';
			if($v['status'] == 1){
				$html .= '<span class="mui-btn mui-btn-primary coupin-btn border-none" data-type="1" data-id="'.$v['id'].'">'.lang('receive','coupon/language').'</span>';
			}else{
				$html .= '<span class="mui-btn mui-btn-grey coupin-btn border-none" data-type="1" data-id="'.$v['id'].'">'.lang('received','coupon/language').'</span>';				
			}
			$html .= '</span></div>';
		}
		$html .= '</div></div></div>';
		$html .="<style>";
		$html .='.coupin-slider-box{display:block;}';
		$html .='.coupin-up{-moz-transform: translateY(-100%);-ms-transform: translateY(-100%);-o-transform: translateY(-100%);-webkit-transform: translateY(-100%);transform: translateY(-100%);-moz-transition: 0.5s all linear;-ms-transition: 0.5s all linear;-o-transition: 0.5s all linear;-webkit-transition:0.5s all linear;transition:0.5s all linear;}';
		$html .='.coupin-down{moz-transform: translateY(0);-ms-transform: translateY(100%);-o-transform: translateY(100%);-webkit-transform: translateY(100%);transform: translateY(100%);-moz-transition: 0.5s all linear;-ms-transition: 0.5s all linear;-o-transition: 0.5s all linear;-webkit-transition:0.5s all linear;transition:0.5s all linear;}';
		$html .=".coupin-recive .border{height:1rem}";
		$html .="</style>";
		$html .= '<script>';
		$html .= 'var $height=$(window).height();';
		$html .= 'var couHeight_height=$(".coupin-head").height();';
		$html .= '$(".coupin-slider-box").css("bottom","-100%");';
		$html .= '$(".coupin-recive").css("height",$height-50+"px");';
		$html .= '$(function(){';
		$html .= '	$(".coupin-show").on("tap",function(){';
		$html .= '		$(".coupin-slider-box").addClass("coupin-up").removeClass("coupin-down");';
		$html .= '	});';
		$html .= '	$(".coupin-slider .icon-close").on("tap",function(){';
		$html .= '		$(".coupin-slider-box").removeClass("coupin-up").addClass("coupin-down");';
		$html .= '	});';
		$html .= '		$(".coupin-btn").on("tap",function(){';
		$html .= '			var this_coupon = $(this);';
		$html .= '			var id = $(this).data("id");';
		$html .= '		$.ajax({';
		$html .= '			url:"'.url('coupon/get_coupon/receive').'",';
		$html .= '			data:{id:id},';
		$html .= '			type:"GET",dataType:"json",';
		$html .= '			success:function(ret){';
		$html .= '				if(ret.status == 1) {';
		$html .= '					$.tips({';
		$html .= '						icon:"success",content:ret.message,';
		$html .= '						callback:function() {';
		$html .= '						}';
		$html .= '					});';
		$html .= '				} else {';
		$html .= '					if(ret.message == "'.lang("not_login","coupon/language").'"){';
		$html .= '						$.tips({';
		$html .= '							icon:"error",content:ret.message,';
		$html .= '							callback:function() {';
		$html .= '								window.location.href=ret.referer;';
		$html .= '								return;';
		$html .= '							}';
		$html .= '						});';
		$html .= '					}else{';
		$html .= '						$.tips({';
		$html .= '							icon:"error",content:ret.message,';
		$html .= '							callback:function() {';
		$html .= '								this_coupon.removeClass("mui-btn-primary").addClass("mui-btn-grey").html("已领取");';
		$html .= '								return;';
		$html .= '							}';
		$html .= '						});';
		$html .= '					}';
		$html .= '				}';
		$html .= '				return;';
		$html .= '			}';
		$html .= '		})';
		$html .= '	})';
		$html .= '})';
		$html .= '</script>';
		return $html;
	}

	/**
	 * [wap_settlement_extra 确认订单 优惠券信息]
	 */
	public function wap_settlement_extra(){
		$params = Input::param();
		$member_info = model('member/member','service')->init();
		$carts = model('order/order','service')->create($member_info['id'],$params['skuids']);
		foreach ($carts['skus'][0]['sku_list'] as $k => $v) {
			$chat['skuids'][$k]['_promos'] = $v['_promos'];
			$chat['skuids'][$k]['prices'] = $v['prices'];
			$chat['skuids'][$k]['number'] = $v['number'];
			$chat['real_amount'] = $carts['real_amount'];
		}
		$html = '';
		$html .= '<ul class="mui-table-view layout-list-common margin-top">';
		$html .= '	<li class="mui-table-view-cell">';
		$html .= '		<a href="'.url("coupon/get_coupon/cart_coupon",$chat).'" class="mui-navigate-right">';
		$html .= '			<span class="hd-h4">'.lang('coupon','coupon/language').'</span>';
		$html .= '			<span class="mui-pull-right wap-coupon" data-show="coupon_code" data-code="0" >'.lang('use_coupon','coupon/language').'</span>';
		$html .= '		</a>';
		$html .= '	</li>';
		$html .= '</ul>';
		$html .= '<script>';
		$html .= '	function setCoupon(){';
		$html .= '		var c_key = window.localStorage.getItem("hdkey");';
		$html .= '		var c_datas = JSON.parse(window.localStorage.getItem("hddatas"));';
		$html .= '		if(c_datas[c_key].coupon){';
		$html .= '			var $coupon = c_datas[c_key].coupon;';
		$html .= '			hd_params.coupon = $coupon._coupon_code_;';
		$html .= '			if($coupon._coupon_code_){';
		$html .= '				var coupon_html = "'.lang('use_coupon','coupon/language').'";';
		$html .= '				if($coupon._coupon_condition_ == -1){';
		$html .= '					coupon_html = "'.lang('cuts_now','coupon/language').'"+$coupon._coupon_discount_;';
		$html .= '				}else{';
		$html .= '					coupon_html = "'.lang('up_to','coupon/language').'"+$coupon._coupon_condition_+"'.lang('cuts','coupon/language').'"+$coupon._coupon_discount_;';
		$html .= '				}';
		$html .= '				$(".wap-coupon").html(coupon_html);';
		$html .= '				$(".wap-coupon").attr("data-code",$coupon._coupon_code_);';
		$html .= '			}';
		$html .= '		}';
		$html .= '	}';
		$html .= '	setCoupon();';
		$html .= '</script>';
		$html .= '';
		return $html;
	}
	
	/**
	 * [diy_edit_extra diy微店 优惠券]
	 */
	public function diy_edit_extra(){
		$modules = cache('module');
		$module = array_keys($modules);
		if(in_array('coupon', $module)){ 
			$html = '';
			$html .= '<script type="text/javascript">';
			$html .= 'hd_config.coupon = {';
			$html .= '	"name": "优惠券",';
			$html .= '	"libs": {';
			$html .= '		"form": {';
			$html .= '			"label": "优惠券：", "name": "coupon", "type": "hidden", "required": "required"';
			$html .= '		},';
			$html .= '		"data": '.json_encode(cache('coupon_activity','','module')).',';
			$html .= '		"dialog": {url: "'.url("coupon/coupon/wap_coupons").'", title: "'.lang('loading','coupon/language').'"}';
			$html .= '	}';
			$html .= '};';
			$html .= 'envConfig.paths.coupon = "'.__ROOT__.'system/module/coupon/statics/js/haidao.diy.coupon";';
			$html .= '</script>';
		}
		echo $html;
	}

	public function tmpl_compile(&$template){
		preg_match_all('/diy\s+(.+)}/',$template,$arr);
		if(empty($arr[0])){
			return $template;
		}
		$compile_tmpl = '';
		$content_tmpl = '';
		foreach ($arr[0] AS $base_tpl) {
			$tpl = explode(' ',$base_tpl);
			$tmpl = $tpl[1] == 'content' ? base64_decode($tpl[2]) : json_decode(base64_decode($tpl[2]),TRUE);
			switch ($tpl[1]) {
				case 'coupon':
					$content_tmpl .= '<style>';
					$content_tmpl .= '.custom-coupon { padding: 10px; text-align: center; font-size: 0; }';
					$content_tmpl .= '.custom-coupon li { display: inline-block; margin-left: 2%; width: 32%; height: 67px; border: 1px solid #ff93b2; border-radius: 4px; background: #ffeaec; }';
					$content_tmpl .= '.custom-coupon li a { color: #fa5262 }';
					$content_tmpl .= '.custom-coupon li:nth-child(1) { margin-left: 0; }';
					$content_tmpl .= '.custom-coupon li:nth-child(2) { background: #f3ffef; border-color: #98e27f; }';
					$content_tmpl .= '.custom-coupon li:nth-child(2) a { color: #7acf8d; }';
					$content_tmpl .= '.custom-coupon li:nth-child(3) { background:#ffeae3; border-color:#ffa492; }';
					$content_tmpl .= '.custom-coupon li:nth-child(3) a { color: #ff9664; }';
					$content_tmpl .= '.custom-coupon .custom-coupon-price { height: 36px; line-height: 24px; padding-top: 12px; font-size: 24px; overflow: hidden; }';
					$content_tmpl .= '.custom-coupon .custom-coupon-price span { font-size: 16px; }';
					$content_tmpl .= '.custom-coupon .custom-coupon-desc { height: 24px; line-height: 20px; font-size: 12px; padding-top: 4px; overflow: hidden; }';
					$content_tmpl .= '</style>';
					$content_tmpl .= '<ul class="custom-coupon clearfix">';
					foreach (explode(',',$tmpl['coupon']) as $k => $v) {
						$coupon = model('coupon/coupon')->where(array('id'=>$v))->find();
						if($coupon){
							$rules = json_decode($coupon['rules'],TRUE);
							$condition = sprintf("%.2f", $rules['condition']);
							$discount = sprintf("%.2f", $rules['discount']);
							if($coupon['type_time'] == 1){
								$content_tmpl .= '<li><a href="javascript:;" data-id="'.$v.'" class="receive_coupon" data-time="'.date('Y.m.d',$coupon['start_time']).'-'.date('Y.m.d',$coupon['end_time']).'">';
							}else{
								$content_tmpl .= '<li><a href="javascript:;" data-id="'.$v.'" class="receive_coupon" data-time="'.date('Y.m.d',time()).'-'.date('Y.m.d',time()+($coupon['time']*60*60*24)).'">';
							}
							$content_tmpl .= '<div class="custom-coupon-price"><span>￥</span>'.$discount.'</div>';
							if($condition == -1){
								$content_tmpl .= '<div class="custom-coupon-desc">'.lang('any_amount','coupon/language').'</div>';
							}else{
								$content_tmpl .= '<div class="custom-coupon-desc">'.lang('up_to','coupon/language').$condition.lang('yuan','coupon/language').lang('can_use','coupon/language').'</div>';
							}
							$content_tmpl .= '</a></li>';
						}	
					}
					$content_tmpl .= '</ul>';
					$content_tmpl .= '<script type="text/javascript" src="{SKIN_PATH}statics/js/haidao.js?v={HD_VERSION}"></script>';
					$content_tmpl .= '<script>';
					$content_tmpl .= '$(".receive_coupon").on("tap",function(){';
					$content_tmpl .= '	var id = $(this).data("id");';
					$content_tmpl .= '	$.ajax({';
					$content_tmpl .= '		url:"'.url("coupon/get_coupon/receive").'",';
					$content_tmpl .= '		data:{id:id},';
					$content_tmpl .= '		type:"GET",';
					$content_tmpl .= '		dataType:"json",';
					$content_tmpl .= '		success:function(ret){if(ret.status == 1) {';
					$content_tmpl .= '			$.tips({';
					$content_tmpl .= '				icon:"success",';
					$content_tmpl .= '				content:ret.message,';
					$content_tmpl .= '				callback:function() {';
					$content_tmpl .= '				}';
					$content_tmpl .= '			});';
					$content_tmpl .= '		} else {';
					$content_tmpl .= '			$.tips({';
					$content_tmpl .= '				icon:"error",';
					$content_tmpl .= '				content:ret.message,';
					$content_tmpl .= '				callback:function() {';
					$content_tmpl .= '					return FALSE;';
					$content_tmpl .= '				}';
					$content_tmpl .= '			});';
					$content_tmpl .= '		}return FALSE;';
					$content_tmpl .= '	}})';
					$content_tmpl .= '})';
					$content_tmpl .= '</script>';
					break;
				default:
					break;
			}
		}
		$template = preg_replace('/<!--{diy coupon+(.+)}-->/',$content_tmpl,$template,1);
	}

	public function global_footer(){
	    return '<script type="text/javascript" src='.url('coupon/public/remind').' ></script>';
	}

}