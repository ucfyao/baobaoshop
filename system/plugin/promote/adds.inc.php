<?php

$plugins = cache('plugins');
$plugins = $plugins[$_GET['mod']];
include(PLUGIN_PATH . PLUGIN_ID . '/template/adds.php');