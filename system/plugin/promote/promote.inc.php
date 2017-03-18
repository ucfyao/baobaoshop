<?php

$plugins = cache('plugins');
$plugins = $plugins[$_GET['mod']];
if ($_POST) {
    if (!$_POST['name'])
        showmessage(lang('not_name', 'promote#language'));
    if (!$_POST['identity']) {
        $_POST['identity'] = random_str(6);
        if (model('promote')->where(array('identity' => $_POST['identity']))->find()) {
            $_POST['identity'] = random_str(6) . rand(0, 99);
        }
    } else {
        $arr = model('promote')->where(array('identity' => $_POST['identity']))->find();
        if ($arr) {
            showmessage(lang('not_identity', 'promote#language'));
        }
    }
    model('promote')->add($_POST);
   showmessage(lang('ok_add', 'promote#language'),url("#promote"));
} else {
    $data = promote_list($_GET, $_GET['page'], $_GET['limit']);
    $lists = $data['lists'];
    $pages = promote_page($data['count'], $data['limit']);
    include(PLUGIN_PATH . PLUGIN_ID . '/template/index.php');
}

/**
 * 推广信息列表
 * @param  array $map 查询条件(默认空)
 * @param  integer $page 分页页码(默认第一页)
 * @param  integer $limit 获取条数(默认取20条)
 * @param  string $order 排序(默认主键降序)
 */
function promote_list($map = array(), $page = 1, $limit = 20, $order = 'id DESC') {
    $limit = (isset($limit) && is_numeric($limit)) ? $limit : 20;
    $list = model('promote')->where($map)->page($page)->limit($limit)->order($order)->select();
    $count = model('promote')->where($map)->count();
    return array('count' => $count, 'limit' => $limit, 'lists' => $list);
}

/**
 * 组织分页模板
 * @param $totalrow
 * @param int $pagesize
 * @param int $pagenum
 * @return string
 */
function promote_page($totalrow, $pagesize = 10, $pagenum = 5) {
    $totalPage = ceil($totalrow / $pagesize);
    $rollPage = floor($pagenum / 2);

    $StartPage = $_GET['page'] - $rollPage;
    $EndPage = $_GET['page'] + $rollPage;
    if ($StartPage < 1)
        $StartPage = 1;
    if ($EndPage < $pagenum)
        $EndPage = $pagenum;

    if ($EndPage >= $totalPage) {
        $EndPage = $totalPage;
        $StartPage = max(1, $totalPage - $pagenum + 1);
    }
    $string = '<ul class="fr">';
    $string .= '<li>共' . $totalrow . '条数据</li>';
    $string .= '<li class="spacer-gray margin-lr"></li>';
    $string .= '<li>每页显示<input class="input radius-none" type="text" name="limit" value="' . $pagesize . '"/>条</li>';
    $string .= '<li class="spacer-gray margin-left"></li>';

    /* 第一页 */
    if ($_GET['page'] > 1) {
        $string .= '<li class="start"><a href="' . page_url(array('page' => 1)) . '"></a></li>';
        $string .= '<li class="prev"><a href="' . page_url(array('page' => $_GET['page'] - 1)) . '"></a></li>';
    } else {
        $string .= '<li class="default-start"></li>';
        $string .= '<li class="default-prev"></li>';
    }
    for ($page = $StartPage; $page <= $EndPage; $page++) {
        $string .= '<li ' . (($page == $_GET['page']) ? 'class="current"' : '') . '><a href="' . page_url(array('page' => $page)) . '">' . $page . '</a></li>';
    }
    if ($_GET['page'] < $totalPage) {
        $string .= '<li class="next"><a href="' . page_url(array('page' => $_GET['page'] + 1)) . '"></a></li>';
        $string .= '<li class="end"><a href="' . page_url(array('page' => $totalPage)) . '"></a></li>';
    } else {
        $string .= '<li class="default-next"></li>';
        $string .= '<li class="default-end"></li>';
    }
    $string .= '</ul>';
    return $string;
}

/**
 *  生成指定长度的随机字符串(包含大写英文字母, 小写英文字母, 数字)
 * 
 * @author Wu Junwei <www.wujunwei.net>
 * 
 * @param int $length 需要生成的字符串的长度
 * @return string 包含 大小写英文字母 和 数字 的随机字符串
 */
function random_str($length) {
    //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
    $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

    $str = '';
    $arr_len = count($arr);
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $arr_len - 1);
        $str.=$arr[$rand];
    }

    return $str;
}
