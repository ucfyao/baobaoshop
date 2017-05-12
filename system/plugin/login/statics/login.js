$('.third_login').bind('click',function(){
	var login_code = $(this).attr('login-code');
	$.post("{U('User/Api/third_login')}",{login_code : login_code},function(ret){
		if (ret.status != 1) {
			alert(ret.info);
		} else {
			location.href = ret.url;
		}
	},'json');
})