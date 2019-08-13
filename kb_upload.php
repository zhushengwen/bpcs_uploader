#!/usr/bin/php
<?php

function recursive_upload($dir) {
    if (!is_dir($dir)) {
        return;
    }
    $handle = opendir($dir);
    while (($file = readdir($handle)) !== false) {
        if ($file == "." || $file == "..") {
            continue;
        }
        $file = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_file($file)) {
            upload_one_file($file);
        } elseif (is_dir($file)) {
            recursive_upload($file);
        }
    }
}

function get_filesize($file) { 
    $size = filesize($file); 
    if ($size < 0) 
        if (!(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')) 
            $size = trim(`stat -c%s $file`); 
        else{ 
            $fsobj = new COM("Scripting.FileSystemObject"); 
            $f = $fsobj->GetFile($file); 
            $size = $file->Size; 
        } 
    return $size; 
}

function upload_one_file($file){
    echo $file."\n";
    if(get_filesize($file) < 1073741824){ 
        system('./bpcs_uploader.php upload "'.$file.'" "'.string_filter($file).'" ');
    }else{//文件大于1个G
        system('./bpcs_uploader.php uploadbig "'.$file.'" "'.string_filter($file).'" ');
    }
    
}

/*
 * 把违法字符替换成空格,可能不全
 */
function string_filter($str){
    return str_replace(array(":", "|", "?", " "), array("", "", "", ""), $str);
}

// get args and upload
for ($i=1; $i < $argc; $i++) { 
    if (is_file($argv[$i])) {
        upload_one_file($argv[$i]);
    }else {
        recursive_upload($argv[$i]);
    }
}