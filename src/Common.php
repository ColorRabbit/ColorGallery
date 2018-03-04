<?php
namespace ColorGallery;

/**
 * Class Common
 *
 * @package ColorRabbitHome\ColorClass
 */
class Common
{
    /**
     * Common constructor.
     */
    public function __construct()
    {
    }

    /**
     * 将字符串替换
     *
     * @param string        $str            待处理的字符串
     * @param string        $replaceStr     需要替换的字符串
     * @param string        $result         将第一次结果集传给function
     *
     * @return string       $result
     */
    public function diff($str, $replaceStr, $result = '')
    {
        $start = strspn($str, $replaceStr);
        $end = strcspn($str, $replaceStr, $start);
        $result .= mb_substr($str, $start, $end);
        $str = mb_substr($str, $start + $end);
        if (!empty($str)) {
            return $this->diff($str, $replaceStr, $result);
        }

        return $result;
    }

    /**
     * 字符串去重
     * @param string $str        需要处理的字符串
     * @param string $charset    字符编码
     *
     * @return bool|string
     */
    public function uniqueStr($str, $charset = '')
    {
        $array = $this->str2Array($str, $charset);
        if (!empty($array)) {
            $array = array_unique($array);
            $res = implode('', $array);

            return $res;
        }

        return false;
    }

    /**
     * 字符串转数组
     *
     * @param        $str
     * @param string $charset
     *
     * @return array
     */
    public function str2Array($str, $charset = '')
    {
        $array = array();
        if ($charset == 'UTF-8') {
            $strlen = mb_strlen($str);
            while ($strlen) {
                $array[] = mb_substr($str, 0, 1, $charset);
                $str = mb_substr($str, 1, $strlen, $charset);
                $strlen = mb_strlen($str);
            }
        }
        if ($charset == '') {
            $strlen = strlen($str);
            while ($strlen) {
                $array[] = substr($str, 0, 1);
                $str = substr($str, 1, $strlen);
                $strlen = strlen($str);
            }
        }

        return $array;
    }

    /**
     * 按行分隔符读取大文件(100M以上)
     *
     * @param string $filename  文件名
     * @param int    $count     读取行数 default 10000
     * @param string $sep       行分隔符
     * @param int    $offset    读取位置 0(正向) | -1(逆向)
     * @param int    $position  偏移量
     *
     * @return array
     * @throws \ErrorException
     */
    public function getFileLines($filename, $count = 10000, $sep = "\n", $offset = 0, $position = 0)
    {
        $handle  = fopen($filename, "a+");
        $content = ''; // 最终内容
        $pos = $position;

        // 正向读取文件
        if ($offset === 0) {
            if ($position < 0) {
                throw new \ErrorException('正向读文件暂不支持postion<0');
            }
            for ($i = 0; $i < $count;) {
                // 读取文件
                fseek($handle, $pos, SEEK_SET);
                $cContent = fread($handle, strlen($sep));  // 当前读取内容寄存
                /*if (strlen($cContent) > 1) {
                    $content  = $content . substr($cContent, 0, 1);// 拼接字符串 备用方案
                }*/
                $content  = substr($content, 0, $pos - $position) . $cContent;// 很准确
                $pos++;

                if ($sep === $cContent) {
                    $i++;
                }
                if (fgets($handle) === false) {
                    break;
                }
            }
        }

        // 逆向读取文件
        if ($offset === -1) {
            if ($position > 0) {
                throw new \ErrorException('反向读文件暂不支持postion>0');
            }
            for ($i = 0; $i <= $count;) {
                // 读取文件
                fseek($handle, $pos, SEEK_END);
                $cContent = fread($handle, strlen($sep));  // 当前读取内容寄存
                if (strlen($cContent) > 1) {
                    $content  = substr($cContent, -1, 1) . $content;// 拼接字符串 备用方案
                } else {
                    $content = $cContent . $content;
                }
                // $content  = $cContent . substr($content, - $position + $pos + 1);// 不太稳定,拼接字符串
                $pos--;

                if ($sep === $cContent) {
                    $i++;
                }
                if (fgets($handle) === false) {
                    break;
                }
            }
            $pos++;
        }

        return array('content' => $content, 'pos' => $pos);
    }

    /**
     * 检测字符编码并专成UTF-8格式
     * @param $str
     *
     * @return string
     */
    public function safeEncoding($str)
    {
        // $code = mb_detect_encoding($str, array('ASCII', 'GB2312', 'GBK', 'UTF-8'));
        // if ($code == 'CP936') {
        //     return $str;
        // }

        return iconv('GBK', 'UTF-8', $str);
    }

    /**
     * @param $url
     *
     * @return bool|string
     */
    public function getHandleUrl($url)
    {
        $pos = strpos($url, '//');
        $newUrl = substr($url, $pos + 2);
        $lpos = strpos($newUrl, '/');
        if ($lpos === false) {
            return $newUrl;
        }

        return substr($newUrl, 0, $lpos);
    }

    /**
     *
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}

