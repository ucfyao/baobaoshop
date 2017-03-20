<?php include template('header', 'admin'); ?>
<?php include(PLUGIN_PATH . PLUGIN_ID . '/template/common/nav.php'); ?>
<div class="content padding-big have-fixed-nav">
    <?php include(PLUGIN_PATH . PLUGIN_ID . '/template/common/tip.php'); ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group"><span class="label">商品数据文件（csv）：</span>
            <div class="box">
                <div class="input hd-input file-box clearfix"><input type="text" class="file-txt" value="" tabindex="0"><input
                        type="button" class="file-btn" value="浏览"><input type="file" class="file"
                                                                         name="goods_file" value=""></div>
            </div>
        </div>

        <div class="form-group"><span class="label text-red">淘宝数据文件（csv）：</span>
            <div class="box">
                <div class="input hd-input file-box clearfix"><input type="text" class="file-txt" value="" tabindex="0"><input
                            type="button" class="file-btn" value="浏览"><input type="file" class="file"
                                                                             name="taobao_file" value=""></div>
            </div>
            <p class="desc">一.限制csv编码为UTF-8（请参考顶部提示第二条核对信息）。二.限制数据文件大小在服务器上传限制（默认2M）以内。</p>
        </div>

        <div class="form-group">
            <input type="submit" class="button bg-main" value="提交"/>
            <a class="button margin-left bg-gray" href="">返回</a>
        </div>
    </form>
</div>



