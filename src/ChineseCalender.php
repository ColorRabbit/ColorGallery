<?php
namespace ColorGallery;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

/**
 * 大部分方法: https://github.com/overtrue/chinese-calendar
 * 算法: http://blog.jjonline.cn/userInterFace/173.html
 *
 * Class ChineseCalender
 */
class ChineseCalender
{
    /**
     * 最小的年份
     *
     * @var int
     */
    static private $minYear = 1900;

    /**
     * 最大的年份
     *
     * @var int
     */
    static private $maxYear = 2100;

    /**
     * 农历 1900-2100 的润大小信息.
     *
     * @var array
     */
    static private $lunars = [
        0x04bd8, 0x04ae0, 0x0a570, 0x054d5, 0x0d260, 0x0d950, 0x16554, 0x056a0, 0x09ad0, 0x055d2, // 1900-1909
        0x04ae0, 0x0a5b6, 0x0a4d0, 0x0d250, 0x1d255, 0x0b540, 0x0d6a0, 0x0ada2, 0x095b0, 0x14977, // 1910-1919
        0x04970, 0x0a4b0, 0x0b4b5, 0x06a50, 0x06d40, 0x1ab54, 0x02b60, 0x09570, 0x052f2, 0x04970, // 1920-1929
        0x06566, 0x0d4a0, 0x0ea50, 0x06e95, 0x05ad0, 0x02b60, 0x186e3, 0x092e0, 0x1c8d7, 0x0c950, // 1930-1939
        0x0d4a0, 0x1d8a6, 0x0b550, 0x056a0, 0x1a5b4, 0x025d0, 0x092d0, 0x0d2b2, 0x0a950, 0x0b557, // 1940-1949
        0x06ca0, 0x0b550, 0x15355, 0x04da0, 0x0a5b0, 0x14573, 0x052b0, 0x0a9a8, 0x0e950, 0x06aa0, // 1950-1959
        0x0aea6, 0x0ab50, 0x04b60, 0x0aae4, 0x0a570, 0x05260, 0x0f263, 0x0d950, 0x05b57, 0x056a0, // 1960-1969
        0x096d0, 0x04dd5, 0x04ad0, 0x0a4d0, 0x0d4d4, 0x0d250, 0x0d558, 0x0b540, 0x0b6a0, 0x195a6, // 1970-1979
        0x095b0, 0x049b0, 0x0a974, 0x0a4b0, 0x0b27a, 0x06a50, 0x06d40, 0x0af46, 0x0ab60, 0x09570, // 1980-1989
        0x04af5, 0x04970, 0x064b0, 0x074a3, 0x0ea50, 0x06b58, 0x055c0, 0x0ab60, 0x096d5, 0x092e0, // 1990-1999
        0x0c960, 0x0d954, 0x0d4a0, 0x0da50, 0x07552, 0x056a0, 0x0abb7, 0x025d0, 0x092d0, 0x0cab5, // 2000-2009
        0x0a950, 0x0b4a0, 0x0baa4, 0x0ad50, 0x055d9, 0x04ba0, 0x0a5b0, 0x15176, 0x052b0, 0x0a930, // 2010-2019
        0x07954, 0x06aa0, 0x0ad50, 0x05b52, 0x04b60, 0x0a6e6, 0x0a4e0, 0x0d260, 0x0ea65, 0x0d530, // 2020-2029
        0x05aa0, 0x076a3, 0x096d0, 0x04afb, 0x04ad0, 0x0a4d0, 0x1d0b6, 0x0d250, 0x0d520, 0x0dd45, // 2030-2039
        0x0b5a0, 0x056d0, 0x055b2, 0x049b0, 0x0a577, 0x0a4b0, 0x0aa50, 0x1b255, 0x06d20, 0x0ada0, // 2040-2049
        0x14b63, 0x09370, 0x049f8, 0x04970, 0x064b0, 0x168a6, 0x0ea50, 0x06b20, 0x1a6c4, 0x0aae0, // 2050-2059
        0x0a2e0, 0x0d2e3, 0x0c960, 0x0d557, 0x0d4a0, 0x0da50, 0x05d55, 0x056a0, 0x0a6d0, 0x055d4, // 2060-2069
        0x052d0, 0x0a9b8, 0x0a950, 0x0b4a0, 0x0b6a6, 0x0ad50, 0x055a0, 0x0aba4, 0x0a5b0, 0x052b0, // 2070-2079
        0x0b273, 0x06930, 0x07337, 0x06aa0, 0x0ad50, 0x14b55, 0x04b60, 0x0a570, 0x054e4, 0x0d160, // 2080-2089
        0x0e968, 0x0d520, 0x0daa0, 0x16aa6, 0x056d0, 0x04ae0, 0x0a9d4, 0x0a2d0, 0x0d150, 0x0f252, // 2090-2099
        0x0d520, // 2100
    ];

    /**
     * 天干地支之天干速查表.
     *
     * @var array
     */
    static private $tianGan = ['甲', '乙', '丙', '丁', '戊', '己', '庚', '辛', '壬', '癸'];

    /**
     * 天干地支之地支速查表.
     *
     * @var array
     */
    static private $diZhi = ['子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥'];

    /**
     * 天干地支之地支速查表 <=> 生肖.
     *
     * @var array
     */
    static private $animals = ['鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪'];

    /**
     * 24节气速查表.
     *
     * @var array
     */
    static private $solarTerm = [
        '小寒', '大寒', '立春', '雨水', '惊蛰', '春分',
        '清明', '谷雨', '立夏', '小满', '芒种', '夏至',
        '小暑', '大暑', '立秋', '处暑', '白露', '秋分',
        '寒露', '霜降', '立冬', '小雪', '大雪', '冬至',
    ];

    /**
     * 1900-2100 各年的 24 节气日期速查表.
     *
     * @var array
     */
    static private $solarTerms = [
        '9778397bd097c36b0b6fc9274c91aa', '97b6b97bd19801ec9210c965cc920e', '97bcf97c3598082c95f8c965cc920f',
        '97bd0b06bdb0722c965ce1cfcc920f', 'b027097bd097c36b0b6fc9274c91aa', '97b6b97bd19801ec9210c965cc920e',
        '97bcf97c359801ec95f8c965cc920f', '97bd0b06bdb0722c965ce1cfcc920f', 'b027097bd097c36b0b6fc9274c91aa',
        '97b6b97bd19801ec9210c965cc920e', '97bcf97c359801ec95f8c965cc920f', '97bd0b06bdb0722c965ce1cfcc920f',
        'b027097bd097c36b0b6fc9274c91aa', '9778397bd19801ec9210c965cc920e', '97b6b97bd19801ec95f8c965cc920f',
        '97bd09801d98082c95f8e1cfcc920f', '97bd097bd097c36b0b6fc9210c8dc2', '9778397bd197c36c9210c9274c91aa',
        '97b6b97bd19801ec95f8c965cc920e', '97bd09801d98082c95f8e1cfcc920f', '97bd097bd097c36b0b6fc9210c8dc2',
        '9778397bd097c36c9210c9274c91aa', '97b6b97bd19801ec95f8c965cc920e', '97bcf97c3598082c95f8e1cfcc920f',
        '97bd097bd097c36b0b6fc9210c8dc2', '9778397bd097c36c9210c9274c91aa', '97b6b97bd19801ec9210c965cc920e',
        '97bcf97c3598082c95f8c965cc920f', '97bd097bd097c35b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa',
        '97b6b97bd19801ec9210c965cc920e', '97bcf97c3598082c95f8c965cc920f', '97bd097bd097c35b0b6fc920fb0722',
        '9778397bd097c36b0b6fc9274c91aa', '97b6b97bd19801ec9210c965cc920e', '97bcf97c359801ec95f8c965cc920f',
        '97bd097bd097c35b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa', '97b6b97bd19801ec9210c965cc920e',
        '97bcf97c359801ec95f8c965cc920f', '97bd097bd097c35b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa',
        '97b6b97bd19801ec9210c965cc920e', '97bcf97c359801ec95f8c965cc920f', '97bd097bd07f595b0b6fc920fb0722',
        '9778397bd097c36b0b6fc9210c8dc2', '9778397bd19801ec9210c9274c920e', '97b6b97bd19801ec95f8c965cc920f',
        '97bd07f5307f595b0b0bc920fb0722', '7f0e397bd097c36b0b6fc9210c8dc2', '9778397bd097c36c9210c9274c920e',
        '97b6b97bd19801ec95f8c965cc920f', '97bd07f5307f595b0b0bc920fb0722', '7f0e397bd097c36b0b6fc9210c8dc2',
        '9778397bd097c36c9210c9274c91aa', '97b6b97bd19801ec9210c965cc920e', '97bd07f1487f595b0b0bc920fb0722',
        '7f0e397bd097c36b0b6fc9210c8dc2', '9778397bd097c36b0b6fc9274c91aa', '97b6b97bd19801ec9210c965cc920e',
        '97bcf7f1487f595b0b0bb0b6fb0722', '7f0e397bd097c35b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa',
        '97b6b97bd19801ec9210c965cc920e', '97bcf7f1487f595b0b0bb0b6fb0722', '7f0e397bd097c35b0b6fc920fb0722',
        '9778397bd097c36b0b6fc9274c91aa', '97b6b97bd19801ec9210c965cc920e', '97bcf7f1487f531b0b0bb0b6fb0722',
        '7f0e397bd097c35b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa', '97b6b97bd19801ec9210c965cc920e',
        '97bcf7f1487f531b0b0bb0b6fb0722', '7f0e397bd07f595b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa',
        '97b6b97bd19801ec9210c9274c920e', '97bcf7f0e47f531b0b0bb0b6fb0722', '7f0e397bd07f595b0b0bc920fb0722',
        '9778397bd097c36b0b6fc9210c91aa', '97b6b97bd197c36c9210c9274c920e', '97bcf7f0e47f531b0b0bb0b6fb0722',
        '7f0e397bd07f595b0b0bc920fb0722', '9778397bd097c36b0b6fc9210c8dc2', '9778397bd097c36c9210c9274c920e',
        '97b6b7f0e47f531b0723b0b6fb0722', '7f0e37f5307f595b0b0bc920fb0722', '7f0e397bd097c36b0b6fc9210c8dc2',
        '9778397bd097c36b0b70c9274c91aa', '97b6b7f0e47f531b0723b0b6fb0721', '7f0e37f1487f595b0b0bb0b6fb0722',
        '7f0e397bd097c35b0b6fc9210c8dc2', '9778397bd097c36b0b6fc9274c91aa', '97b6b7f0e47f531b0723b0b6fb0721',
        '7f0e27f1487f595b0b0bb0b6fb0722', '7f0e397bd097c35b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa',
        '97b6b7f0e47f531b0723b0b6fb0721', '7f0e27f1487f531b0b0bb0b6fb0722', '7f0e397bd097c35b0b6fc920fb0722',
        '9778397bd097c36b0b6fc9274c91aa', '97b6b7f0e47f531b0723b0b6fb0721', '7f0e27f1487f531b0b0bb0b6fb0722',
        '7f0e397bd097c35b0b6fc920fb0722', '9778397bd097c36b0b6fc9274c91aa', '97b6b7f0e47f531b0723b0b6fb0721',
        '7f0e27f1487f531b0b0bb0b6fb0722', '7f0e397bd07f595b0b0bc920fb0722', '9778397bd097c36b0b6fc9274c91aa',
        '97b6b7f0e47f531b0723b0787b0721', '7f0e27f0e47f531b0b0bb0b6fb0722', '7f0e397bd07f595b0b0bc920fb0722',
        '9778397bd097c36b0b6fc9210c91aa', '97b6b7f0e47f149b0723b0787b0721', '7f0e27f0e47f531b0723b0b6fb0722',
        '7f0e397bd07f595b0b0bc920fb0722', '9778397bd097c36b0b6fc9210c8dc2', '977837f0e37f149b0723b0787b0721',
        '7f07e7f0e47f531b0723b0b6fb0722', '7f0e37f5307f595b0b0bc920fb0722', '7f0e397bd097c35b0b6fc9210c8dc2',
        '977837f0e37f14998082b0787b0721', '7f07e7f0e47f531b0723b0b6fb0721', '7f0e37f1487f595b0b0bb0b6fb0722',
        '7f0e397bd097c35b0b6fc9210c8dc2', '977837f0e37f14998082b0787b06bd', '7f07e7f0e47f531b0723b0b6fb0721',
        '7f0e27f1487f531b0b0bb0b6fb0722', '7f0e397bd097c35b0b6fc920fb0722', '977837f0e37f14998082b0787b06bd',
        '7f07e7f0e47f531b0723b0b6fb0721', '7f0e27f1487f531b0b0bb0b6fb0722', '7f0e397bd097c35b0b6fc920fb0722',
        '977837f0e37f14998082b0787b06bd', '7f07e7f0e47f531b0723b0b6fb0721', '7f0e27f1487f531b0b0bb0b6fb0722',
        '7f0e397bd07f595b0b0bc920fb0722', '977837f0e37f14998082b0787b06bd', '7f07e7f0e47f531b0723b0b6fb0721',
        '7f0e27f1487f531b0b0bb0b6fb0722', '7f0e397bd07f595b0b0bc920fb0722', '977837f0e37f14998082b0787b06bd',
        '7f07e7f0e47f149b0723b0787b0721', '7f0e27f0e47f531b0b0bb0b6fb0722', '7f0e397bd07f595b0b0bc920fb0722',
        '977837f0e37f14998082b0723b06bd', '7f07e7f0e37f149b0723b0787b0721', '7f0e27f0e47f531b0723b0b6fb0722',
        '7f0e397bd07f595b0b0bc920fb0722', '977837f0e37f14898082b0723b02d5', '7ec967f0e37f14998082b0787b0721',
        '7f07e7f0e47f531b0723b0b6fb0722', '7f0e37f1487f595b0b0bb0b6fb0722', '7f0e37f0e37f14898082b0723b02d5',
        '7ec967f0e37f14998082b0787b0721', '7f07e7f0e47f531b0723b0b6fb0722', '7f0e37f1487f531b0b0bb0b6fb0722',
        '7f0e37f0e37f14898082b0723b02d5', '7ec967f0e37f14998082b0787b06bd', '7f07e7f0e47f531b0723b0b6fb0721',
        '7f0e37f1487f531b0b0bb0b6fb0722', '7f0e37f0e37f14898082b072297c35', '7ec967f0e37f14998082b0787b06bd',
        '7f07e7f0e47f531b0723b0b6fb0721', '7f0e27f1487f531b0b0bb0b6fb0722', '7f0e37f0e37f14898082b072297c35',
        '7ec967f0e37f14998082b0787b06bd', '7f07e7f0e47f531b0723b0b6fb0721', '7f0e27f1487f531b0b0bb0b6fb0722',
        '7f0e37f0e366aa89801eb072297c35', '7ec967f0e37f14998082b0787b06bd', '7f07e7f0e47f149b0723b0787b0721',
        '7f0e27f1487f531b0b0bb0b6fb0722', '7f0e37f0e366aa89801eb072297c35', '7ec967f0e37f14998082b0723b06bd',
        '7f07e7f0e47f149b0723b0787b0721', '7f0e27f0e47f531b0723b0b6fb0722', '7f0e37f0e366aa89801eb072297c35',
        '7ec967f0e37f14998082b0723b06bd', '7f07e7f0e37f14998083b0787b0721', '7f0e27f0e47f531b0723b0b6fb0722',
        '7f0e37f0e366aa89801eb072297c35', '7ec967f0e37f14898082b0723b02d5', '7f07e7f0e37f14998082b0787b0721',
        '7f07e7f0e47f531b0723b0b6fb0722', '7f0e36665b66aa89801e9808297c35', '665f67f0e37f14898082b0723b02d5',
        '7ec967f0e37f14998082b0787b0721', '7f07e7f0e47f531b0723b0b6fb0722', '7f0e36665b66a449801e9808297c35',
        '665f67f0e37f14898082b0723b02d5', '7ec967f0e37f14998082b0787b06bd', '7f07e7f0e47f531b0723b0b6fb0721',
        '7f0e36665b66a449801e9808297c35', '665f67f0e37f14898082b072297c35', '7ec967f0e37f14998082b0787b06bd',
        '7f07e7f0e47f531b0723b0b6fb0721', '7f0e26665b66a449801e9808297c35', '665f67f0e37f1489801eb072297c35',
        '7ec967f0e37f14998082b0787b06bd', '7f07e7f0e47f531b0723b0b6fb0721', '7f0e27f1487f531b0b0bb0b6fb0722',
    ];

    /**
     * 数字转中文速查表.
     *
     * @var array
     */
    static private $weekdayAlias = ['日', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十'];

    /**
     * 日期转农历称呼速查表.
     *
     * @var array
     */
    static private $dateAlias = ['初', '十', '廿', '卅'];

    /**
     * 月份转农历称呼速查表.
     *
     * @var array
     */
    static private $monthAlias = ['正', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '冬', '腊'];

    /**
     * @var object 时间
     */
    private $date = null;

    /**
     * ChineseCalender constructor.
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    /**
     * 获取阳历指定月份天数
     *
     * @param int $year
     * @param int $month
     *
     * @return mixed
     */
    static public function getSolarMonths($year, $month)
    {
        $monthHash = [
            '0' => 0,
            '1' => self::leapMonth($year) ? 29 : 28,
            '2' => 28,
            '3' => 31,
            '4' => 30,
            '5' => 31,
            '6' => 30,
            '7' => 31,
            '8' => 31,
            '9' => 30,
            '10' => 31,
            '11' => 30,
            '12' => 31
        ];

        return $monthHash[$month];
    }

    /**
     * 传入阳历年月日获得详细的公历、农历信息.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return array
     */
    public function solar($year, $month, $day)
    {
        $this->clearCache();
        $this->date = static::makeDate($year . '-' . $month . '-' . $day);

        $lunar = $this->solar2lunar();
        $week = abs($this->date->format('N')); // 1 ~ 7
        return array_merge($lunar, [
            'gregorian_year' => $year,
            'gregorian_month' => $month,
            'gregorian_day' => $day,
            'week_no' => $week,
            'week_name' => '星期' . self::$weekdayAlias[$week],
            'is_today' => static::makeDate('now')->diff($this->date)->days == 0,
            'constellation' => self::toConstellation($month, $day),
        ]);
    }

    /**
     * 传入农历年月日以及传入的月份是否闰月获得详细的公历、农历信息.
     *
     * @param int  $year        lunar year
     * @param int  $month       lunar month
     * @param int  $day         lunar day
     * @param bool $isLeapMonth lunar month is leap or not.[如果是农历闰月第四个参数赋值true即可]
     *
     * @return array
     */
    public function lunar($year, $month, $day, $isLeapMonth = false)
    {
        $this->clearCache();
        $solar = $this->lunar2solar($year, $month, $day, $isLeapMonth);
        return $this->solar($solar['solar_year'], $solar['solar_month'], $solar['solar_day']);
    }

    /**
     * 阳历转阴历.
     *
     * @return array
     */
    private function solar2lunar()
    {
        list($year, $month, $day) = explode('-', $this->date->format('Y-n-j'));
        $this->validDate();

        $offset = self::diffDate($this->date, '1900-01-31')->days;
        for ($i = static::$minYear; $i <= static::$maxYear && $offset > 0; ++$i) {
            $daysOfYear = self::daysOfYear($i);
            $offset -= $daysOfYear;
        }
        if ($offset < 0) {
            $offset += $daysOfYear;
            --$i;
        }
        // 农历年
        $lunarYear = $i;
        $leap = self::leapMonth($i); // 闰哪个月
        $isLeap = false;
        // 用当年的天数 offset,逐个减去每月（农历）的天数，求出当天是本月的第几天
        for ($i = 1; $i < 13 && $offset > 0; ++$i) {
            // 闰月
            if ($leap > 0 && $i == ($leap + 1) && !$isLeap) {
                --$i;
                $isLeap = true;
                $daysOfMonth = self::leapDays($lunarYear); // 计算农历月天数
            } else {
                $daysOfMonth = self::lunarDays($lunarYear, $i); // 计算农历普通月天数
            }
            // 解除闰月
            if ($isLeap == true && $i == ($leap + 1)) {
                $isLeap = false;
            }
            $offset -= $daysOfMonth;
        }
        // offset为0时，并且刚才计算的月份是闰月，要校正
        if ($offset == 0 && $leap > 0 && $i == $leap + 1) {
            if ($isLeap) {
                $isLeap = false;
            } else {
                $isLeap = true;
                --$i;
            }
        }
        if ($offset < 0) {
            $offset += $daysOfMonth;
            --$i;
        }
        // 农历月
        $lunarMonth = $i;
        // 农历日
        $lunarDay = $offset + 1;
        // 月柱 1900 年 1 月小寒以前为 丙子月(60进制12)
        $firstNode = $this->getTerm($lunarYear, ($month * 2 - 1)); // 返回当月「节气」为几日开始
        $secondNode = $this->getTerm($lunarYear, ($month * 2)); // 返回当月「节气」为几日开始
        // 依据 12 节气修正干支月
        $ganZhiMonth = $this->toGanZhi(($year - static::$minYear) * 12 + $month + 11);
        if ($day >= $firstNode) {
            $ganZhiMonth = $this->toGanZhi(($year - static::$minYear) * 12 + $month + 12);
        }
        // 获取该天的节气
        $term = null;
        if ($firstNode == $day) {
            $term = self::$solarTerm[$month * 2 - 2];
        }
        if ($secondNode == $day) {
            $term = self::$solarTerm[$month * 2 - 1];
        }
        // 日柱 当月一日与 1900/1/1 相差天数
        $dayCyclical = self::diffDate($year . '-' . $month . '-01', '1900-01-01')->days + 10;
        $ganZhiDay = $this->toGanZhi($dayCyclical + $day - 1);
        return [
            'lunar_year' => $lunarYear,
            'lunar_month' => $lunarMonth,
            'lunar_day' => $lunarDay,
            'lunar_month_chinese' => ($isLeap ? '闰' : '') . self::toChinaMonth($lunarMonth),
            'lunar_day_chinese' => self::toChinaDay($lunarDay),
            'ganzhi_year' => self::ganZhiYear($lunarYear),
            'ganzhi_month' => $ganZhiMonth,
            'ganzhi_day' => $ganZhiDay,
            'animal' => self::getAnimal($lunarYear),
            'term' => $term,
            'is_leap' => $isLeap,
        ];
    }

    /**
     * 阴历转阳历.
     *
     * @param int  $year
     * @param int  $month
     * @param int  $day
     * @param bool $isLeapMonth
     *
     * @return array|int
     */
    private function lunar2solar($year, $month, $day, $isLeapMonth = false)
    {
        // 参数区间 1900.1.3 1 ~2100.12.1
        $leapMonth = self::leapMonth($year);
        // 传参要求计算该闰月公历 但该年得出的闰月与传参的月份并不同
        if ($isLeapMonth && ($leapMonth != $month)) {
            $isLeapMonth = false;
        }
        // 超出了最大极限值
        if ($year == 2100 && $month == 12 && $day > 1 || $year == 1900 && $month == 1 && $day < 31) {
            return -1;
        }
        $maxDays = $days = self::lunarDays($year, $month);
        // if month is leap, _day use leapDays method
        if ($isLeapMonth) {
            $maxDays = self::leapDays($year);
        }
        // 参数合法性效验
        if ($year < 1900 || $year > 2100 || $day > $maxDays) {
            throw new InvalidArgumentException('传入的参数不合法');
        }
        // 计算农历的时间差
        $offset = 0;
        for ($i = 1900; $i < $year; ++$i) {
            $offset += self::daysOfYear($i);
        }
        $isAdd = false;
        for ($i = 1; $i < $month; ++$i) {
            $leap = self::leapMonth($year);
            if (!$isAdd) {// 处理闰月
                if ($leap <= $i && $leap > 0) {
                    $offset += self::leapDays($year);
                    $isAdd = true;
                }
            }
            $offset += self::lunarDays($year, $i);
        }
        // 转换闰月农历 需补充该年闰月的前一个月的时差
        if ($isLeapMonth) {
            $offset += $days;
        }
        // 1900 年农历正月一日的公历时间为 1900 年 1 月 30 日 0 时 0 分 0 秒 (该时间也是本农历的最开始起始点)
        $startTimestamp = mktime(0, 0, 0, 1, 30, 1900);
        $date = date('Y-m-d', ($offset + $day) * 86400 + $startTimestamp);
        list($solarYear, $solarMonth, $solarDay) = explode('-', $date);
        return [
            'solar_year' => $solarYear,
            'solar_month' => $solarMonth,
            'solar_day' => $solarDay,
        ];
    }

    /**
     * 获取时间对象.
     *
     * @param string $date
     * @param string $timezone
     *
     * @return DateTime
     */
    static private function makeDate($date, $timezone = 'Asia/Shanghai')
    {
        return new DateTime($date, new DateTimeZone($timezone));
    }

    /**
     * 验证日期是否在允许的范围内
     *
     * @return bool
     */
    private function validDate()
    {
        // 参数区间1900.1.31~2100.12.31
        $minTime = mktime(0, 0, 0, 01, 31, self::$minYear);
        $maxTime = mktime(0, 0, 0, 12, 31, self::$maxYear);
        $stamp = $this->date->getTimestamp();
        if ($stamp < $minTime || $stamp > $maxTime) {
            throw new InvalidArgumentException("Plunar Error: expected date $this->date->format('Y.m.d'), expecting 1891.2.9 - 2100.2.9");
        }

        return true;
    }

    /**
     * 计算天数差
     *
     * @param $firstDate
     * @param $secondDate
     *
     * @return bool|\DateInterval
     */
    static private function diffDate($firstDate, $secondDate)
    {
        if (!($firstDate instanceof DateTime)) {
            $firstDate = static::makeDate($firstDate);
        }
        if (!($secondDate instanceof DateTime)) {
            $secondDate = static::makeDate($secondDate);
        }
        return $firstDate->diff($secondDate);
    }

    /**
     * 返回农历指定年的总天数.
     *
     * @param int $year
     *
     * @return int
     */
    static public function daysOfYear($year)
    {
        $sum = 348;
        for ($i = 0x8000; $i > 0x8; $i >>= 1) {
            $sum += (self::$lunars[$year - 1900] & $i) ? 1 : 0;
        }
        return $sum + self::leapDays($year);
    }

    /**
     * 返回农历y年闰月的天数 若该年没有闰月则返回 0.
     *
     * @param int $year
     *
     * @return int
     */
    static public function leapDays($year)
    {
        if (self::leapMonth($year)) {
            return (self::$lunars[$year - static::$minYear] & 0x10000) ? 30 : 29;
        }
        return 0;
    }

    /**
     * 返回农历 y 年闰月是哪个月；若 y 年没有闰月 则返回0.
     *
     * @param int $year
     *
     * @return int
     */
    static private function leapMonth($year)
    {
        // 闰字编码 \u95f0
        return self::$lunars[$year - static::$minYear] & 0xf;
    }

    /**
     * 返回农历 y 年 m 月（非闰月）的总天数，计算 m 为闰月时的天数请使用 leapDays 方法.
     *
     * @param int $year
     * @param int $month
     *
     * @return int
     */
    static public function lunarDays($year, $month)
    {
        // 月份参数从 1 至 12，参数错误返回 -1
        if ($month > 12 || $month < 1) {
            return -1;
        }
        return  (static::$lunars[$year - static::$minYear] & (0x10000 >> $month)) ? 30 : 29;
    }

    /**
     * 公历月、日判断所属星座.
     *
     * @param int $gregorianMonth
     * @param int $gregorianDay
     *
     * @return string
     */
    static public function toConstellation($gregorianMonth, $gregorianDay)
    {
        $constellations = '魔羯水瓶双鱼白羊金牛双子巨蟹狮子处女天秤天蝎射手魔羯';
        $arr = [20, 19, 21, 21, 21, 22, 23, 23, 23, 23, 22, 22];
        return mb_substr($constellations, $gregorianMonth * 2 - ($gregorianDay < $arr[$gregorianMonth - 1] ? 2 : 0), 2, 'UTF-8');
    }

    /**
     * 传入公历年获得该年第n个节气的公历日期
     *
     * @example
     * <pre>
     *  $_24 = $this->getTerm(1987,3) ;// _24 = 4; 意即 1987 年 2 月 4 日立春
     * </pre>
     *
     * @param int $year 公历年(1900-2100)；
     * @param int $no   二十四节气中的第几个节气(1~24)；从n=1(小寒)算起
     *
     * @return int
     */
    public function getTerm($year, $no)
    {
        if ($year < 1900 || $year > 2100) {
            return -1;
        }
        if ($no < 1 || $no > 24) {
            return -1;
        }
        // hexdec — 十六进制转换为十进制
        $solarTermsOfYear = array_map('hexdec', str_split(self::$solarTerms[$year - 1900], 5));
        $positions = [
            0 => [0, 1],
            1 => [1, 2],
            2 => [3, 1],
            3 => [4, 2],
        ];
        $group = sprintf('%d', ($no - 1) / 4);
        list($offset, $length) = $positions[($no - 1) % 4];
        return substr($solarTermsOfYear[$group], $offset, $length);
    }

    /**
     * 传入offset偏移量返回干支.
     *
     * @param int $offset 相对甲子的偏移量
     *
     * @return string
     */
    public function toGanZhi($offset)
    {
        return self::$tianGan[$offset % 10] . self::$diZhi[$offset % 12];
    }

    /**
     * 传入农历数字月份返回汉语通俗表示法.
     *
     * @param int $month
     *
     * @return string
     */
    static public function toChinaMonth($month)
    {
        // 若参数错误 返回 -1
        if ($month > 12 || $month < 1) {
            throw new InvalidArgumentException("错误的月份:{$month}");
        }
        return self::$monthAlias[abs($month)].'月';
    }

    /**
     * 传入农历日期数字返回汉字表示法.
     *
     * @param int $day
     *
     * @return mixed|string
     */
    static public function toChinaDay($day)
    {
        switch ($day) {
        case 10:
            return '初十';
        case 20:
            return '二十';
            break;
        case 30:
            return '三十';
            break;
        default:
            return self::$dateAlias[sprintf('%d', $day / 10)] . self::$weekdayAlias[$day % 10];
        }
    }

    /**
     * 年份转生肖.
     * 仅能大致转换, 精确划分生肖分界线是 “立春”.
     *
     * @param int $year
     *
     * @return mixed
     */
    static public function getAnimal($year)
    {
        return self::$animals[($year - 4) % 12];
    }

    /**
     * 农历年份转换为干支纪年.
     *
     * @param int $lunarYear
     *
     * @return mixed
     */
    static public function ganZhiYear($lunarYear)
    {
        $ganKey = ($lunarYear - 3) % 10;
        $zhiKey = ($lunarYear - 3) % 12;
        // 如果余数为 0 则为最后一个天干
        if ($ganKey == 0) {
            $ganKey = 10;
        }
        // 如果余数为 0 则为最后一个地支
        if ($zhiKey == 0) {
            $zhiKey = 12;
        }
        return self::$tianGan[$ganKey - 1] . self::$diZhi[$zhiKey - 1];
    }

    /**
     * 清除一些会被影响的数据
     */
    public function clearCache()
    {
        $this->date = null;
    }

}