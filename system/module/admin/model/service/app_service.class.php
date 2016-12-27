<?php
/**
 *      [HeYi] (C)2013-2099 HeYi Science and technology Yzh.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      http://www.yaozihao.cn
 *      tel:18519188969
 */
defined('MODULES_PATH') OR define('MODULES_PATH', APP_PATH.config('DEFAULT_H_LAYER').'/');
class app_service extends service {
	public $data = array();
	public $code = 200;
	public function __construct() {
		$this->api = 'market.yaozihao.cn/api.php';
        $this->app_db = $this->load->table('admin/app');
        $this->appvar_db = $this->load->table('admin/appvar');
        $this->plugin_down_path = CACHE_PATH.'plugin/';
    }
    public function lists($status = 1,$type = 'plugin'){
    	$app_path = $type == 'plugin' ? PLUGIN_PATH : MODULES_PATH;
		$app_folder = dir($app_path);
		$addons = $this->app_db->where(array('identifier' => array('like',$type.'.%')))->select();
		foreach($addons as $app) {
			$app['identifier'] = str_replace($type.'.','',$app['identifier']);
			$apps[$app['identifier']] = $app;
		}
		while($entry = $app_folder->read()) {
			if(!in_array($entry, array('.', '..')) && is_dir($app_path.$entry)) {
				$entrydir = $app_path.$entry;
				$importfile = $entrydir.'/config.xml';
				if(!file_exists($importfile)) continue;
				$importtxt = @implode('', file($importfile));
				$xmldata = xml2array($importtxt);
				if (!in_array($entry, array_keys($apps))) {
					$app = array(
						'available'   => 0,
						'name'        => $xmldata['name'],
						'identifier'  => $xmldata['identifier'],
						'description' => $xmldata['description'],
						'author'      => $xmldata['author'],
						'copyright'   => $xmldata['copyright'],
						'version' => $xmldata['version'],
					);
				} else {
					$app = $apps[$entry];
				}
				$app['new_ver'] = $xmldata['version'];				
				$apps[$entry] = $app;
			}
		}
		foreach ($apps as $key => $value) {
			switch ($status) {
				case '1':
					if(!($value['id'] > 0 && $value['available'] == 1)) unset($apps[$key]);
					break;
				case '0':
					if(!($value['id'] > 0 && $value['available'] == 0)) unset($apps[$key]);
					break;
				case '-1':
					if($value['id'] > 0) unset($apps[$key]);
					break;
				default:
					break;
			}
		}
		return $apps;
    }
    /**
     * [renew 续费]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public function renew($params){
    	$shop = $this->load->librarys('market');
    	$shop->check_sign();
    	$data = $params['data'];
    	$plugins = cache('plugin_lists');
    	if(empty($data) || !is_array($data)){
    		$this->code = -20006;
    		$this->error = '参数错误';
    		return FALSE;
    	}
    	foreach ($data AS $plugin) {
    		$plugins[$plugin['branch_id']]['start_time'] = $plugin['start_time'];
    		$plugins[$plugin['branch_id']]['end_time'] = $plugin['end_time'];
    	}
    	cache('plugin_lists',$plugins);
    	return true;
    }
    /**
     * [push_upgrade 推送更新]
     * @return [type] [description]
     */
    public function upgrade($params){
    	$shop = $this->load->librarys('market');
    	$shop->check_sign();
		$data = $params['data'];
    	if(empty($data) || !is_array($data)){
    		$this->code = -20006;
    		$this->error = '参数错误';
    		return FALSE;
    	}
    	foreach ($data AS $apps) {
    		$identifier = $apps['_main']['key'];
	    	if(!$apps['_history']){
	    		$this->code = 20013;
	    		$this->error = '当前版本已是最新！';
	    		continue;
	    	}
	    	foreach ($apps['_history'] AS $plugin) {
	    		$file = $this->plugin_down_path.random(8).'.zip';
	    		$install_data = $shop->get_plugin($apps['branch_id']);
	    		$this->downpack($file,$install_data);
		    	$expfile = $this->expfile($file,$identifier,$apps['type']);
		    	if(!$expfile){
					continue;
				}
				
				$result = $this->_upgrade($identifier,$apps['type'],$apps['branch_id']);
				if(!$result){
					continue;
				}
			}
			$version = $this->app_db->where(array('branch_id' => $apps['branch_id']))->getfield('version');
			$shop->_notify($apps['branch_id'],'update',$version);
    	}
    }
     /**
     * [get 执行插件安装流程]
     * @param  string  $identifier [description]
     * @param  boolean $is_down    [description]
     * @return [type]              [description]
     */
    public function install($params){
		if(!is_dir(CACHE_PATH.'plugin')) mkdir(CACHE_PATH.'plugin');
		$shop = $this->load->librarys('market');
    	$shop->check_sign($params);
    	$data = $params['data'];
    	foreach ($data AS $plugin) {
	    	//验证是否是推送的信息
	    	$file = $this->plugin_down_path.random(8).'.zip';
	    	if(!$file){
				$this->code = -20003;
				$this->error = '请检查目录权限';
				continue;
			}
			$identifier = $plugin['identifier'];
			if(!$identifier){
				$this->code = -20004;
				$this->error = '插件标识丢失，请联系云商团队！';
				continue;
			}
			$install_data = $shop->get_plugin($plugin['branch_id']);
			$this->downpack($file,$install_data);
			$expfile = $this->expfile($file,$identifier,$plugin['type']);
			if(!$expfile){
				continue;
			}
			
			$result = $this->_install($identifier,$plugin['type'],$plugin['branch_id']);
			if(!$result){
				continue;
			}
			$version = $this->app_db->where(array('branch_id' => $plugin['branch_id']))->getfield('version');
			$this->install_branch_cache($plugin['branch_id']);
			$shop->_notify($plugin['branch_id'],'install',$version);
    	}
		return true;
    }
    public function get_api_url(){
	    $url = $_SERVER['SCRIPT_NAME'];
	    $url = str_replace('/index.php','',$url);
	    $url = str_replace('/api/cloud.php','',$url);
	    return $url;
	}
    /**
     * [upgrade ]
     * @param  string $identifier [description]
     * @return [type]             [description]
     */
    public function upgrade_app($identifier = '',$type = 'plugin'){
    	if(!$identifier){
    		$this->error = '应用标识不能为空';
    		return FALSE;
    	}
    	$branch_id = $this->app_db->where(array('identifier' => $type.'.'.$identifier))->getfield('branch_id');
    	if(!$branch_id){
			$result = $this->_upgrade($identifier,'plugin');
			if(!$result){
				return FALSE;
			}
    	}else{
	    	$plugins = $this->get_new_plugin($branch_id);
	    	if(!$plugins){
	    		$this->error = '当前版本已是最新！';
	    		return FALSE;
	    	}
    	}
    	return TRUE;
    }
    /**
     * [uninstall 卸载]
     * @param  string $identifier [description]
     * @return [type]             [description]
     */
    public function uninstall($identifier = '',$type = 'plguin'){
		$data = $this->app_db->where(array('identifier' => $type.'.'.$identifier))->field('branch_id,version,is_system')->find();
		if($data['is_system'] == 1){
			$this->error = '内置应用不能删除';
			return FALSE;
		}
		$result = $this->_uninstall($identifier,$type);
		if(!$result){
		  	$this->error = '卸载失败';
		  	return FALSE;
		}
		if($data['branch_id'] > 0){
		  	$shop = $this->load->librarys('market');
		  	$shop->_notify($data['branch_id'],'uninstall',$data['version']);
		}
    	return TRUE;
    }
    /**
     * [downpack 下载插件]
     * @param  [type] $identifier [description]
     * @return [type]             [description]
     */
    private function downpack($file = '',$data = array()){
    	$ch = curl_init();
		//Initialize a cURL session.
		$fp = fopen($file, 'wb');
		curl_setopt($ch, CURLOPT_URL, $this->api);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_NOPROGRESS, 0);
		//curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_BUFFERSIZE, 64000);
		curl_setopt($ch, CURLOPT_POST, FALSE); // post传输数据
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);// post传输数据
		$res = curl_exec($ch);
		if (curl_errno($ch)){
			die(curl_error($ch));
		}else{
			curl_close($ch);
		}
		fclose($fp);
		return true;
	}
	/**
	 * [expfile 解压插件]
	 * @param  [type] $identifier [description]
	 * @return [type]             [description]
	 */
	private function expfile($file = '',$identifier = '',$type = 'plugin'){
		if(!$identifier){
			$this->code = -20004;
			$this->error = '应用标识不能为空';
			return false;
		}
		if(is_file($file) && file_exists($file)){
			$archive = $this->load->librarys('pclzip',$file);
			//解压
			switch ($type) {
				case 'plugin':
					$result = $archive->extract(PCLZIP_OPT_PATH, $this->plugin_down_path, PCLZIP_OPT_REPLACE_NEWER);
					break;
				case 'module':
					$result = $archive->extract(PCLZIP_OPT_PATH, $this->plugin_down_path, PCLZIP_OPT_REPLACE_NEWER);
					break;
				case 'template':
					$result = $archive->extract(PCLZIP_OPT_PATH, TPL_PATH, PCLZIP_OPT_REPLACE_NEWER);
					break;
				default:
					break;
			}
			if ($result == 0) {
				$this->code = -20008;
				$this->error = lang('admin/upload_file_no_exist');
				return false;
			} 
			@unlink($file);
			return true;
		}else{
			$this->code = -20007;
			$this->error = '插件不存在';
			return false;
		}
	}
	/**
	 * [install 安装插件]
	 * @param  [type] $identifier [description]
	 * @return [type]             [description]
	 */
	public function _install($identifier = '',$type = 'plugin',$branch_id = 0){
		$xmldata  = $this->get_xml_config($identifier,$type,$branch_id);
		$app_folder = $type == 'plugin' ? PLUGIN_PATH.$identifier : MODULES_PATH.$identifier;
		$plugin_folder = $branch_id > 0 ? $this->plugin_down_path.$identifier : $app_folder;
		if(!$xmldata) {
			$this->del_dir($branch_id,$plugin_folder);
			return false;
		}
		if(!isset($xmldata['apply_version'])){
			$this->code = -20010;
			$this->error = '插件适用版本不存在，无法安装！';
			$this->del_dir($branch_id,$plugin_folder);
			return false;
		}
		if(HD_BRANCH == 'develop' && version_compare(HD_VERSION, $xmldata['apply_version']['develop']) < 0 || HD_BRANCH == 'stable' && version_compare(HD_VERSION,$xmldata['apply_version']['stable']) < 0){
			$this->code = -20010;
			$this->error = '系统版本低于插件适用版本，无法安装';
			$this->del_dir($branch_id,$plugin_folder);
			return false;
		}
		$app_info = $this->app_db->where(array('identifier' => $type.'.'.$identifier))->find();
		if(!empty($app_info)){
			$this->code = -20012;
			$this->error = '插件已安装，请勿重复安装';
			$this->del_dir($branch_id,$plugin_folder);
			return false;
		}
		if($branch_id > 0){
			$result = rename($plugin_folder,$app_folder);
			if(!$result){
				dir::copyDir($plugin_folder,$app_folder);
				deldir($plugin_folder);
			}
		}
		$plugin_data = array(
			'available' => 0,
			'name' => $xmldata['name'],
			'identifier' => $xmldata['type'].'.'.$xmldata['identifier'],
			'description' => $xmldata['description'],
			'copyright' => $xmldata['copyright'],
			'menu' => serialize($xmldata['menu']),
			'version' => $xmldata['version'],
			'author' => $xmldata['author'],
			'sort' => $xmldata['sort'] ? $xmldata['sort'] : 100,
		);
		if($branch_id) $plugin_data['branch_id'] = $branch_id;
		/* 执行安装文件 */
		if($xmldata['installsql'] && file_exists($app_folder.'/'.$xmldata['installsql'])) {
			$sql = file_get_contents($app_folder.'/'.$xmldata['installsql']);
			if ($sql) {
				$sql = str_replace("hd_", config('DB_PREFIX'), $sql);
				error_reporting(0);
				foreach (split(";[\r\n]+", $sql) as $v) {
					@mysql_query($v);
				}
				dir::delDir(CACHE_PATH.'common/fields/');
			}
			@unlink($app_folder.'/'.$xmldata['installsql']);
		}
		if($xmldata['installfile'] && file_exists($app_folder.'/'.$xmldata['installfile'])) {
			include $app_folder.'/'.$xmldata['installfile'];
		}
		$appid = $this->app_db->update($plugin_data);
		if ($appid === FALSE) {
			$this->code = -20011;
			$this->error = $this->app_db->getError();
			return false;
		} else {			
			/* 创建插件字段 */
			$vars = array();
			foreach ($xmldata['setting'] as $v) {
				$v['appid'] = $appid;
				$vars[] = $v;
			}
			$this->appvar_db->addAll($vars);
			/* 创建后台菜单 */
			$nodes = array();
			foreach($xmldata['menu'] as $module) {
				if(is_numeric($module['type'])) {
					$nodes[] = array(
						'parent_id' => $module['type'],
						'm' => $module['module'] ? $module['module'] : '',
						'c' => $module['control'] ? $module['control'] : '',
						'a' => $module['action'] ? $module['action'] : '',
						'name' => $module['menu'],
						'sort' => $module['displayorder'],
						'url' => !$module['module'] ? $this->get_api_url().'/index.php?m=admin&c=app&a=module&mod='.$module['name'] : '',
						'appid' => $appid,
					);
					if($module['setting'] == 1){
						$data = array();
						$data['id'] = $appid;
						$data['url'] = $module['module'] ? $this->get_api_url().'/index.php?m='.$module['module'].'&c='.$module['control'].'&a='.$module['action'] : $this->get_api_url().'/index.php?m=admin&c=app&a=module&mod='.$module['name'];
						$result = $this->app_db->save($data);
					}
				}
			}
			if($nodes) $result = $this->load->table('node')->addAll($nodes);
			@unlink($app_folder.'/'.$xmldata['installfile']);
			@unlink($app_folder.'/'.$xmldata['upgradefile']);
			@unlink($app_folder.'/'.$xmldata['upgradesql']);
			@unlink($app_folder.'/config.xml');
			/* 更新缓存 */
			$this->build_cache();
			return true;
		}
	}
	/**
	 * [del_dir description]
	 * @param  integer $branch_id [description]
	 * @param  [type]  $file      [description]
	 * @return [type]             [description]
	 */
	private function del_dir($branch_id = 0,$file){
		if($branch_id > 0){
			deldir($file);
		}
		return TRUE;
	}
	/**
	 * [_upgrade 升级]
	 * @param  string  $identifier [description]
	 * @param  integer $branch_id  [description]
	 * @return [type]              [description]
	 */
	private function _upgrade($identifier = '',$type = 'plugin',$branch_id = 0){
		$xmldata  = $this->get_xml_config($identifier,$type,$branch_id);
		if(!$xmldata) {
			$this->code = -20009;
			$this->error = '没有找到配置文件';
			$this->del_dir($branch_id,$plugin_folder);
			return false;
		}
		$app_folder = $type == 'plugin' ? PLUGIN_PATH.$identifier : MODULES_PATH.$identifier;
		$plugin_folder = $branch_id > 0 ? $this->plugin_down_path.$identifier : $app_folder;
		if(!isset($xmldata['apply_version'])){
			$this->code = -20010;
			$this->error = '插件适用版本不存在，无法升级！';
			$this->del_dir($branch_id,$plugin_folder);
			return false;
		}
		if(HD_BRANCH == 'develop' && version_compare(HD_VERSION, $xmldata['apply_version']['develop']) < 0 || HD_BRANCH == 'stable' && version_compare(HD_VERSION,$xmldata['apply_version']['stable']) < 0){
			$this->code = -20010;
			$this->error = '系统版本低于插件适用版本，无法升级';
			$this->del_dir($branch_id,$plugin_folder);
			return false;
		}
		$plugin_info = $this->app_db->where(array('identifier' => $type.'.'.$identifier))->find();
		if($plugin_info['version'] >= $xmldata['version']){
			$this->code = -20013;
			$this->error = '插件已是最新';
			$this->del_dir($branch_id,$plugin_folder);
			return false;
		}
		if($branch_id > 0){
			deldir($app_folder);
			$result = rename($plugin_folder,$app_folder);
			if(!$result){
				dir::copyDir($plugin_folder,$app_folder);
				deldir($plugin_folder);
			}
		}
		$plugin_data = array(
			'name' => $xmldata['name'],
			'description' => $xmldata['description'],
			'menu' => serialize($xmldata['menu']),
			'copyright' => $xmldata['copyright'],
			'version' => $xmldata['version'],
			'author' => $xmldata['author'],
			'sort' => $xmldata['sort'] ? $xmldata['sort'] : 100,
		);
		
		if($xmldata['upgradesql'] && file_exists($app_folder.'/'.$xmldata['upgradesql'])) {
			$sql = file_get_contents($app_folder.'/'.$xmldata['upgradesql']);
			if ($sql) {
				$sql = str_replace("hd_", config('DB_PREFIX'), $sql);
				error_reporting(0);
				foreach (split(";[\r\n]+", $sql) as $v) {
					@mysql_query($v);
				}
				dir::delDir(CACHE_PATH.'common/fields/');
			}
			@unlink($app_folder.'/'.$xmldata['upgradesql']);
		}
		if($xmldata['upgradefile'] && file_exists($app_folder.'/'.$xmldata['upgradefile'])) {
			include $app_folder.'/'.$xmldata['upgradefile'];
		}
		$result = $this->app_db->where(array('identifier' => $type.'.'.$identifier))->save($plugin_data);
		if ($result === FALSE) {
			$this->code = -20011;
			$this->error = $this->app_db->getError();
			return false;
		} else {			
			/* 创建插件字段 */
			$vars = array();
			foreach ($xmldata['setting'] as $v) {
				$v['appid'] = $appid;
				$vars[] = $v;
			}
			$this->appvar_db->addAll($vars);
			/* 创建后台菜单 */
			$nodes = array();
			foreach($xmldata['menu'] as $module) {
				if(is_numeric($module['type'])) {
					$nodes[] = array(
						'parent_id' => $module['type'],
						'm' => $module['module'] ? $module['module'] : '',
						'c' => $module['control'] ? $module['control'] : '',
						'a' => $module['action'] ? $module['action'] : '',
						'name' => $module['menu'],
						'sort' => $module['displayorder'],
						'url' => !$module['module'] ? $this->get_api_url().'/index.php?m=admin&c=app&a=module&mod='.$module['name'] : '',
						'appid' => $appid,
					);
					if($module['setting'] == 1){
						$data = array();
						$data['id'] = $appid;
						$data['url'] = $module['module'] ? $this->get_api_url().'/index.php?m='.$module['module'].'&c='.$module['control'].'&a='.$module['action'] : $this->get_api_url().'/index.php?m=admin&c=app&a=module&mod='.$module['name'];
						$result = $this->app_db->save($data);
					}
				}
			}
			@unlink($app_folder.'/'.$xmldata['installfile']);
			@unlink($app_folder.'/'.$xmldata['upgradefile']);
			@unlink($app_folder.'/'.$xmldata['installsql']);
			@unlink($app_folder.'/config.xml');
			/* 更新缓存 */
			$this->build_cache();
			return true;
		}
	}
	/**
	 * [get_version 检查版本信息]
	 * @param  [type] $branch_id [description]
	 * @return [type]             [description]
	 */
	private function get_new_plugin($branch_id = 0){
		if(!$branch_id){
			$this->error = '插件不存在';
			return FALSE;
		}
		$shop = $this->load->librarys('market');
		$plugin = $shop->get_branch_upgrade((array)$branch_id);
		if($plugin['code'] != 10000){
			$this->error = '通信出错';
			return FALSE;
		}
		$version_remote = $plugin['result'];
		return $version_remote;
	}
	/**
	 * [uninstall 卸载插件]
	 * @param  string $identifier [description]
	 * @return [type]             [description]
	 */
	private function _uninstall($identifier = '',$type = 'plugin') {
		$app_folder = $type == 'plugin' ? PLUGIN_PATH.$identifier : MODULES_PATH.$identifier;
		$sqlmap = array();
		$sqlmap['identifier'] = $type.'.'.$identifier;
		$app = $this->app_db->where($sqlmap)->field('id,branch_id')->find();
		$appid = $app['id'];
		if(!$appid) {
			$this->error = '插件不存在';
			return FALSE;
		}
		$this->appvar_db->where(array('appid' => $appid))->delete();
		$this->app_db->where($sqlmap)->delete();
		/* 执行删除文件 */
		if(file_exists($app_folder.'/uninstall.sql')) {
			$sql = file_get_contents($app_folder.'/uninstall.sql');
			if ($sql) {
				$sql = str_replace("hd_", config('DB_PREFIX'), $sql);
				error_reporting(0);
				foreach (split(";[\r\n]+", $sql) as $v) {
					@mysql_query($v);
				}
			}
		}
		include $app_folder.'/uninstall.php';
		/* 卸载菜单 */
		$this->load->table('node')->where(array('appid' => $appid))->delete();
		deldir($app_folder);
		if($app['branch_id'] > 0){
			$this->uninstall_branch_cache($app['branch_id']);
		}
		$this->build_cache();
		return true;
	}

	/**
	 * [available 启用禁用插件]
	 * @param  string $identifier [description]
	 * @return [type]             [description]
	 */
	public function available($identifier = '',$type = 'plugin') {
		if(empty($identifier)) {
			$this->error = '参数错误';
			return false;
		}
		$sqlmap = array();
		$sqlmap['identifier'] = $type.'.'.$identifier;
		$_available = $this->app_db->where($sqlmap)->getField('available');
		if($_available == 1) {
			$available = 0;
			$msg = '禁用';
		}else {
			$available = 1;
			$msg = '启用';			
		}
		$result = $this->app_db->where($sqlmap)->setField('available', $available);
		if(!$result) {
			$this->error = '插件'.$msg.'失败';
			return false;
		}
		$this->build_cache();
		return true;
	}
	/**
	 * [build_cache 生成缓存]
	 * @return [type] [description]
	 */
	public function build_cache() {
		$shop = $this->load->librarys('market');
		$lists = $shop->get_branch_auth();
		$branch = $end = array();
		if($lists['lists']){
			foreach ($lists['lists'] AS $list) {
				if((TIMESTAMP > $list['start_time'] && TIMESTAMP < $list['end_time']) || $list['end_time'] == 0){
					$end[] = $list['type'].'.'.$list['_key'];
				}
			}
		}
		$sqlmap = array();
		$sqlmap['identifier'] = array('NOT IN',$end);
		$sqlmap['branch_id'] = array('NEQ',0);
		$this->app_db->where($sqlmap)->setField('available',0);
		
		$sqlmap = array();
		$sqlmap['available'] = 1;
		$app_lists = $this->app_db->where($sqlmap)->select();
		
		if($app_lists) {
			$apps = array();
			foreach ($app_lists as $app_list) {
				$plugins[$app_list['identifier']] = $app_list;
				$pluginvars[$app_list['identifier']] = $this->get_pluginvar($app_list['identifier']);
			}
		}
		cache('plugins', $plugins);
		cache('appvars', $pluginvars);
	}

	/**
	 * [get_pluginvar 获取指定插件设置]
	 * @param  [type] $pluginid [description]
	 * @return [type]           [description]
	 */
	private function get_pluginvar($identifier) {
		$sqlmap = array();
		$sqlmap['appid'] = $identifier;
		return $this->load->table('appvar')->where($sqlmap)->getfield('type, value', TRUE);
	}
	/**
	 * [get_xml_config 获取指定插件配置文件]
	 * @param  string $identifier [description]
	 * @return [type]             [description]
	 */
	private function get_xml_config($identifier = '',$type = 'plugin',$branch_id = 0) {
		if (empty($identifier)) {
			$this->error = '参数错误';
			return FALSE;
		}
		$plugin_folder = $branch_id > 0 ? $this->plugin_down_path.$identifier : ($type == 'plugin' ? PLUGIN_PATH.$identifier : MODULES_PATH.$identifier);
		if(!is_dir($plugin_folder) || !file_exists($plugin_folder)) {
			$this->error = '插件目录不存在';
			return FALSE;
		}

		$plugin_xml = $plugin_folder.'/config.xml';
		if(!file_exists($plugin_xml)) {
			$this->error = '插件配置文件丢失（'.$plugin_xml.'）';
			return FALSE;
		}
		/* 检测重复安装 */
		$importtxt = @implode('', file($plugin_xml));
		$xmldata = xml2array($importtxt);
		return $xmldata;
	}
	/**
	 * [ajax_upgrade 获取更新列表]
	 * @return [type] [description]
	 */
	public function ajax_upgrade($flag = FALSE){
		if(!cache('app_lists','','common') || (boolean)$flag == TRUE){
			$cloud = unserialize(authcode(config('__cloud__','cloud'),'DECODE'));
			$shop = $this->load->librarys('market');
			$info = $shop->get_branch($this->app_db->getfield('branch_id',TRUE));
			$plugins = array();
	    	foreach ($info['result'] AS $key => $result) {
	    		$versions = array();
		    	$plugins[$result['id']] = $result;
	    		if($result['_history']){
			    	foreach ($result['_history'] AS $_history) {
			    		$versions[] = $_history['version'];
			    	}
			    	if($result['now_version'] < max($versions)){
	    				$plugins[$result['id']]['new_version'] = max($versions);
			    	}
	    		}
	    		$plugins[$result['id']]['out_time'] = (TIMESTAMP > $result['end_time'] && $result['end_time'] > 0) ? 1 : 0;
	    		$plugins[$result['id']]['_end_time'] = $result['end_time'] > 0 ? date('Y-m-d h:i:sa',$result['end_time']) : '永久';
	    	}
	    	cache('app_lists',$plugins,'common',array('expire' => 86400));
		}
	    return cache('app_lists');
	}
	/**
	 * [synchro_lists 同步列表]
	 * @return [type] [description]
	 */
	public function synchro_lists(){
		if(!cache('branch_lock')){
			cache('branch_lock',TIMESTAMP,'common',array('expire' => 7200));
			if(!cache('branch_lists')){
				$this->biuld_branch_cache();
			}
			$shop = $this->load->librarys('market');
			$result = $shop->synchro_status(cache('branch_lists'));
			cache('branch_lists',null);
			$branches = $this->biuld_branch_cache();
			return $result;
		}
	}
	/**
	 * [get_branch_lists 生成应用列表缓存]
	 * @return [type] [description]
	 */
	private function biuld_branch_cache(){
		$branches = $this->app_db->where(array('branch_id' => array('GT',0)))->getfield('identifier,branch_id,version',TRUE);
		$result = array();
		foreach ($branches as $key => $branch) {
			$result[$branch['branch_id']]['version'] = $branch['version'];
			$result[$branch['branch_id']]['type'] = 'install';
		}
		cache('branch_lists',$result);
		return TRUE;
	}
	/**
	 * [uninstall_branch_cache 卸载缓存]
	 * @param  [type] $branch_id [description]
	 * @return [type]            [description]
	 */
	private function uninstall_branch_cache($branch_id){
		$branches = cache('branch_lists');
		if(!empty($branches) && !empty($branches[$branch_id])){
			$branches[$branch_id]['type'] = 'uninstall';
			cache('branch_lists',$branches);
		}
		return TRUE;
	} 
	/**
	 * [install_branch_cache description]
	 * @param  [type] $branch_id [description]
	 * @return [type]            [description]
	 */
	private function install_branch_cache($branch_id){
		$branches = cache('branch_lists');
		if(!empty($branches) && !empty($branches[$branch_id])){
			$branches[$branch_id]['type'] = 'install';
			cache('branch_lists',$branches);
		}
		return TRUE;
	}
	/**
	 * [get_apps description]
	 * @return [type] [description]
	 */
	public function get_apps() {
		return $this->app_db->where(array('available' => 1))->getField('identifier',TRUE);
	}
}                                              