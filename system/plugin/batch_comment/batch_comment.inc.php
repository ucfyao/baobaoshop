<?php
defined('IN_ADMIN') or die('GET OUT OF HAIDAO');
if (!IS_POST) {
    // 配置页面 - 虚拟评论列表
    if (!$_GET['action'] or $_GET['action'] == 'index' or $_GET['action'] == 'list') {
        $map['delete'] = 0;
        $result = virtual_list($map);
        $comments = $result['lists'];
        $page = virtual_page($result['count'], $result['limit']);
        include template('#list');
    }

    // 新增或编辑评论
    if ($_GET['action'] == 'edit') {
        if ($_GET['cid']) {
            $map['id'] = $_GET['cid'];
            $comment = model('virtual_comment')->where($map)->find();
            $comment['imgs'] = json_decode($comment['imgs'], true);
        }
        include template('#edit');
    }

    // 回收站
    if ($_GET['action'] == 'recycle') {
        if ($_GET['cid']) {
            $map['id'] = $_GET['cid'];
            $map['delete'] = 0;
            $map['time_up'] = time();
            $result = model('virtual_comment')->update($map);
            if (!$result) showmessage('数据库连接错误，恢复失败。');
            showmessage('评论恢复完成。', -1, 1);
        } else {
            $map['delete'] = 1;
            $result = virtual_list($map);
            $comments = $result['lists'];
            $page = virtual_page($result['count'], $result['limit']);
            include template('#recycle');
        }
    }

    // 删除虚拟评论
    if ($_GET['action'] == 'delete') {
        $map['id'] = $_GET['cid'];
        $map['delete'] = 1;
        $map['time_del'] = time();
        $result = model('virtual_comment')->update($map);
        if (!$result) showmessage('数据库连接错误，删除失败。');
        showmessage('评论删除完成。', -1, 1);
    }

    // 销毁虚拟评论
    if ($_GET['action'] == 'destroy') {
        if (!$_GET['cid']) showmessage('未指定销毁评论。');
        $map['id'] = $_GET['cid'];
        $result = model('virtual_comment')->where($map)->delete();
        if (!$result) showmessage('数据库连接错误，销毁失败。');
        showmessage('评论销毁完成。', -1, 1);
    }
} else {

    // 插件配置提交
    if ($_GET['action'] == 'setting') {

    }

    // 删除虚拟评论
    if ($_GET['action'] == 'delete') {
        if (!$_GET['cid']) showmessage('未指定要删除的评论');
        if ($_GET['cid'][0] == 'on') array_shift($_GET['cid']);
        $ids = $_GET['cid'];
        $map['id'] = array('in', $ids);
        model('virtual_comment')->where($map)->setField('time_del', time());
        $result = model('virtual_comment')->where($map)->setField('delete', 1);
        if (!$result) showmessage('数据库连接错误，删除失败。');
        showmessage('评论删除完成。', -1, 1);
    }

    // 新增或编辑评论提交
    if ($_GET['action'] == 'edit') {
        $comment = valid_comment($_GET, $_FILES);
        $result = model('virtual_comment')->update($comment);
        if (!$result) showmessage('数据库连接错误，评论添加失败。');
        if ($comment['id'] > 0) showmessage('评论编辑成功。', url('admin/app/module', array('mod' => 'batch_comment', 'action' => 'list')), 1);
        showmessage('评论添加成功。', url('admin/app/module', array('mod' => 'batch_comment', 'action' => 'list')), 1);
    }

    // 销毁虚拟评论
    if ($_GET['action'] == 'destroy') {
        if (!$_GET['cid']) showmessage('未指定要销毁的评论');
        if ($_GET['cid'][0] == 'on') array_shift($_GET['cid']);
        $ids = $_GET['cid'];
        $map['id'] = array('in', $ids);
        $result = model('virtual_comment')->where($map)->delete();
        if (!$result) showmessage('数据库连接错误，销毁失败。');
        showmessage('评论销毁完成。', -1, 1);
    }

    // 恢复虚拟评论
    if ($_GET['action'] == 'recycle') {
        if (!$_GET['cid']) showmessage('未指定要恢复的评论');
        if ($_GET['cid'][0] == 'on') array_shift($_GET['cid']);
        $ids = $_GET['cid'];
        $map['id'] = array('in', $ids);
        $result = model('virtual_comment')->where($map)->setField('delete', 0);
        if (!$result) showmessage('数据库连接错误，恢复失败。');
        showmessage('评论恢复完成。', -1, 1);
    }
}

/**
 * 验证评论合法
 * @param $params
 * @param $files
 * @return mixed
 */
function valid_comment($params, $files)
{
    if (!is_numeric($params['sku_id'])) showmessage('评论商品信息错误或未选择。');
    if (!is_numeric($params['mood']) or $params['mood'] > 3 or $params['mood'] < 1) showmessage('评论星级未选择或信息格式错误。');
    if (empty($params['name'])) showmessage('评论会员名称未填写。');
    if (empty($params['content'])) showmessage('评论内容未填写。');
    if ($params['cid']) {
        $comment['id'] = $params['cid'];
        $comment['time_up'] = time();
    } else {
        $comment['time_in'] = time();
    }
    $virtual_dir = APP_ROOT . 'uploadfile/virtual';
    if (!is_dir($virtual_dir)) mkdir($virtual_dir);

    if ($files["avatar"]["error"] === 0) {
        $avatar_dir = APP_ROOT . 'uploadfile/virtual/avatar';
        if (!is_dir($avatar_dir)) mkdir($avatar_dir);
        $tmp_name = $files["avatar"]["tmp_name"];
        $name = uniqid() . $files["avatar"]["name"];
        if (move_uploaded_file($tmp_name, "$avatar_dir/$name")) {
            $comment['avatar'] = './uploadfile/virtual/avatar/' . $name;
        }
    }

    $imgs_dir = APP_ROOT . 'uploadfile/virtual/imgs';
    if (!is_dir($imgs_dir)) mkdir($imgs_dir);
    foreach ($files["imgs"]["error"] as $key => $error) {
        if ($error === 0) {
            $tmp_name = $files["imgs"]["tmp_name"][$key];
            $name = uniqid() . $files["imgs"]["name"][$key];
            if (move_uploaded_file($tmp_name, "$imgs_dir/$name")) {
                $imgs[] = './uploadfile/virtual/imgs/' . $name;
            }
        }
    }
    if ($imgs) $comment['imgs'] = json_encode($imgs);

    $comment['sku_id'] = $params['sku_id'];
    $map['sku_id'] = $params['sku_id'];
    $sku = model('goods_sku')->where($map)->find();
    $comment['spu_id'] = $sku['spu_id'];
    $comment['sku_name'] = $sku['sku_name'];
    $comment['name'] = $params['name'];
    $comment['time'] = strtotime($params['time']);
    $comment['content'] = trim($params['content']);
    $comment['mood'] = $params['mood'];
    return $comment;
}

/**
 * 虚拟评论列表
 * @param  array $map 查询条件(默认空)
 * @param  integer $page 分页页码(默认第一页)
 * @param  integer $limit 获取条数(默认取20条)
 * @param  string $order 排序(默认主键降序)
 */
function virtual_list($map = array(), $page = 1, $limit = 20, $order = 'id DESC')
{
    $limit = (isset($limit) && is_numeric($limit)) ? $limit : 20;
    $list = model('virtual_comment')->where($map)->page($page)->limit($limit)->order($order)->select();
    $count = model('virtual_comment')->where($map)->count();
    return array('count' => $count, 'limit' => $limit, 'lists' => $list);
}

/**
 * 组织分页模板
 * @param $totalrow
 * @param int $pagesize
 * @param int $pagenum
 * @return string
 */
function virtual_page($totalrow, $pagesize = 10, $pagenum = 5)
{
    $totalPage = ceil($totalrow / $pagesize);
    $rollPage = floor($pagenum / 2);

    $StartPage = $_GET['page'] - $rollPage;
    $EndPage = $_GET['page'] + $rollPage;
    if ($StartPage < 1) $StartPage = 1;
    if ($EndPage < $pagenum) $EndPage = $pagenum;

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