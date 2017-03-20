<?php
if (!defined('IN_ADMIN')) exit('Access Denied');
if (!IS_POST) {

    // 配置中心
    if (!$_GET['type']) {
        include(PLUGIN_PATH . PLUGIN_ID . '/template/index.php');
    }

    // 匹配商品图片
    if ($_GET['type'] == 'img_goods') {
        include(PLUGIN_PATH . PLUGIN_ID . '/haidao_zip.php');
        validate_zip('goods');
        mate_goods('haidao_goods');
        showmessage('商品图片匹配完毕。');
    }

    // 匹配品牌图片
    if ($_GET['type'] == 'img_brand') {
        include(PLUGIN_PATH . PLUGIN_ID . '/haidao_zip.php');
        validate_zip('brand');
        mate_brand('haidao_brand');
        showmessage('品牌图片匹配完毕。');
    }


} else {
    check_upload();
    $taobao_data = validate_file($_FILES['taobao_file'], 'taobao');
    $goods_data = validate_file($_FILES['goods_file'], 'goods');
    $msg = '';

    if ($taobao_data) {
        import_taobao_goods($taobao_data);
        $msg .= ' 淘宝商品 ';
    }

    if ($goods_data) {
        import_category($goods_data);
        import_brand($goods_data);
        import_goods($goods_data);
        $msg .= ' 商品数据 ';
    }

    showmessage($msg . '导入成功。');
}

/**
 * 验证是否上传任意文件。
 */
function check_upload()
{
    $goods_size = $_FILES['goods_file']['size'];
    $taobao_size = $_FILES['taobao_file']['size'];
    if ($taobao_size + $goods_size < 1) showmessage('没有上传任何数据文件。');
}

/**
 * 验证数据文件合法性
 * - 格式限制为csv
 * - 大小限制在2m以内
 * - 数据编码限制为utf-8
 */
function validate_file($file, $type)
{

    if ($file['size'] < 1) return false;
    $file_name = $file['name'];
    $file_path = $file['tmp_name'];
    if (end(explode('.', $file_name)) != 'csv') showmessage('数据文件必须是csv格式。');
    if (filesize($file_path) >= 2024000) showmessage('数据文件大小必须保持在2m以内。');
    if (($handle = fopen($file_path, "r")) == FALSE) showmessage('数据文件打开失败，请检查文件有效性。');
    $data = get_handle_data($handle, $type);
    fclose($handle);
    return $data;
}


/**
 * 获取结果集数据 返回数组
 * @param $handle
 */
function get_handle_data($handle, $type)
{
    $out = array();
    $n = 0;
    $spe = ",";
    if ($type == 'taobao') $spe = "	";
    while ($data = fgetcsv($handle, 90000, $spe)) {
        $num = count($data);
        for ($i = 0; $i < $num; $i++) {
            $field_name = get_field_name($i, $type);
            $out[$n][$field_name] = convert_utf8($data[$i]);
        }
        $n++;
    }
    unset($out[0]);
    if ($type == 'taobao') {
        unset($out[1]);
        unset($out[2]);
    }
    return $out;
}

/**
 * 导入淘宝商品数据
 * @param $data
 */
function import_taobao_goods($data)
{
    $map1['name'] = '淘宝商品';
    $cid = model('goods_category')->where($map1)->getField('id');
    if (!$cid) $cid = model('goods_category')->add(array('name' => '淘宝商品'));
    foreach ($data as $k => $v) {
        if (!$v['title']) continue;
        $thumb = explode('|', $v['picture']);
        $thumb = str_replace(';', '', $thumb[1]);
        $spu['name'] = $v['title'];
        $spu['catid'] = $cid;
        $spu['sn'] = 'NU' . uniqid() . random(2, 99);
        $spu['status'] = 1;
        $spu['thumb'] = $thumb;
        $spu['sku_total'] = $v['num'] ?: 99;
        $spu['content'] = $v['description'] ?: '';
        $spu['imgs'] = json_encode($thumb);
        $spu_id = model('goods_spu')->add($spu);

        $sku['spu_id'] = $spu_id;
        $sku['sn'] = $spu['sn'] . '-1';
        $sku['sku_name'] = $v['title'];
        $sku['shop_price'] = $v['price'];
        $sku['market_price'] = $v['price'] * 1.2;
        $sku['number'] = $v['num'] ?: 99;
        $sku['content'] = $v['description'] ?: '';
        $sku['status'] = 1;
        $sku['thumb'] = $thumb;
        $sku['imgs'] = json_encode($thumb);
        model('goods_sku')->add($sku);
    }
}

/**
 * 导入商品数据 2.0
 * @param $data
 */
function import_goods($data)
{
    import_spu($data);
    import_sku($data);
}

/**
 * 导入商品spu数据 2.0
 * @param $data
 */
function import_spu($data)
{
    foreach (assoc_unique($data, 'spu_name') as $k => $v) {
        if (!$v['category_name']) continue;
        $map3['name'] = $v['spu_name'];
        if (model('goods_spu')->where($map3)->getField('id')) continue;

        $spu['catid'] = get_cate_id($v['category_name']);
        if (!$spu['catid']) continue;
        if ($v['brand_name']) {
            $brand_map['name'] = $v['brand_name'];
            $spu['brand_id'] = model('brand')->where($brand_map)->getField('id') ?: 0;
        }
        $spu['sn'] = 'NU' . uniqid();
        $spu['status'] = 1;
        $spu['sku_total'] = 100;
        $spu['name'] = $v['spu_name'];
        $spu_list[] = $spu;
    }
    model('goods_spu')->addAll($spu_list);
}

/**
 * 导入商品sku数据
 * @param $data
 * @return mixed
 */
function import_sku($data)
{

    foreach (assoc_unique($data, 'sku_name') as $k => $v) {

        $spuid_map['name'] = $v['spu_name'];
        $sku['spu_id'] = model('goods_spu')->where($spuid_map)->getField('id');
        if (!$sku['spu_id']) continue;
        $sku['sn'] = 'NU' . uniqid();
        $sku['status'] = 1;
        $sku['thumb'] = $v['sku_thumb'] ?: '';
        $sku['imgs'] = $v['sku_imgs'] ? json_encode(explode(',', $v['sku_imgs'])) : '';
        $sku['sku_name'] = $v['sku_name'];
        $sku['shop_price'] = $v['sku_shop_price'];
        $sku['market_price'] = $v['sku_market_price'] ?: $v['sku_shop_price'] * 1.2;
        $sku['subtitle'] = $v['sku_subtitle'] ?: '';
        $sku['style'] = get_title_color($v['sku_title_color']) ?: '';
        $sku['number'] = $v['sku_number'] ?: 100;
        $sku['keyword'] = $v['sku_keywords'] ?: '';
        $sku['description'] = $v['sku_description'] ?: '';
        $sku['warn_number'] = $v['sku_warn'] ?: 5;
        $sku['min_distribution_price'] = $v['sku_min_dis_price'] ?: $v['sku_shop_price'];
        $sku['max_distribution_price'] = $v['sku_max_dis_price'] ?: $v['sku_shop_price'] * 2;
        $sku['took_price'] = $v['sku_took_price'] ?: $v['sku_shop_price'];
        $sku_list[] = $sku;
    }
    model('goods_sku')->addAll($sku_list);
}

/**
 * 导入分类数据 2.0
 * @param $data
 */
function import_category($data)
{
    foreach ($data as $v) $categoies[] = $v['category_name'];

    if (!$categoies) return false;

    foreach (array_unique($categoies) as $v) {
        $cate = explode('>', $v);
        if ($cate[0]) {
            $fmap['name'] = $cate[0];
            $fid = model('goods_category')->where($fmap)->getField('id');
            if (!$fid) {
                $f_cate['name'] = $cate[0];
                $f_cate['status'] = 1;
                $fid = model('goods_category')->add($f_cate);
            }

            if ($fid && $cate[1]) {
                $smap['name'] = $cate[1];
                $smap['parent_id'] = $fid;
                $sid = model('goods_category')->where($smap)->getField('id');
                if (!$sid) {
                    $s_cate['name'] = $cate[1];
                    $s_cate['parent_id'] = $fid;
                    $s_cate['status'] = 1;
                    $sid = model('goods_category')->add($s_cate);
                }

                if ($sid && $cate[2]) {
                    $thmap['name'] = $cate[2];
                    $thmap['parent_id'] = $sid;
                    $thid = model('goods_category')->where($thmap)->getField('id');
                    if (!$thid) {
                        $th_cate['name'] = $cate[2];
                        $th_cate['parent_id'] = $sid;
                        $th_cate['status'] = 1;
                        model('goods_category')->add($th_cate);
                    }
                }
            }
        }
    }
    cache('goods_category', NULL);
}

/**
 * 导入品牌数据 2.0
 * @param $data
 */
function import_brand($data)
{
    foreach ($data as $v) $brands[] = $v['brand_name'];

    if (!$brands) return false;

    foreach (array_unique($brands) as $v) {
        $map['name'] = $v;
        if (!model('brand')->where($map)->getField('id')) {
            $brand['name'] = $v;
            $brand['status'] = 1;
            $brand['isrecommend'] = 1;
            model('brand')->add($brand);
        }
    }
}


/**
 * 针对类型获取字段标识名称
 * @param $num
 * @param $type
 */
function get_field_name($num, $type)
{
    $goods = array(
        0 => 'category_name',
        1 => 'spu_name',
        2 => 'sku_name',
        3 => 'sku_shop_price',
        4 => 'brand_name',
        5 => 'sku_thumb',
        6 => 'sku_imgs',
        7 => 'sku_subtitle',
        8 => 'sku_title_color',
        9 => 'sku_number',
        10 => 'sku_market_price',
        11 => 'sku_keywords',
        12 => 'sku_description',
        13 => 'sku_warn',
        14 => 'sku_min_dis_price',
        15 => 'sku_max_dis_price',
        16 => 'sku_took_price',
    );

    $category = array(
        0 => 'name',
        1 => 'parent_name',
        2 => 'keywords',
        3 => 'description',
    );

    $brand = array(
        0 => 'name',
        1 => 'description',
    );

    $taobao = array(
        0 => 'title',//宝贝名称
        1 => 'cid',//宝贝类目
        2 => 'seller_cids',//店铺类目
        3 => 'stuff_status',//新旧程度
        4 => 'location_state',//省
        5 => 'location_city',//城市
        6 => 'item_type',//出售方式
        7 => 'price',//宝贝价格
        8 => 'auction_increment',//加价幅度
        9 => 'num',//宝贝数量
        10 => 'valid_thru',//有效期
        11 => 'freight_payer',//运费承担
        12 => 'post_fee',//平邮
        13 => 'ems_fee',//EMS
        14 => 'express_fee',//快递
        15 => 'has_invoice',//发票
        16 => 'has_warranty',//保修
        17 => 'approve_status',//放入仓库
        18 => 'has_showcase',//橱窗推荐
        19 => 'list_time',//开始时间
        20 => 'description',//宝贝描述
        21 => 'cateProps',//宝贝属性
        22 => 'postage_id',//邮费模版ID
        23 => 'has_discount',//会员打折
        24 => 'modified',//修改时间
        25 => 'upload_fail_msg',//上传状态
        26 => 'picture_status',//图片状态
        27 => 'auction_point',//返点比例
        28 => 'picture',//新图片
        29 => 'video',//视频
        30 => 'skuProps',//销售属性组合
        31 => 'inputPids',//用户输入ID串
        32 => 'inputValues',//用户输入名-值对
        33 => 'outer_id',//商家编码
        34 => 'propAlias',//销售属性别名
        35 => 'auto_fill',//代充类型
        36 => 'num_id',//数字ID
        37 => 'local_cid',//本地ID
        38 => 'navigation_type',//宝贝分类
        39 => 'user_name',//用户名称
        40 => 'syncStatus',//宝贝状态
        41 => 'is_lighting_consigment',//闪电发货
        42 => 'is_xinpin',//新品
        43 => 'foodparame',//食品专项
        44 => 'features',//尺码库
        45 => 'buyareatype',//采购地
        46 => 'global_stock_type',//库存类型
        47 => 'global_stock_country',//国家地区
        48 => 'sub_stock_type',//库存计数
        49 => 'item_size',//物流体积
        50 => 'item_weight',//物流重量
        51 => 'sell_promise',//退换货承诺
        52 => 'custom_design_flag',//定制工具
        53 => 'wireless_desc',//无线详情
        54 => 'barcode',//商品条形码
        55 => 'sku_barcode',//sku 条形码
        56 => 'newprepay',//7天退货
        57 => 'subtitle',//宝贝卖点
        58 => 'cpv_memo',//属性值备注
        59 => 'input_custom_cpv',//自定义属性值
        60 => 'qualification',//商品资质
        61 => 'add_qualification',//增加商品资质
        62 => 'o2o_bind_service',//关联线下服务
    );

    switch ($type) {
        case 'goods':
            return $goods[$num];
            break;
        case 'category':
            return $category[$num];
            break;
        case 'brand':
            return $brand[$num];
            break;
        case 'taobao':
            return $taobao[$num];
        default:
            showmessage('文件类型设置错误。');
    }
}

/**
 * 返回标题颜色
 * @param $str
 */
function get_title_color($str)
{
    $data = array(
        '红色' => '#e23a3d',
        '黄色' => '#ff5a00',
        '蓝色' => '#1380cb',
    );
    return $data[$str];
}

/**
 * 编码转换utf8
 * @param $str
 * @return string
 */
function convert_utf8($str)
{
    if (!mb_detect_encoding($str, 'UTF-8', true)) {
        return trim(iconv('GBK', 'UTF-8', $str));
    }
    return trim($str);
}

function convert_gbk($str)
{
    return trim(iconv('utf-8', 'gbk', $str));
}


/**
 * 验证压缩文件是否合法
 * @param $type
 */
function validate_zip($type)
{
    $file_name = 'haidao_' . $type . '.zip';
    if (!file_exists($file_name)) showmessage('压缩文件不存在哦，核对是否已经上传。');
    $zip = new Hzip();
    $res = $zip->unzip("haidao_{$type}.zip", '', false, true);
    if (!$res) showmessage('压缩文件解压失败，请检查是否含有密码或已经损坏导致无法解压。');
}

/**
 * 匹配品牌图片
 * @param $dir
 */
function mate_brand($dir)
{
    $brand_names = i_scandir($dir);
    if (!$brand_names) return false;
    mkdir_r(convert_gbk("uploadfile/brand"));
    foreach ($brand_names as $brand_name) {
        $name_arr = explode('.', $brand_name);
        $name = $name_arr[0];
        $map['name'] = $name;
        $id = model('brand')->where($map)->getField('id');
        if (!$id) continue; // 品牌不存在
        $o_file = convert_gbk("haidao_brand/{$brand_name}");
        $n_file = convert_gbk("uploadfile/brand/{$id}.{$name_arr[1]}");
        if (!rename($o_file, $n_file)) return false;
        $item['id'] = $id;
        $item['logo'] = "uploadfile/brand/{$id}.png";
        model('brand')->update($item);
    }
    unlink('haidao_brand.zip');
    rrmdir('haidao_brand');
}

/**
 * 匹配商品图片
 */
function mate_goods($dir)
{
    $goods_dirs = i_scandir($dir);
    if (!in_array('spu', $goods_dirs)) showmessage('商品图片文件中，spu文件夹是必须的。');
    mate_spu('haidao_goods/spu');
    mate_sku('haidao_goods/sku');
    unlink('haidao_goods.zip');
    rrmdir('haidao_goods');
}

/**
 * 匹配spu商品图片
 * @param $dir
 */
function mate_spu($dir)
{
    $spu_names = i_scandir($dir);
    if (!$spu_names) return false;

    foreach ($spu_names as $spu_name) {
        $map['name'] = $spu_name;
        $id = model('goods_spu')->where($map)->getField('id');

        if (!$id) continue; // 商品不存在
        $img_names = i_scandir("haidao_goods/spu/{$spu_name}");

        if (!$img_names) continue; // 图片不存在
        foreach ($img_names as $img_name) {
            if ($img_name == '相册') mate_spu_imgs("haidao_goods/spu/{$spu_name}/{$img_name}", $id);
            if ($img_name == '封面') mate_spu_thumb("haidao_goods/spu/{$spu_name}/{$img_name}", $id);
            if ($img_name == '内容') mate_spu_content("haidao_goods/spu/{$spu_name}/{$img_name}", $id);
        }
    }
}

/**
 * 匹配sku商品图片
 * @param $type
 */
function mate_sku($dir)
{
    $sku_names = i_scandir($dir);
    if (!$sku_names) return false;

    foreach ($sku_names as $sku_name) {

        $map['sku_name'] = $sku_name;
        $sku_id = model('goods_sku')->where($map)->getField('sku_id');
        if (!$sku_id) continue; // 商品不存在

        $img_names = i_scandir("haidao_goods/sku/{$sku_name}");
        if (!$img_names) continue; // 图片不存在

        foreach ($img_names as $img_name) {
            if ($img_name == '相册') mate_sku_imgs("haidao_goods/sku/{$sku_name}/{$img_name}", $sku_id);
            if ($img_name == '封面') mate_sku_thumb("haidao_goods/sku/{$sku_name}/{$img_name}", $sku_id);
            if ($img_name == '内容') mate_sku_content("haidao_goods/sku/{$sku_name}/{$img_name}", $sku_id);
        }
    }
}

/**
 *  匹配sku相册
 */
function mate_sku_imgs($dir, $id)
{
    mkdir_r(convert_gbk("uploadfile/goods/sku/{$id}"));
    $o_dir = convert_gbk($dir);
    $n_dir = convert_gbk("uploadfile/goods/sku/{$id}/imgs");

    if (!rename($o_dir, $n_dir)) return false;
    $imgs = i_scandir($n_dir);
    $arr_img = array();
    foreach ($imgs as $k => $img) {
        if (!check_img_type($img)) continue;
        $arr_img[] = "uploadfile/goods/sku/{$id}/imgs/{$img}";
    }
    if (!$arr_img) return false;
    $map1['sku_id'] = $id;
    $map1['imgs'] = json_encode($arr_img);
    model('goods_sku')->update($map1);
}

/**
 *  匹配spu相册
 */
function mate_spu_imgs($dir, $id)
{
    mkdir_r(convert_gbk("uploadfile/goods/spu/{$id}"));
    $o_dir = convert_gbk($dir);
    $n_dir = convert_gbk("uploadfile/goods/spu/{$id}/imgs");

    if (!rename($o_dir, $n_dir)) return false;
    $imgs = i_scandir($n_dir);
    $arr_img = array();
    foreach ($imgs as $k => $img) {
        if (!check_img_type($img)) continue;
        $arr_img[] = "uploadfile/goods/spu/{$id}/imgs/{$img}";
    }
    if (!$arr_img) return false;
    $map1['id'] = $id;
    $map1['imgs'] = json_encode($arr_img);
    model('goods_spu')->update($map1);
}

/**
 * 匹配sku封面
 */
function mate_sku_thumb($dir, $id)
{
    mkdir_r(convert_gbk("uploadfile/goods/sku/{$id}"));
    $o_dir = convert_gbk($dir);
    $n_dir = convert_gbk("uploadfile/goods/sku/{$id}/thumb");

    if (!rename($o_dir, $n_dir)) return false;

    $img = i_scandir($n_dir);
    if (!$img) return false;
    $str = "uploadfile/goods/sku/{$id}/thumb/{$img[2]}";
    $map1['sku_id'] = $id;
    $map1['thumb'] = $str;
    model('goods_sku')->update($map1);
}

/**
 * 匹配spu封面
 */
function mate_spu_thumb($dir, $id)
{
    mkdir_r(convert_gbk("uploadfile/goods/spu/{$id}"));
    $o_dir = convert_gbk($dir);
    $n_dir = convert_gbk("uploadfile/goods/spu/{$id}/thumb");
    if (!rename($o_dir, $n_dir)) return false;

    $img = i_scandir($n_dir);

    if (!$img) return false;
    $str = "uploadfile/goods/spu/{$id}/thumb/{$img[2]}";
    $map1['id'] = $id;
    $map1['thumb'] = $str;
    model('goods_spu')->update($str);
}

/**
 * 匹配sku内容
 */
function mate_sku_content($dir, $id)
{
    mkdir_r(convert_gbk("uploadfile/goods/sku/{$id}"));
    $o_dir = convert_gbk($dir);
    $n_dir = convert_gbk("uploadfile/goods/sku/{$id}/content");
    if (!rename($o_dir, $n_dir)) return false;

    $imgs = i_scandir($n_dir);
    $str = '';
    foreach ($imgs as $k => $img) {
        if (!check_img_type($img)) continue;
        $str .= "<p style='text-align: center;'><img src='uploadfile/goods/sku/{$id}/content/{$img}'/></p>";
    }
    if (!$str) return false;
    $map1['sku_id'] = $id;
    $map1['content'] = $str;
    model('goods_sku')->update($map1);
}


/**
 * 匹配spu内容
 */
function mate_spu_content($dir, $id)
{
    mkdir_r(convert_gbk("uploadfile/goods/spu/{$id}"));
    $o_dir = convert_gbk($dir);
    $n_dir = convert_gbk("uploadfile/goods/spu/{$id}/content");
    if (!rename($o_dir, $n_dir)) return false;

    $imgs = i_scandir($n_dir);
    $str = '';
    foreach ($imgs as $k => $img) {
        if (!check_img_type($img)) continue;
        $str .= "<p style='text-align: center;'><img src='uploadfile/goods/spu/{$id}/content/{$img}'/></p>";
    }
    if (!$str) return false;
    $map1['id'] = $id;
    $map1['content'] = $str;
    model('goods_spu')->update($map1);
}

/**
 * 列出指定路径中的文件和目录
 * @param $dir
 */
function i_scandir($dir)
{
    $dir = convert_gbk($dir);
    $dirs = array_diff(scandir($dir), array('..', '.'));
    $data = array();
    foreach ($dirs as $k => $v) {
        $data[$k] = convert_utf8($v);
    }
    return $data;
}

/**
 * 检查图片是否合法
 * @param $img
 * @return bool
 */
function check_img_type($img)
{
    if (!in_array(end(explode('.', $img)), array('jpg', 'jpge', 'png'))) return false;
    return true;
}

/**
 * 删除目录
 * @param $src
 */
function rrmdir($src)
{
    $dir = opendir($src);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            $full = $src . '/' . $file;
            if (is_dir($full)) {
                rrmdir($full);
            } else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}

/**
 * 创建文件夹
 * @param $dirName
 * @param int $rights
 */
function mkdir_r($dirName, $rights = 0777)
{
    $dirs = explode('/', $dirName);
    $dir = '';
    foreach ($dirs as $part) {
        $dir .= $part . '/';
        if (!is_dir($dir) && strlen($dir) > 0)
            mkdir($dir, $rights);
    }
}

/**
 * 得到分类id
 * @param $category_str
 */
function get_cate_id($category_str)
{
    $cate = explode('>', $category_str);
    $f_map['name'] = array_pop($cate);

    if ($s_map['name'] = array_pop($cate)) {
        $f_map['parent_id'] = model('goods_category')->where($s_map)->getField('id');
    } else {
        $f_map['parent_id'] = 0;
    }
    return model('goods_category')->where($f_map)->getField('id');
}

/**
 * 根据二维数组中的某个键值去重。
 * @param $arr
 * @param $key
 * @return mixed
 */
function assoc_unique($arr, $key)
{
    $tmp_arr = array();
    foreach ($arr as $k => $v) {
        if (in_array($v[$key], $tmp_arr)) {
            unset($arr[$k]);
        } else {
            $tmp_arr[] = $v[$key];
        }
    }
    return $arr;
}