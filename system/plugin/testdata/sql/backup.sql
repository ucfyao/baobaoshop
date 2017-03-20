SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for hd_article
-- ----------------------------
DROP TABLE IF EXISTS `hd_article`;
CREATE TABLE `hd_article` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '文章标题',
  `content` text NOT NULL COMMENT '文章内容',
  `category_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '文章图片',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '外链',
  `dataline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `keywords` varchar(255) NOT NULL COMMENT '关键字',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `put` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否推荐',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='文章表';


-- ----------------------------
-- Table structure for hd_article_category
-- ----------------------------
DROP TABLE IF EXISTS `hd_article_category`;
CREATE TABLE `hd_article_category` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父分类',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='文章分类表';

-- ----------------------------
-- Table structure for hd_attribute
-- ----------------------------
DROP TABLE IF EXISTS `hd_attribute`;
CREATE TABLE `hd_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `value` text NOT NULL COMMENT '属性值(逗号分隔)',
  `search` smallint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否参与筛选',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '输入控件的类型,radio:单选,checkbox:复选,input:输入',
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='属性';

-- ----------------------------
-- Table structure for hd_brand
-- ----------------------------
DROP TABLE IF EXISTS `hd_brand`;
CREATE TABLE `hd_brand` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '品牌id',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '品牌名称',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '品牌logo图片',
  `descript` text NOT NULL COMMENT '品牌描述',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '品牌的地址',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态(是否显示，显示:1,隐藏:0)',
  `isrecommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='商品品牌';

-- ----------------------------
-- Table structure for `hd_focus`
-- ----------------------------
DROP TABLE IF EXISTS `hd_focus`;
CREATE TABLE `hd_focus` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `width` int(4) unsigned NOT NULL DEFAULT '100' COMMENT '宽',
  `height` int(4) unsigned NOT NULL DEFAULT '100' COMMENT '高',
  `target` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否新窗口打开',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='焦点图';

-- ----------------------------
-- Table structure for `hd_goods_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `hd_goods_attribute`;
CREATE TABLE `hd_goods_attribute` (
  `sku_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `attribute_id` int(10) unsigned NOT NULL COMMENT '属性id',
  `attribute_value` varchar(255) NOT NULL COMMENT '属性值',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '商品属性种类：1为规格，2为属性',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态(是否显示，显示:1,隐藏:0)',
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品属性';

-- ----------------------------
-- Table structure for `hd_goods_category`
-- ----------------------------
DROP TABLE IF EXISTS `hd_goods_category`;
CREATE TABLE `hd_goods_category` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '父级分类id',
  `type_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品模型id',
  `keywords` varchar(200) NOT NULL,
  `descript` varchar(200) NOT NULL,
  `show_in_nav` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在导航中显示,状态:0:关闭，1:开启',
  `grade` text NOT NULL COMMENT '价格分级',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态:0:关闭，1:开启',
  `sort` int(8) NOT NULL DEFAULT '100' COMMENT '排序',
  `img` varchar(200) NOT NULL DEFAULT '' COMMENT '分类前面的小图标',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '外部链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=226 DEFAULT CHARSET=utf8 COMMENT='商品分类';

-- ----------------------------
-- Table structure for hd_goods_sku
-- ----------------------------
DROP TABLE IF EXISTS `hd_goods_sku`;
CREATE TABLE `hd_goods_sku` (
  `sku_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '子商品id',
  `spu_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品id,取值goods的goods_id',
  `sku_name` varchar(200) NOT NULL DEFAULT '' COMMENT '子商品名称',
  `subtitle` varchar(200) NOT NULL DEFAULT '' COMMENT '副标题',
  `style` varchar(50) NOT NULL,
  `sn` varchar(200) NOT NULL DEFAULT '' COMMENT '商品货号',
  `barcode` varchar(60) NOT NULL DEFAULT '' COMMENT '商品条形码',
  `spec` text NOT NULL COMMENT '商品所属规格类型id，取值spec的id',
  `imgs` text NOT NULL COMMENT '商品图册',
  `thumb` varchar(200) NOT NULL DEFAULT '' COMMENT '缩略图',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `status_ext` tinyint(1) NOT NULL COMMENT '商品标签状态',
  `number` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '商品库存数量',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场售价',
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售售价',
  `keyword` text NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  `show_in_lists` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在列表显示',
  `warn_number` tinyint(3) NOT NULL DEFAULT '5',
  `prom_type` varchar(200) NOT NULL DEFAULT '' COMMENT '促销类型',
  `prom_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '促销类型ID',
  `up_time` int(10) NOT NULL DEFAULT '0' COMMENT '上架时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `edition` int(10) NOT NULL DEFAULT '1' COMMENT '版本号',
  PRIMARY KEY (`sku_id`),
  UNIQUE KEY `sn` (`sn`) USING BTREE,
  KEY `goods_id` (`spu_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=173 DEFAULT CHARSET=utf8 COMMENT='子商品表';

-- ----------------------------
-- Table structure for hd_goods_index
-- ----------------------------
DROP TABLE IF EXISTS `hd_goods_index`;
CREATE TABLE `hd_goods_index` (
  `sku_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `spu_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `catid` mediumint(8) unsigned NOT NULL,
  `brand_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品状态',
  `sales` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '人气',
  `show_in_lists` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在列表中显示',
  `status_ext` tinyint(1) NOT NULL,
  `shop_price` decimal(10,2) NOT NULL,
  `favorites` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '收藏',
  `attr_ids` text NOT NULL,
  `spec_ids` text NOT NULL,
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `prom_type` varchar(200) NOT NULL DEFAULT '' COMMENT '促销类型',
  `prom_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '促销类型ID',
  PRIMARY KEY (`sku_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for hd_goods_spu
-- ----------------------------
DROP TABLE IF EXISTS `hd_goods_spu`;
CREATE TABLE `hd_goods_spu` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '商品名称,商品标题',
  `sn` varchar(200) NOT NULL DEFAULT '' COMMENT '商品货号',
  `subtitle` varchar(200) NOT NULL DEFAULT '' COMMENT '副标题，广告语',
  `style` varchar(50) NOT NULL COMMENT '商品标题的html样式',
  `catid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `brand_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '商品品牌id',
  `keyword` varchar(200) NOT NULL COMMENT '商品关键词，利于搜索引擎优化',
  `description` varchar(200) NOT NULL COMMENT '商品描述，利于搜索引擎优化',
  `content` text NOT NULL COMMENT '商品的详细描述',
  `imgs` text NOT NULL COMMENT '商品图册',
  `thumb` varchar(200) NOT NULL DEFAULT '' COMMENT '缩略图',
  `min_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最小价格',
  `max_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最大价格',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(是否上架，删除:-1,上架:1,下架:0)',
  `specs` text NOT NULL COMMENT '规格数据 json',
  `sku_total` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '商品总数',
  `give_point` int(11) NOT NULL DEFAULT '-1' COMMENT '积分',
  `warn_number` tinyint(3) NOT NULL DEFAULT '2' COMMENT '库存警告数量',
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `spec_id` int(10) NOT NULL DEFAULT '0' COMMENT '上传图片时与规格关联的id',
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Table structure for hd_help
-- ----------------------------
DROP TABLE IF EXISTS `hd_help`;
CREATE TABLE `hd_help` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `url` varchar(50) NOT NULL DEFAULT '' COMMENT '超链接',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `keywords` varchar(50) NOT NULL DEFAULT '' COMMENT '帮助关键字',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='站点帮助';



-- ----------------------------
-- Table structure for hd_navigation
-- ----------------------------
DROP TABLE IF EXISTS `hd_navigation`;
CREATE TABLE `hd_navigation` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '导航名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用',
  `sort` int(8) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `target` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否新窗口打开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='导航设置';
