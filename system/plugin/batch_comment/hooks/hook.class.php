<?php

class plugin_batch_comment_hook extends plugin
{
    public function pre_response()
    {
        /**
         * 渲染获取评论处
         */
        if (MODULE_NAME == 'comment' && CONTROL_NAME == 'index' && METHOD_NAME == 'ajax_comment') {
            if ($_GET['mood']) $_GET['mood'] == 'positive' ? $map['mood'] = 1 : ($_GET['mood'] == 'neutral' ? $map['mood'] = 2 : $map['mood'] = 3);
            $result = $this->load->librarys('View')->fetch('result');
            $map['delete'] = 0;
            $map['spu_id'] = $_GET['spu_id'];
            $comments = virtual_list($map);
            if (!$comments) return false;
            $result['count'] += $comments['count'];
            foreach ($comments['lists'] as $comment) {
                $item['_datetime'] = date('Y-m-d', $comment['time']);
//                $item['_username'] = $comment['name'];
                $item['avatar'] = $comment['avatar'] ?: "./uploadfile/avatar/default.png";
                $item['content'] = $comment['content'];
                $item['datetime'] = $comment['time'];
                $item['imgs'] = json_decode($comment['imgs'], true) ?: array();
                $item['is_shield'] = 1;
                $item['mood'] = $comment['mood'] == 1 ? 'positive' : ($comment['mood'] == 2 ? 'neutral' : 'negative');
                $item['sku_id'] = $comment['sku_id'];
                $item['spu_id'] = $comment['spu_id'];
                $item['username'] = cut_str($comment['name'], 1, 0) . '**' . cut_str($comment['name'], 1, -1);
                $result['lists'][] = $item;
            }
            $result['lists'] = array_orderby($result['lists'], 'datetime', SORT_DESC);
            $result['pages'] = pages($result['count'], 5);
            $this->load->librarys('View')->assign('result', $result);
        }
    }


    public function pre_output(&$data)
    {
        /**
         * 商品详情 - 编辑统计
         */
        if (MODULE_NAME == 'goods' && CONTROL_NAME == 'index' && METHOD_NAME == 'detail') {
            $map['delete'] = 0;
            $map['sku_id'] = $_GET['sku_id'];
            $data['count']['positive'] += model('virtual_comment')->where($map)->where(array('mood' => 1))->count();
            $data['count']['neutral'] += model('virtual_comment')->where($map)->where(array('mood' => 2))->count();
            $data['count']['negative'] += model('virtual_comment')->where($map)->where(array('mood' => 3))->count();
        }
    }
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
 * 多维数组排序
 * @return mixed
 */
function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}