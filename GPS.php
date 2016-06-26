<?php
/**
 *
 */
namespace hughcube\helpers;

class GPS
{
    /**
     * 就算两个经纬度的距离
     *
     * @param  $location string, 格式: longitude,latitude
     * @return double 单位米
     */
    public function getDistance($locationA, $locationB)
    {
        $locationA = static::analyseLocation($locationA);
        $locationB = static::analyseLocation($locationB);
        if (empty($locationB) || empty($locationA)) {
            return false;
        }

        $lngA = $locationA['lng'];
        $latA = $locationA['lat'];

        $lngB = $locationB['lng'];
        $latB = $locationB['lat'];

        $earthRadius = 6367000;
        $latA = ($latA * pi()) / 180;
        $lngA = ($lngA * pi()) / 180;
        $latB = ($latB * pi()) / 180;
        $lngB = ($lngB * pi()) / 180;
        $calcLongitude = $lngB - $lngA;
        $calcLatitude = $latB - $latA;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($latA) * cos($latB) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return $calculatedDistance;
    }

    /**
     * 解析经纬度字符串, 经度在前纬度在后
     *
     * @param  $location string 113.1111,22.86789
     * @return array ['lng' => 经度, 'lat' => 纬度]
     */
    public static function analyseLocation($location)
    {
        $locationArray = explode(',', $location);
        if (2 != count($locationArray)
            || !is_numeric($locationArray[0])
            || !is_numeric($locationArray[1])
            || abs($locationArray[0]) >= 180
            || abs($locationArray[1]) >= 90
        ) {
            return false;
        } else {
            return ['lat' => $locationArray[1], 'lng' => $locationArray[0]];
        }
    }

    /**
     * 判断点是否在一个多边形里面
     *
     * @param $point
     * @param $points
     * @return bool
     */
    public static function inBlock($point, $points)
    {
        // 求得最大点和最小点的x,y
        $maxX = $points[0][0];
        $maxY = $points[0][1];
        $minX = $points[0][0];
        $minY = $points[0][1];
        foreach ($points as $value) {
            if ($value[0] > $maxX) {
                $maxX = $value[0];
            }
            if ($value[0] < $minX) {
                $minX = $value[0];
            }
            if ($value[1] > $maxY) {
                $maxY = $value[1];
            }
            if ($value[1] < $minY) {
                $minY = $value[1];
            }
        }

        // 判断是不是在最大四边形外
        if ($point[0] > $maxX || $point[0] < $minX || $point[1] > $maxY || $point[1] < $minY) {
            return false;
        }

        // 求当前点的垂直线与几条边相交
        $crosspoint = 0;
        $newline = ['x1' => $point[0], 'x2' => $point[0], 'y1' => $point[1], 'y2' => $minY];
        for ($i = 1; $i < count($points); $i++) {
            $tmpline = [
                'x1' => $points[($i - 1)][0],
                'x2' => $points[$i][0],
                'y1' => $points[($i - 1)][1],
                'y2' => $points[$i][1]
            ];
            if (static::inLine($point, $tmpline)) {
                return true;
            }            // 判断是否在线上，是直接返回true
            if (static::isCross($newline, $tmpline)) {
                $crosspoint++;
            }
        }

        $tmpline = [
            'x1' => $points[($i - 1)][0],
            'x2' => $points[0][0],
            'y1' => $points[($i - 1)][1],
            'y2' => $points[0][1]
        ];
        // 判断是否在线上，是直接返回true
        if (static::inLine($point, $tmpline)) {
            return true;
        }
        if (static::isCross($newline, $tmpline)) {
            $crosspoint++;
        }

        // 相交的线为奇数是，在面内。
        return 1 == $crosspoint % 2;
    }

    protected static function multiply($x1, $y1, $x2, $y2, $x0, $y0)
    {
        return ($x1 - $x0) * ($y2 - $y0) - ($x2 - $x0) * ($y1 - $y0);
    }

    // 判断两线段是否相交
    public static function isCross($a, $b)
    {
        return max($a['x1'], $a['x2']) >= min($b['x1'], $b['x2']) && max($b['x1'], $b['x2']) >= min($a['x1'], $a['x2']) && max($a['y1'], $a['y2']) >= min($b['y1'], $b['y2']) && max($b['y1'], $b['y2']) >= min($a['y1'], $a['y2']) && static::multiply($a['x1'], $a['y1'], $b['x2'], $b['y2'], $b['x1'], $b['y1']) * static::multiply($b['x2'], $b['y2'], $a['x2'], $a['y2'], $b['x1'], $b['y1']) >= 0 && static::multiply($b['x1'], $b['y1'], $a['x2'], $a['y2'], $a['x1'], $a['y1']) * static::multiply($a['x2'], $a['y2'], $b['x2'], $b['y2'], $a['x1'], $a['y1']) >= 0;
    }

    // 判断点是否在线段上
    public static function inLine($p, $line)
    {
        if ($line['x1'] == $line['x2']) {
            return $p[0] == $line['x1'] && ($p[1] < max($line['y1'], $line['y2']) && $p[1] > min($line['y1'], $line['y2']));
        }

        if ($line['y1'] == $line['y2']) {
            return $p[1] == $line['y1'] && ($p[0] < max($line['x1'], $line['x2']) && $p[0] > min($line['x1'], $line['x2']));
        }

        if ($line['y1'] == $p[1]) {
            return $line['x1'] == $p[0];
        }
        if ($line['x1'] == $p[0]) {
            return $line['y1'] == $p[1];
        }

        return abs(($line['x1'] - $line['x2']) / ($line['y1'] - $line['y2'])) == abs(($line['x1'] - $p[0]) / ($line['y1'] - $p[1]));

    }
}
