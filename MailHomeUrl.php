<?php

namespace hughcube\helpers;

class MailHomeUrl
{
    public function getHomeUrl($email)
    {
        if (!Check::isEmail($email, false)) {
            return false;
        }

        list($user, $domain) = explode('@', $email);
        $domain = strtolower($domain);

        if (isset(static::$mailUrl[$domain])) {
            return static::$mailUrl[$domain];
        } else {
            return [
                'name' => '',
                'url' => 'http://mail.' . $domain
            ];
        }
    }

    public static $mailUrl = [
        'sina.com' => [
            'name' => 'SINA',
            'url' => 'http://mail.sina.com.cn/index.html?from=mail'
        ],
        'vip.sina.com' => [
            'name' => 'SINA',
            'url' => 'http://vip.sina.com.cn'
        ],
        'sina.cn' => [
            'name' => 'SINA',
            'url' => 'http://mail.sina.com.cn/cnmail'
        ],
        '2008.sina.com' => [
            'name' => 'SINA',
            'url' => 'http://mail.2008.sina.com.cn'
        ],
        'sohu.com' => [
            'name' => 'SOHU',
            'url' => 'http://mail.sohu.com'
        ],
        'vip.sohu.com' => [
            'name' => 'SOHU',
            'url' => 'http://vip.sohu.com'
        ],
        '163.com' => [
            'name' => '163',
            'url' => 'http://mail.163.com'
        ],
        'vip.163.com' => [
            'name' => '163',
            'url' => 'http://vip.mail.163.com'
        ],

        'qq.com' => [
            'name' => 'QQ',
            'url' => 'http://mail.qq.com'
        ],
        'vip.qq.com' => [
            'name' => 'QQ',
            'url' => 'http://vip.mail.qq.com'
        ],
        'tom.com' => [
            'name' => 'TOM',
            'url' => 'http://mail.tom.com'
        ],
        'vip.tom.com' => [
            'name' => 'TOM',
            'url' => 'http://vip.tom.com'
        ],
        'hotmail.com' => [
            'name' => 'HOTMAIL',
            'url' => 'http://www.hotmail.com'
        ],
        'gmail.com' => [
            'name' => 'GMAIL',
            'url' => 'http://www.gmail.com'
        ],
        'msn.com' => [
            'name' => 'MSN',
            'url' => 'http://my.msn.com'
        ],
        'live.com' => [
            'name' => 'MSN',
            'url' => 'http://mail.live.com'
        ],
        'yahoo.com.cn' => [
            'name' => 'YAHOO',
            'url' => 'http://mail.cn.yahoo.com'
        ],
        'yahoo.cn' => [
            'name' => 'YAHOO',
            'url' => 'http://mail.cn.yahoo.com'
        ],
        '126.com' => [
            'name' => '126',
            'url' => 'http://www.126.com'
        ],
        '139.cn' => [
            'name' => '139',
            'url' => 'http://mail.139.com'
        ],
        'yeah.net' => [
            'name' => 'YEAH',
            'url' => 'http://mail.yeah.net'
        ],
        '188.com' => [
            'name' => '188',
            'url' => 'http://www.188.com'
        ],
        'sogou.com' => [
            'name' => 'SOGOU',
            'url' => 'http://mail.sogou.com'
        ],
        '189.cn' => [
            'name' => '189',
            'url' => 'http://www.189.cn'
        ],
        '21cn.com' => [
            'name' => '21CN',
            'url' => 'http://mail.21cn.com'
        ],
        '21cn.net' => [
            'name' => '21CN',
            'url' => 'http://mail.21cn.com/net'
        ],
        'eyou.com' => [
            'name' => 'EYOU',
            'url' => 'http://www.eyou.com'
        ]
    ];
}
