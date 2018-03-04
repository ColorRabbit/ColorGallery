<?php

namespace ColorGallery;

/**
 * Class CalculateTime
 * http://www.ab126.com/clock/2290.html  在线时间转换器
 *
 * @package ColorRabbitHome\ColorClass
 */
class CalculateTime
{

    /**
     * @var int
     */
    private $startTime = 0;

    /**
     * @var int
     */
    private $endTime = 0;

    /**
     * set startTime
     */
    public function setStartTime()
    {
        $this->startTime = microtime(true);
    }

    /**
     * set endTime
     */
    public function setEndTime()
    {
        $this->endTime = microtime(true);
    }

    /**
     * get span time
     *
     * @return float|int|mixed
     */
    public function spanTime()
    {
        $span = $this->endTime - $this->startTime;
        $span = var_export($span, true);
        if ($pos = strrpos($span, 'E')) {
            $num = intval(substr($span, $pos + 2));
            switch ($num) {
            case $num < 4:
                return substr($span, 0, $pos) * pow(10, 3 - $num) . ' 毫秒';
            case $num < 7:
                return substr($span, 0, $pos) * pow(10, 6 - $num) . ' 微秒';
            case $num > 6:
                return substr($span, 0, $pos) * pow(10, 9 - $num) . ' 纳秒';
            }
        }

        return $span . '秒';
    }

}