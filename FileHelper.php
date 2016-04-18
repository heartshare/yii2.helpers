<?php

namespace hughcube\helpers;

use yii;

class FileHelper extends \yii\helpers\FileHelper
{

    /**
     * 函数directorySize,用于获取一个目录的大小,单位@字节;
     *
     * @param directory array   [必须]    需要统计的目录;
     * @return int;
     */
    public static function directorySize($directory)
    {
        $directorySize = 0;
        if (is_dir($directory)) {
            if ($handle = @opendir($directory)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..') {
                        $thePath = $directory . DIRECTORY_SEPARATOR . $file;
                        if (is_dir($thePath)) {
                            $directorySize += call_user_func(__METHOD__, $thePath);
                        } elseif (is_file($thePath)) {
                            $directorySize += filesize($thePath);
                        }
                    }
                }
                closedir($handle);
            }
        } elseif (is_file($directory)) {
            $directorySize += filesize($directory);
        }

        return $directorySize;
    }

    /**
     * 读取文件;
     *
     * @param  string $file 文件名
     * @param  boolean $lockNB 是否堵塞;
     * @return string 文件内容
     */
    public static function getContents($file, $lockNB = false)
    {
        $contents = false;
        if (false !== ($fp = fopen($file, 'r'))) {
            if (flock($fp, $lockNB ? (LOCK_SH | LOCK_NB) : LOCK_SH)) {
                $contents = stream_get_contents($fp);
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }

        return $contents;
    }

    /**
     * 安全写入文件内容, 文件不存在就创建文件;
     *
     * @param  string $file 文件名
     * @param  string $string 写入的内容
     * @param  boolean $isAppend 是否使用追加的方式写入;
     * @param  boolean $lockNB 是否堵塞;
     * @param  [type]  $fileMode 创建文件的赋予的权限;
     * @return boolean;
     */
    public static function putContents($file, $string, $isAppend = false, $lockNB = false, $fileMode = null)
    {
        $result = false;
        if (false !== ($fp = fopen($file, $isAppend ? 'a' : 'w'))) {
            if (flock($fp, $lockNB ? (LOCK_EX | LOCK_NB) : LOCK_EX)) {
                $result = fwrite($fp, $string);
                null === $fileMode OR chmod($file, intval($fileMode));
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }

        return $result;
    }

    /**
     * 优化include, 主要防止变量污染
     *
     * @param $path
     * @param null $default
     * @return mixed|null
     */
    public static function getInclude($file, $checkExist = false, $default = null)
    {
        if ($checkExist) {
            return is_file($file) ? include $file : $default;
        } else {
            return include $file;
        }
    }
}
