<div class="tips margin-tb">
    <div class="tips-info border">
        <h6>使用前请务必细则阅读使用手册，相信会为您避免很多不必要的麻烦。</h6>
        <a id="show-tip" data-open="true" href="javascript:;">关闭操作提示</a>
    </div>
    <?php if (!$_GET['type']) { ?>
        <div class="tips-txt padding-small-top layout">
            <p>- 我们约定所有的单份数据文件都是 UTF-8 编码格式且大小限制于2m以内，如果您的数据文件非 UTF-8 编码则极有可能导致数据异常。</p>
            <p>- 可以记事本打开数据文件，'文件->另存为->保存按钮'旁，查看当前编码并做修改。</p>
            <p>- 我们已经为您准备好了标准格式的 <a class="text-blue" href="/system/plugin/data_import/haidao_goods.csv">【商品数据模板】</a>，请您注意：在保存文件的时候不要更换了数据文件的编码。</p>

            <p>- 您可以在这里下载一份标准样式的 <a class="text-blue" href="/system/plugin/data_import/haidao_goods.zip">【商品图片压缩包】</a>，如果已经将商品图片压缩包上传至系统根目录 <a class="text-blue"
                                                                                                                                               href="index.php?m=admin&c=app&a=module&mod=data_import&type=img_goods">【匹配商品图片与数据】</a>。
            </p>

            <p>- 这是标准样式的 <a class="text-blue" href="/system/plugin/data_import/haidao_brand.zip">【品牌图片压缩包】</a>，如果已经将品牌图片压缩包上传至系统根目录 <a class="text-blue"
                                                                                                                                       href="index.php?m=admin&c=app&a=module&mod=data_import&type=img_brand">【匹配品牌图片与数据】</a>。
            </p>
            <p><span class="text-red">商品数据导入注意事项：</span></p>
            <p>- 什么叫spu与sku?简单来说spu就是iPhone7，sku就是iPhone7-黑色，iPhone7-银色。</p>
            <p>- 我们强烈建议商品名称中不要包含任意特殊符号且禁止以空格开头！</p>
            <p>- 如果不填写sku市场价数据，那么市场价默认为sku销售价的1.2倍。</p>
            <p>- 分类格式：'顶级(可选)>二级(可选)>子级(必须)'。</p>
            <div class="hr-gray"></div>
            <p><span class="text-red">当您完成数据文件的成功导入後便可以进行第二步关于相关图片的操作：</span></p>
            <p>- 关于图片的处理，您必须要具有服务器FTP文件上传与系统文件读写权限与能力，我们需要您将规定命名与格式的图片文件夹压缩为zip格式後上传到系统的根目录，注意：完成图片处理后该文件将被即时删除。</p>
            <p>- 我们约定商品图片压缩文件夹名称为【haidao_goods.zip】，其中包含【spu（必须）】【sku（可选）】两个文件夹，两个文件夹中又包含名称为【'商品名称'（是实际的商品名称！）】的文件夹。</p>
            <p>-
                spu文件夹下'spu名称（如：iPhone7）'文件夹中包括：【封面，必须【数量=1】】【相册，必须【数量>=1】】【内容，可选【数量>=1】】三个文件夹。</p>
            <p>- 内容图片文件夹内的图片以展示顺序（如:a.png,b.png,c.png,d.png,e.png）命名</p>
            <p>-
                sku文件夹下'sku名称（如：iPhone7-黑色）'文件夹中包括：【封面，可选【数量=1】】【相册，可选【数量>=1】】【内容，可选【数量>=1】】三个文件夹。</p>
            <p>- 我们建议所有的封面图片采用正方形（即：400*400,600*600,800*800）分辨率，避免与模板样式造成冲突。</p>
            <p>- 当无sku图片时，默认会显示其对应spu商品封面，相册，与内容图片。</p>
            <p>- 我们约定品牌图片压缩文件夹名称为【haidao_brand.zip】，其中包含名称为【'品牌名称.图片格式'（如：iPhone.png）】图片文件。</p>
        </div>

    <?php } ?>
</div>
<div class="hr-gray"></div>