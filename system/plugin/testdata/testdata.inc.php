<?php
if (!defined('IN_ADMIN')) {
    exit('Access Denied');
}

/**
 * 演示数据插件
 *
 * - 需要设置是否保留系统原有的信息
 * - 需要在安装的时候添加数据表属性以供辨别是否为演示信息
 * - 需要勾选设置具体所需【添加或清空】的演示信息
 *
 */

$plugins = cache('plugins');
$plugins = $plugins[$_GET['mod']];

$test_config = cache('testdata_config', '', 'plugin');
if (IS_POST) {
    set_time_limit(0);
    $config = include APP_ROOT . 'config/database.php';
    $config || showmessage(lang('config_file_not_exist','testdata#language'));

    $mysql_link = @mysql_connect($config['db_host'] . ':' . $config['db_port'], $config['db_user'], $config['db_pwd']);
    $mysql_link || showmessage(lang('databse_config_error','testdata#language'));

    if ($_GET['status'] == 'get') {
        if ($test_config['status'] == 'installed') showmessage(lang('testdata_exist','testdata#language'));

        $sql_file = PLUGIN_PATH . '/testdata/sql/testdata.sql';
        if (!file_exists($sql_file)) showmessage(lang('sqlfile_not_exist','testdata#language'));

        $sqls = parse_sql($sql_file, true, $config['db_prefix']);
        foreach ($sqls as $sql) mysql_query($sql);

        $test_config['status'] = 'installed';
        cache('testdata_config', $test_config, 'plugin');
        include APP_ROOT.'system/module/admin/model/service/cache_service.class.php';
        $cache=new cache_service();
        $cache->setting();
        $cache->module();
        $cache->plugin();
        $cache->template();
        $cache->taglib();
        $cache->field();
        $cache->temp();
        $cache->extra();
        $cache->delgoods();
        showmessage(lang('importing','testdata#language'));
    } elseif ($_GET['status'] == 'del') {
        if ($test_config['status'] == 'uninstall') showmessage(lang('testdata_not_exsit','testdata#language'));

        $sql_file = PLUGIN_PATH . PLUGIN_ID . '/sql/backup.sql';
        if (!file_exists($sql_file)) showmessage(lang('sqlfile_not_exist','testdata#language'));

        $sqls = parse_sql($sql_file, true, $config['db_prefix']);
        foreach ($sqls as $sql) mysql_query($sql);

        $test_config['status'] = 'uninstall';
        cache('testdata_config', $test_config, 'plugin');

        showmessage(lang('reseting','testdata#language'));
    } else {
        showmessage(lang('error','testdata#language'));
    }
} else {
    include(PLUGIN_PATH . PLUGIN_ID . '/template/index.php');
}

/**
 * 返回sql语句数组
 * @param $fileName
 * @param bool $status
 * @param $db_pre
 * @return array
 */
function parse_sql($fileName, $status = true, $db_pre)
{
    $lines = file($fileName);
    $lines[0] = str_replace(chr(239) . chr(187) . chr(191), "", $lines[0]);//去除BOM头
    $flage = true;
    $sqls = array();
    $sql = "";
    foreach ($lines as $line) {
        $line = trim($line);
        $char = substr($line, 0, 1);
        if ($char != '#' && strlen($line) > 0) {
            $prefix = substr($line, 0, 2);
            switch ($prefix) {
                case '/*': {
                    $flage = (substr($line, -3) == '*/;' || substr($line, -2) == '*/') ? true : false;
                    break 1;
                }
                case '--':
                    break 1;
                default : {
                    if ($flage) {
                        $sql .= $line;
                        if (substr($line, -1) == ";") {
                            $sql = str_replace('hd_', $db_pre, $sql);
                            $sqls[] = $sql;
                            $sql = "";
                        }
                    }
                    if (!$flage) $flage = (substr($line, -3) == '*/;' || substr($line, -2) == '*/') ? true : false;
                }
            }
        }
    }
    return $sqls;
}