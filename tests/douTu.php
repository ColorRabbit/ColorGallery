<?php
/**
 * Created by PhpStorm.
 * User: colorrabbit
 * Date: 2018/2/14
 * Time: 11:03
 */
namespace ColorGallery\tests;

use ColorGallery\DouTu;

$keyWord = filter_input(INPUT_GET, 'key_word');
$mime = filter_input(INPUT_GET, 'mime');
$page = filter_input(INPUT_GET, 'page');

if ($keyWord === null) {
    $keyWord = '哄';
}

if ($mime === null) {
    $mime = 0;
}

if ($page === null) {
    $page = 1;
}

$douTu = new DouTu();
$douTu->setKeyWord($keyWord);
$douTu->setMime($mime);

echo $douTu->douTu($page);