<?php
/**
 * Created by PhpStorm.
 * User: ColorRabbit
 * Date: 2017/11/13
 * Time: 16:40
 */
namespace ColorGallery;

/**
 * 算法来自: http://phping.net/2015/12/08/PHP%E7%B3%BB%E5%88%97%E9%97%AE%E7%AD%94%E5%AD%A6%E4%B9%A0%E4%B9%8BString/
 * Class UnicodeMutualUtf8
 *
 * @package ColorGallery
 */
class UnicodeMutualUtf8
{
    /**
     * utf8字符转换成Unicode字符
     * @param $utf8Str
     *
     * @return string
     */
    public static function utf8ToUnicode($utf8Str)
    {
        $unicode = (ord($utf8Str[0]) & 0x1F) << 12;
        $unicode |= (ord($utf8Str[1]) & 0x3F) << 6;
        $unicode |= (ord($utf8Str[2]) & 0x3F);

        return dechex($unicode);
    }

    /**
     * Unicode字符转换成utf8字符
     * @param $unicodeStr
     *
     * @return string
     */
    public static function unicodeToUtf8($unicodeStr)
    {
        $code = intval(hexdec($unicodeStr));
        //这里注意转换出来的code一定得是整形，这样才会正确的按位操作
        $ord1 = decbin(0xe0 | ($code >> 12));
        $ord2 = decbin(0x80 | (($code >> 6) & 0x3f));
        $ord3 = decbin(0x80 | ($code & 0x3f));
        $utf8Str = chr(bindec($ord1)) . chr(bindec($ord2)) . chr(bindec($ord3));

        return $utf8Str;
    }

}