<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
class admin_group_control extends init_control {
	public function _initialize() {
		parent::_initialize();
		$this->model = $this->load->table('admin_group');
		$this->service = $this->load->service('admin_group');
	}

	/* 团队角色 */
	public function index() {
		$data = $this->service->getAll();
		include $this->admin_tpl('admin_group_index');
	}
	
	/* 删除 */
	public function del() {
		if(empty($_GET['formhash']) || $_GET['formhash'] != FORMHASH) showmessage('_token_error_');
		$id = (array)$_GET['id'];
		if(in_array(1, $id))showmessage(lang('_update_admin_group_success_','admin/language'));
		$this->model->where(array('id' => array('IN', $id)))->delete();
		showmessage(lang('_del_admin_group_success_','admin/language'), url('index'), 1);
	}
	/* 添加 */
	public function add() {
		if(checksubmit('dosubmit')){
			$_GET=array_filter($_GET); 
			if (array_key_exists("rules", $_GET)) $_GET['rules'] = implode($_GET['rules'],',');
			$r = $this->service->save($_GET);
			if(!$r)showmessage($this->model->getError(), url('index'), 1);
			showmessage(lang('_update_admin_group_success_','admin/language'), url('index'), 1);
		}else{
			//节点
			$this->node = $this->load->service('node');
			$nodes = $this->node->get_checkbox_data();
			$nodes = list_to_tree($nodes);
			$this->load->librarys('View')->assign('nodes',$nodes)->display('admin_group_update');
		}
	}
	/* 编辑 */
	public function edit() {
		if (checksubmit('dosubmit')) {
			$_GET=array_filter($_GET); 
			$_GET['id'] = (int) $_GET['id'];
			if (array_key_exists("rules", $_GET)) {
				$_GET['rules'] = implode($_GET['rules'],',');
			}
			if($_GET['id'] > 1) {
				$r = $this->service->save($_GET);
				if($r === false) {
					showmessage($this->model->getError(), url('index'));
				}
			}
			showmessage(lang('_update_admin_group_success_','admin/language'), url('index'), 1);
		} else {
			//个人信息
			$data = current($this->service->getAll(array('id'=>$_GET['id'])));
			$data['rules'] = explode(',',$data['rules']);
			//节点
			$this->node = $this->load->service('node');
			$nodes = $this->node->get_checkbox_data();
			$nodes = list_to_tree($nodes);
			$this->load->librarys('View')->assign('nodes',$nodes)->assign('data',$data)->display('admin_group_update');
		}
	}


	public function ajax_status() {
		$id = $_GET['id'];
		if(empty($_GET['formhash']) || $_GET['formhash'] != FORMHASH) showmessage('_token_error_');
		if((int)$id=1)showmessage(lang('_update_admin_group_success_','admin/language'));
		if ($this->service->change_status($id)) {
			showmessage(lang('_status_success_','admin/language'), '', 1);
		} else {
			showmessage(lang('_status_error_','admin/language'), '', 0);
		}
	}

}
