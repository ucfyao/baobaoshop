<?php

include "function/function.php";//接入发送参数及公共参数

class plugin_hsb_toolbar_hook {

	public function global_footer() {
        
		$config = cache('hsb_toolbar_config', '', 'plugin');
		
		$kfs = model('hsb_toolbar_kf')->where(array('kf_status'=>1))->order('kf_sort DESC')->select();

		$html = '';
		
		$html .= '<script type="text/javascript" src="/statics/hsb_toolbar/ibar.js"></script>';
		$html .= '<link rel="stylesheet" type="text/css" href="/statics/hsb_toolbar/ibar.css" />';
		$html .= '<style>';
		$html .= '.ibar ul li a:hover, .ibar ul li.show a{background-color: '.$config['bg_color'].';}';
		$html .= '.ibar ul .btn-customer a:hover, .ibar ul .btn-customer.show a{background-color: '.$config['bg_color'].';}';
		$html .= '</style>';
		$html .= '<div class="ibar">';
		$html .= '<div class="ibar-panel">';
		$html .= '<ul class="ibar-mp-center">';
		$html .= '<li class="ibar-btn-item btn-user">';
		$html .= '<a href="'.$config['link_url'].'" target="_blank"><em></em>'.$config['link_name'].'</a>';
		$html .= '<div class="ibar-tool ibar-tooltip">'.$config['link_name'].'<i></i></div>';
		$html .= '</li>';
		$html .= '<li class="btn-customer">';
		$html .= '<a href="javascript:;">客服咨询</a>';
		$html .= '</li>';
		$html .= '</ul>';
		$html .= '<ul class="ibar-mp-buttom">';
		$html .= '<li class="ibar-btn-item btn-qrcode">';
		$html .= '<a href="javascript:;"><em></em>二维码</a>';
		$html .= '<div class="ibar-tool ibar-img">';
		if (preg_match('/(http:\/\/)|(https:\/\/)/i', $config['qr_img'])) {
		$html .= '<img src="'.$config['qr_img'].'" width="200px" height="200px">';
		}else{
		$html .= '<img src=".'.$config['qr_img'].'" width="200px" height="200px">';
		}
		$html .= '<i></i>';
		$html .= '<p>'.$config['qr_text'].'</p>';
		$html .= '</div>';
		$html .= '</li>';
		$html .= '<li class=""></li>';
		$html .= '<li>';
		$html .= '<div class="btn-gotop">';
		$html .= '<a href="javascript:;"></a>';
		$html .= '</div>';
		$html .= '</li>';
		$html .= '</ul>';
		$html .= '</div>';
		$html .= '<div class="ibar-intro">';
		$html .= '<div class="top">';
		$html .= '<span class="ibar-colse"></span>';
		$html .= '<h2 class="padding-big-top strong">在线咨询</h2>';
		$html .= '<p class="text-default strong">'.$config['ol_time'].'</p>';
		$html .= '</div>';
		$html .= '<div class="intro-box">';
		$html .= '<p class="text-default strong">'.$config['ol_text'].'</p>';
		$html .= '<ul>';
		foreach ($kfs as $k=>$v) {
		$html .= '<li>'.getkfol($v['kf_type'],$v['kf_no'],$v['kf_name']).'</li>';
		}
		$html .= '</ul>';
		$html .= '<div class="text">';
		$html .= '<p class="text-default strong">'.$config['ot_text'].'</p>';
		$html .= ''.$config['ot_cont'].'';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="bottom">';
		$html .= '<div class="time-text">';
		$html .= '<em></em>';
		$html .= '<span class="h3 strong">'.$config['tel_type'].'</span>';
		$html .= '<span>'.$config['tel_time'].'</span>';
		$html .= '</div>';
		$html .= '<div class="tel">'.$config['tel_num'].'</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '
		          <script>
		            $(window).scroll(function(){
		            var scrollTop=$(document).scrollTop();
		              if(scrollTop>0){
		                $(".ibar .btn-gotop").show();
		              }else{
		                $(".ibar .btn-gotop").hide();
		              }
		            });
		          
				     if($(".subnav").length>0){
		             var tbar = $(".subnav").offset().top;
		             $(window).scroll(scrolls);
		             scrolls();
		           
				     function scrolls() {
		                var fix = $(".subnav");
		                var sTop = $(window).scrollTop();
		                if (sTop > tbar) {
		                  $(".subnav").css({
		                  position: "fixed",
		                  top: "0"
		                  });
		                } else {
		                  $(".subnav").css({
		                  position: "relative",
		                  top: "0"
		                  });
		                }
		              }
		            }
		          </script>
		';
		
		return $html;
	}//global_footer
	
}