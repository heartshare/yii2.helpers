<?php

namespace hughcube\helpers;

class Check
{

    /**
     * 函数stringLength,判断一个字符串的长度是否在一个范围里面;
     *
     * @return bool;
     */
    public static function length($string, $min = 0, $max = 255)
    {
        return ($min == 0 || isset($string{$min - 1})) && !isset($string{$max});
    }

    /**
     * 判断一个数是否在一个范围内;
     *
     * @return bool;
     */
    public static function range($number, $max = null, $min = null)
    {
        if (null !== $max && $max < $number) {
            return false;
        }

        if (null !== $min && $min > $number) {
            return false;
        }

        return true;
    }

    /**
     * 函数checkSAPI,验证php的运行方式;
     * aolserver、apache、 apache2filter、apache2handler、 caudium、cgi,
     * cgi-fcgi、cli、 continuity、embed、 isapi、litespeed、 milter、nsapi、 phttpd、
     * pi3web、roxen、 thttpd、tux、webjames;
     *
     * @param serverAPI string [可选] 默认为cli,验证是否为命令行模式;
     * @return bool;
     */
    public static function sapi($serverAPI = 'cli')
    {
        return strtolower(PHP_SAPI) == strtolower($serverAPI);
    }

    /**
     * 函数checkOS,验证当前环境是否为指定系统;
     *
     * @param os ;
     * @return bool;
     */
    public static function os($os = 'Win')
    {
        return strtolower(PHP_OS) == strtolower($os);
    }

    /**
     * 函数isEmpty,判断一个字符串是否为空;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isEmpty($string, $trim = true)
    {
        $string = $trim ? trim($string) : $string;

        return empty($string);
    }

    /**
     * 函数isUtf8,判断一个字符串的编码是否为UTF-8;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isUtf8($string)
    {
        //return json_encode(array($string)) != '[null]';

        // $temp1 = @iconv("GBK", "UTF-8", $string);
        // $temp2 = @iconv("UTF-8", "GBK", $temp1);
        // return $temp1 == $temp2;

        // return preg_match('%^(?:
        //     [\x09\x0A\x0D\x20-\x7E]              # ASCII
        //     | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        //     | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
        //     | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        //     | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
        //     | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
        //     | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        //     | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
        //     )*$%xs', $string);

        return static::encoding($string, 'UTF-8');
    }

    /**
     * 函数checkEncoding,判断一个字符串的编码,该函数待完善;
     *
     * @param
     *          str string [必须] 需要判断的字符;
     * @param
     *          encoding string [可选] 字符的参考编码,默认为UTF-8;
     * @return bool;
     */
    public static function encoding($str, $encoding = 'UTF-8')
    {
        $encodingType = ['GB2312', 'UTF-8', 'ASCII', 'GBK'];

        return mb_detect_encoding($str, $encodingType) == strtoupper($encoding);
    }

    /**
     * 函数isOctal,判断一个字符串是否为八进制字符;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isOctal($string)
    {
        return !preg_match('/[^0-7]+/', $string);
    }

    /**
     * 函数isBinary,判断一个字符串是否为二进制字符;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isBinary($string)
    {
        return !preg_match('/[^01]+/', $string);
    }

    /**
     * 函数isHex,判断一个字符串是否为十六进制字符;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isHex($string)
    {
        return !preg_match('/[^0-9a-f]+/i', $string);
    }

    /**
     * 函数isAlphanum,判断一个字符串是否是数字和字母组成;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isAlphanum($string)
    {
        return ctype_alnum($string);
    }

    /**
     * 函数isAlpha,判断一个字符串是否是字母组成;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isAlpha($string)
    {
        return ctype_alpha($string);
    }

    /**
     * 函数isNaming,判断一个字符串是否是符合的命名规则;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isNaming($string)
    {
        return 1 == preg_match('/^[a-z\_][a-z1-9\_]*/i', $string);
    }

    /**
     * 函数isWhitespace,判断一个字符串是否为空白符,空格制表符回车等都被视作为空白符,类是\n\r\t;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isWhitespace($string)
    {
        return ctype_cntrl($string);
    }

    /**
     * 函数isNumeral,判断一个变量是否为数字;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isDigit($string)
    {
        return is_numeric($string) && ctype_digit(strval($string));
    }

    /**
     * 函数isEmail,判断是否是一个合法的邮箱;
     *
     * @param str string [必须] 需要判断的字符;
     * @param bool [可选] 是否判断域名,该功能只能在linux下使用,默认不判断;
     * @return bool;
     */
    public static function isEmail($string, $isStrict = false)
    {
        $result = filter_var($string, FILTER_VALIDATE_EMAIL);
        if ($result && $isStrict && static::os('linux') && function_exists('getmxrr')) {
            list($prefix, $domain) = explode('@', $string);
            $result = getmxrr($domain, $mxhosts);
        }

        return $result;
    }

    /**
     * 函数isMobile,判断是否是一个合法的手机号码;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isMobile($string)
    {
        return preg_match('/^(13[0-9]|15[0-9]|18[0-9]|14[0-9]|17[0-9])\d{8}$/', $string);
    }

    /**
     * 函数isTel,判断是否是一个合法的固定电话号码;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isTel($string)
    {
        return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/', $string);
    }

    /**
     * 函数isQQ,判断是否为一个QQ号码;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isQQ($string)
    {
        return static::isDigit($string) && 10000 < $string && 9999999999 > $string;
    }

    /**
     * 函数isZipCode,判断是否为一个邮政编码;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isZipcode($string)
    {
        return static::isDigit($string) && $string < 999999 && $string > 100000;
    }

    /**
     * 函数isIp,判断是否为一个合法的IP地址,可以适用于IPV6;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isIp($ip)
    {
        return static::isIp4($ip) || static::isIp6($ip);
    }

    public static function isIp4($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    public static function isIp6($ip)
    {
        return filter_var($ip, FILTER_FLAG_IPV6);
    }

    /**
     * 函数isUrl,判断是否为一个URL, 只有在200-299状态情况才算能够访问;
     *
     * @param str string [必须] 需要判断的字符;
     * @return bool;
     */
    public static function isUrl($url, $checkAccess = false)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        if ($checkAccess && !get_headers($url)) {
            return false;
        }

        return true;
    }

    /**
     * 函数ping,判断一个IP是否能够ping的通,该函数需要开启EXEC;
     *
     * @param ip string [必选] 需要试探的IP地址;
     * @param timeOut int [可选] 超时时间,默认为4000,单位是毫秒,1000毫秒等于一秒;
     * @return bool;
     */
    // public static function ping($host, $timeout = 1) {
    //     /* ICMP ping packet with a pre-calculated checksum */
    //     $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
    //     $socket  = @socket_create(AF_INET, SOCK_RAW, 1);
    //     @socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeout, 'usec' => 0));
    //     @socket_connect($socket, $host, null);

    //     $ts = microtime(true);
    //     @socket_send($socket, $package, strLen($package), 0);
    //     $result = @socket_read($socket, 255) ? microtime(true) - $ts : false;
    //     @socket_close($socket);

    //     return $result;
    // }

    /**
     * 函数telnet,实现php telnet的功能;
     *
     * @param ip string [必选] 需要试探的IP地址;
     * @param port int [必选] 端口;
     * @return bool;
     */
    public static function telnet($hostname, $port, $timeout = 1)
    {
        if (!static::range($port, 65535, 0) && $port !== -1) {
            return false;
        }

        if (function_exists('fSockOpen')) {
            $socket = @fSockOpen($hostname, $port, $errno, $errstr, $timeout);
            $results = false !== $socket && 0 == $errno;
            false !== $socket AND fclose($socket);

            return $results;
        }

        return true;
    }
}
