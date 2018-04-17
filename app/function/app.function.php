<?php
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 6; $i++) $size /= 1024;
    return sprintf("%.2f", $size) . $delimiter . $units[$i];
}