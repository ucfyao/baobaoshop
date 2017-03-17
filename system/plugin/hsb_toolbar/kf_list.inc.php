<?php
//客服列表
if(!defined('IN_ADMIN')) {
    exit('Access Denied');
}
require_cache(PLUGIN_PATH . PLUGIN_ID . '/hooks/function/function.php'); //接入公共参数

$limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? $_GET['limit'] : 20;
$count = $this->load->table('hsb_toolbar_kf')->count();
$pages = $this->admin_pages($count, $limit);

$kfs = model('hsb_toolbar_kf')->page($_GET['page'])->limit($limit)->order('kf_sort DESC')->select();

$lists = array();
foreach ($kfs as $key => $kf) {
      $lists[] = $kf;
}
	
include(PLUGIN_PATH . PLUGIN_ID . '/template/kf_list.php');