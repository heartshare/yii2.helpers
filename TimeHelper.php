<?php

namespace hughcube\helpers;

final class TimeHelper
{
    /**
     * 函数getTheMsec获取当前的微秒时间戳;
     *
     * @param void ;
     * @return float;
     */
    public static function microtime()
    {
        list($msec, $sec) = explode(' ', microtime());

        return ($msec + $sec) * 1000000;
    }

    //'Y-d-m H:i:s u'
    public static function date($format, $time = null)
    {
        $time = null === $time ? microtime(true) : $time;
        $millisec = round(($time - intval($time)) * 1000);

        if (1000 == $millisec) {
            $time++;
            $millisec = 0;
        }

        $millisec = str_pad(strval($millisec), 3, '0', STR_PAD_LEFT);

        return date(strtr($format, ['u' => $millisec]), intval($time));
    }

    /**
     * 函数getAgeByTimestamp根据时间戳得到年龄;
     *
     * @param timestamp int [必选]    时间戳;
     * @return int;
     */
    public static function getAge($timestamp, $by = null, $format = 'Y-m-d')
    {
        $b = explode('-', date($format, $timestamp));
        $n = explode('-', date($format, null === $by ? time() : $by));

        $age = $n[0] - $b[0] - 1;
        unset($n[0]);
        unset($b[0]);

        $total = count($b);
        foreach ($b as $key => $value) {
            if ($n[$key] < $b[$key]) {
                break;
            } elseif (($n[$key] > $b[$key]) || ($n[$key] == $b[$key] && $key == $total - 1)) {
                $age++;
                break;
            }
        }

        return $age;
    }

    /**
     * 函数getXingZuoByTimestamp,根据时间戳得到星座;
     *
     * @param timestamp int [必选]    时间戳;
     * @return string;
     */
    private static $xingZuo = [
        1 => [20 => ['name' => ['en_us' => 'Aquarius', 'zh_cn' => '水瓶座', 'zh_tw' => '水瓶座']]],
        2 => [19 => ['name' => ['en_us' => 'Pisces', 'zh_cn' => '双鱼座', 'zh_tw' => '雙魚座']]],
        3 => [21 => ['name' => ['en_us' => 'Aries', 'zh_cn' => '牡羊座', 'zh_tw' => '牧羊座']]],
        4 => [20 => ['name' => ['en_us' => 'Taurus', 'zh_cn' => '金牛座', 'zh_tw' => '金牛座']]],
        5 => [21 => ['name' => ['en_us' => 'Gemini', 'zh_cn' => '双子座', 'zh_tw' => '雙子座']]],
        6 => [22 => ['name' => ['en_us' => 'Cancer', 'zh_cn' => '巨蟹座', 'zh_tw' => '巨蟹座']]],
        7 => [23 => ['name' => ['en_us' => 'Leo', 'zh_cn' => '狮子座', 'zh_tw' => '獅子座']]],
        8 => [23 => ['name' => ['en_us' => 'Virgo', 'zh_cn' => '处女座', 'zh_tw' => '處女座']]],
        9 => [23 => ['name' => ['en_us' => 'Libra', 'zh_cn' => '天秤座', 'zh_tw' => '天枰座']]],
        10 => [24 => ['name' => ['en_us' => 'Scorpio', 'zh_cn' => '天蝎座', 'zh_tw' => '天蠍座']]],
        11 => [22 => ['name' => ['en_us' => 'Sagittarius', 'zh_cn' => '射手座', 'zh_tw' => '射手座']]],
        12 => [22 => ['name' => ['en_us' => 'Capricorn', 'zh_cn' => '魔羯座', 'zh_tw' => '摩羯座']]],
    ];

    public function getXingZuo($timestamp)
    {
        $month = idate('n', $timestamp);
        $day = idate('j', $timestamp);
        list($startDay, $xingZuoName) = each(static::$xingZuo[$month]);
        if ($day < $startDay) {
            $month = 0 == (($month - 1 < 0) ? ($month = 11) : ($month -= 1)) ? 12 : $month;
            list($startDay, $xingZuoName) = each(static::$xingZuo[$month]);
        }

        return array_merge($xingZuoName, ['id' => $month]);
    }

    /**
     * 函数getPeriodOfYear,根据一个时间戳得到当年的起始时间戳和结束时间戳;
     *
     * @param timestamp int [必须] 时间戳;
     * @return array,键@start:开始时间戳,键@end:结束时间戳;
     */
    public static function getPeriodOfYear($timestamp = null)
    {
        $timestamp = null === $timestamp ? time() : $timestamp;
        $year = date('Y', $timestamp);

        return [
            'start' => strtotime($year . '-01-01 00:00:00'),
            'end' => strtotime($year . '-12-31 23:59:59')
        ];
    }

    /**
     * 函数getPeriodOfMonth,根据一个时间戳得到当月的起始时间戳和结束时间戳;
     *
     * @param timestamp int [必须] 时间戳;
     * @return array,键@start:开始时间戳,键@end:结束时间戳;
     */
    public static function getPeriodOfMonth($timestamp = null)
    {
        $timestamp = null === $timestamp ? time() : $timestamp;

        return [
            'start' => strtotime(date('Y-m-01 00:00:00', $timestamp)),
            'end' => strtotime(date('Y-m-t 23:59:59', $timestamp))
        ];
    }

    /**
     * 函数getPeriodOfWeek,根据一个时间戳得到当周的起始时间戳和结束时间戳;
     *
     * @param timestamp int [必须] 时间戳;
     * @return array,键@start:开始时间戳,键@end:结束时间戳;
     */
    public static function getPeriodOfWeek($timestamp = null)
    {
        $timestamp = null === $timestamp ? time() : $timestamp;
        $dateInfoArray = getdate($timestamp);
        $result['start'] = strtotime($dateInfoArray['year'] . '-' . $dateInfoArray['mon'] . '-' . $dateInfoArray['wday']);
        $result['start'] -= (($dateInfoArray['wday'] == 0 ? 7
                    : $dateInfoArray['wday']) - 2) * 86400;
        $result['end'] = $result['start'] + 604799;

        return $result;
    }

    /**
     * 函数getPeriodOfDay,根据一个时间戳得到当天的起始时间戳和结束时间戳;
     *
     * @param timestamp int [必须] 时间戳;
     * @return array,键@start:开始时间戳,键@end:结束时间戳;
     */
    public static function getPeriodOfDay($timestamp = null)
    {
        $timestamp = null === $timestamp ? time() : $timestamp;
        $dateString = date('Y-m-d', $timestamp);

        return [
            'start' => strtotime($dateString . ' 00:00:00'),
            'end' => strtotime($dateString . ' 23:59:59')
        ];
    }

    /**
     * 函数getPeriodOfQuarter,根据一个时间戳得到当季度的起始时间戳和结束时间戳;
     *
     * @param timestamp int [必须] 时间戳;
     * @return array,键@start:开始时间戳,键@end:结束时间戳;
     */
    public static function getPeriodOfQuarter($timestamp = null)
    {
        $timestamp = null === $timestamp ? time() : $timestamp;
        $base = [1 => [1, 3], 2 => [4, 6], 3 => [7, 9], 4 => [10, 12]];
        $dateInfoArray = getdate($timestamp);
        $quarter = ceil($dateInfoArray['mon'] / 3);

        return [
            'start' => strtotime($dateInfoArray['year'] . '-' . $base[$quarter][0] . '-01 00:00:00'),
            'end' => strtotime(date('Y-m-t 23:59:59', strtotime($dateInfoArray['year'] . '-' . $base[$quarter][1] . '-01 00:00:00')))
        ];
    }

    /**
     * 计算当前时间和格林威治时间相差多少秒
     *
     * @return [type] [description]
     */
    public static function getJetLag()
    {
        return time() - strtotime(gmdate('y-m-d H:i:s', time()));
    }
}
