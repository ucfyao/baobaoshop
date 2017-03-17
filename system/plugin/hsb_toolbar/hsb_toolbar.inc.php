<?php
if (!defined('IN_ADMIN')) {
    exit('Access Denied');
}
$plugins = cache('plugins');
$plugins = $plugins[$_GET['mod']];
$config = cache('hsb_toolbar_config', '', 'plugin');
$folders = glob(PLUGIN_PATH.PLUGIN_ID.'/library/driver/*');
if (IS_POST) {
    $this->attachment_service = $this->load->service('attachment/attachment');
    $this->attachment_service->setConfig(authcode(serialize(array('module'=>'common','path' => 'common','mid' => 1,'allow_exts' => array('gif','jpg','jpeg','bmp','png'))), 'ENCODE'));

			if(!empty($_FILES['qr_img']['name'])) {
				$_GET['qr_img'] = $this->attachment_service->upload('qr_img');
				if(!$_GET['qr_img']){
					showmessage($this->attachment_service->error);
				}
			}
    // 配置
    $result_add = cache('hsb_toolbar_config', $_GET, 'plugin');
	
	if (!$result_add) showmessage(lang('post_message_error', 'hsb_toolbar#language'));
	$this->attachment_service->attachment($_GET['qr_img'],'',false);
	showmessage(lang('post_message_success', 'hsb_toolbar#language'), url('admin/app/module',array('mod' => 'hsb_toolbar:hsb_toolbar')), 1);

} else {
    include(PLUGIN_PATH . PLUGIN_ID . '/template/config.php');
}