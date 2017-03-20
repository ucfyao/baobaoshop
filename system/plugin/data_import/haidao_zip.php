<?php

class Hzip
{

    public function __construct()
    {
        header("content-type:text/html;charset=utf8");
    }

    /**
     * 解压文件到指定目录
     *
     * @param   string   zip压缩文件的路径
     * @param   string   解压文件的目的路径
     * @param   boolean  是否以压缩文件的名字创建目标文件夹
     * @param   boolean  是否重写已经存在的文件
     */
    public function unzip($src_file, $dest_dir = false, $create_zip_name_dir = true, $overwrite = true)
    {
        if (!$zip = zip_open($src_file)) return false;
        $splitter = ($create_zip_name_dir === true) ? "." : "/";
        if ($dest_dir === false) {
            $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter)) . "/";
        }
        $this->create_dirs($dest_dir);
        while ($zip_entry = zip_read($zip)) {
            $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
            if ($pos_last_slash !== false) {
                $this->create_dirs($dest_dir . substr(zip_entry_name($zip_entry), 0, $pos_last_slash + 1));
            }
            if (zip_entry_open($zip, $zip_entry, "r")) {
                $file_name = $dest_dir . zip_entry_name($zip_entry);
                if ($overwrite === true || $overwrite === false && !is_file($file_name)) {
                    $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                    @file_put_contents($file_name, $fstream);
                    chmod($file_name, 0777);
                }
                zip_entry_close($zip_entry);
            }
        }
        zip_close($zip);
        return true;
    }

    /**
     * 创建目录
     */
    public function create_dirs($path)
    {
        if (is_dir($path)) return false;
        $directory_path = "";
        $directories = explode("/", $path);
        array_pop($directories);
        foreach ($directories as $directory) {
            $directory_path .= $directory . "/";
            if (!is_dir($directory_path)) {
                mkdir($directory_path);
                chmod($directory_path, 0777);
            }
        }
    }
}