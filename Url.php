<?php

namespace hughcube\helpers;

class Url extends \yii\helpers\Url
{
    public static function appendQuery($url, array $query = [])
    {
        if (empty($query)) {
            return $url;
        }

        $parseUrl = parse_url($url);
        if (isset($parseUrl['query'])) {
            parse_str($parseUrl['query'], $queryParams);
        } else {
            $queryParams = [];
        }
        $queryParams = array_merge($queryParams, $query);
        $parseUrl['query'] = http_build_query($queryParams);
        $scheme = isset($parseUrl['scheme']) ? $parseUrl['scheme'] . '://' : '';
        $host = isset($parseUrl['host']) ? $parseUrl['host'] : '';
        $port = isset($parseUrl['port']) ? ':' . $parseUrl['port'] : '';
        $user = isset($parseUrl['user']) ? $parseUrl['user'] : '';
        $pass = isset($parseUrl['pass']) ? ':' . $parseUrl['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parseUrl['path']) ? $parseUrl['path'] : '';
        $query = isset($parseUrl['query']) ? '?' . $parseUrl['query'] : '';
        $fragment = isset($parseUrl['fragment']) ? '#' . $parseUrl['fragment'] : '';

        return "{$scheme}{$user}{$pass}{$host}{$port}{$path}{$query}{$fragment}";
    }
}
