<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
hd_core::load_class('init', 'admin');
class member_log_control extends init_control
{
    public function _initialize() {
        parent::_initialize();
        $this->service = $this->load->service('member_log');
        $this->model = $this->load->table('member_log');
        $this->member = $this->load->table('member/member');
    }

    public function index() {
        $sqlmap = array();
    	$sqlmap['type'] = 'money';
        if($_GET['start']) {
            $time[] = array("GT", strtotime($_GET['start']));
        }
        if($_GET['end']) {
            $time[] = array("LT", strtotime($_GET['end']));
        }
        if($time){
            $sqlmap['dateline'] = $time;
        }
        if($_GET['keywords']){
            $mid = $this->member->where(array('username' => $_GET['keywords']))->getField('id');
            if($mid > 0){
                $sqlmap['mid'] = (int)$mid;
            }else{
                showmessage('请输入正确会员名', url('index'), 1);
            }
        }
        $limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? (int) $_GET['limit'] :20;
        $lists = $this->model->where($sqlmap)->page($_GET['page'])->limit($limit)->order('dateline desc')->select();
        $count = $this->model->where($sqlmap)->count();
    	$pages = $this->admin_pages($count, $limit);
        $this->load->librarys('View')->assign('lists',$lists)->assign('pages',$pages)->display('member_log');
    }
}