<?php
//获取会员名称
function _getuser($userid=''){

	   $users = model('member')->where(array('id' => $userid))->getField('username', true);

       foreach ($users as $key => $v) {
        $users = $v;
       }
       return $users;     
}

//获取会员微信OPENID
function _getopenid($userid=''){

	   $users = model('member_oauth')->where(array('member_id' => $userid))->getField('openid', true);

       foreach ($users as $key => $v) {
        $users = $v;
       }
       return $users;     
}