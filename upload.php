<?php
/**
 * Created by PhpStorm.
 * User: zhushengwen
 * Date: 2019/8/13
 * Time: 15:15
 */
include '_bpcs_files_/common.inc.php';
$dir       = $argv[1] ?: '.';
$keep      = $argv[2] ?: 7;
$prefixdir = $argv[3];
if ($prefixdir) {
    $prefixdir .= DIRECTORY_SEPARATOR;
}

function recursive_upload($dir)
{
    if ( ! is_dir($dir)) {
        return;
    }
    $handle = opendir($dir);
    while (($file = readdir($handle)) !== false) {
        if ($file == "." || $file == "..") {
            continue;
        }
        $file = $dir.DIRECTORY_SEPARATOR.$file;
        if (is_file($file)) {
            upload($file);
        } elseif (is_dir($file)) {
            recursive_upload($file);
        }
    }
}

function to_unix_path($path)
{
    return str_replace('\\', '/', $path);
}

function not_exists($file_dst)
{
    $uploader = __DIR__.'/bpcs_uploader.php';
    $info     = cmd("php $uploader info $file_dst");

    return (boolean)(strpos($info, 'file does not exist'));
}

function exec_upload($file, $file_dst)
{
    $uploader = __DIR__.'/bpcs_uploader.php';
    $info     = cmd("php $uploader upload $file $file_dst");

    return (boolean)(strpos($info, 'uploaded.'));
}

function upload($file)
{
    global $dir, $prefixdir, $keep;
    $mtime = filemtime($file);
    $ntime = time();

    $file_dst   = $prefixdir.trim(substr($file, strlen($dir)), DIRECTORY_SEPARATOR);
    $file_dst   = to_unix_path($file_dst);
    $not_exists = not_exists($file_dst);
    //check exists
    $ok = false;
    if ($not_exists) {
        $ok = exec_upload($file, $file_dst);
    }
    if ( ! $not_exists or $ok) {
        if ($ntime - $mtime > $keep * 24 * 3600) {
            unlink($file);
        }
    }
}

recursive_upload($dir);
