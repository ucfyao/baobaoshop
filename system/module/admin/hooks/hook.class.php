<?php
class module_admin_hook
{
	public function update_cache() {
		model('admin/app','service')->build_cache();
	}
	public function global_footer(){
		return '<script type="text/javascript" src="'.url('admin/plan/synchro_lists').'" ></script><script type="text/javascript" src="'.url('admin/plan/cloud_update').'" ></script>';
	}
}