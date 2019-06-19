<?php

declare(strict_types = 1);
namespace Apptest\yh\s;

use Xutengx\Request\Component\File;

/**
 * 将参数数组按键从小到大排序(注意,int均转换为string)
 * 然后依次拼接上当前时间戳(timestamp),用户令牌(token)
 * 再转化为json,再md5后加盐再md5;
 */
class Sign {

    // 盐( 前端签名用的盐 )
    const key = 'yh';
    // 请求允许的误差时间
    const expired = 600;

    /**
     * 检测登入商户平台的sign
     * @param array $param      请求中的参数 (不包含 token,timestamp,sign)
     * @param string $token     用户token
     * @param int $timestamp    时间戳
     * @param string $sign      待核对的sign
     * @return bool
     */
    public static function checkLoginSign(array $param, string $token, int $timestamp, string $sign): bool {
        if ($timestamp + self::expired < time())
            return false;
        unset($param['token']);
        unset($param['timestamp']);
        unset($param['sign']);
        foreach ($param as $k => $v){
        	// 文件类型参数不参与签名
        	if($v instanceof File){
        		unset($param[$k]);
	        }
        }
        ksort($param);
        $param['timestamp'] = $timestamp;
        $param['token'] = $token;
        $str = stripslashes(\json_encode($param, JSON_UNESCAPED_UNICODE));

        return ($sign === md5(md5($str) . self::key));
    }    
    
    /**
     * 检测支付对接的sign
     * @param array $param      请求中的参数 (不包含 token,timestamp,sign)
     * @param string $token     用户token
     * @param int $timestamp    时间戳
     * @param string $sign      待核对的sign
     * @param string $key       商户对接的盐    main_user.secret
     * @return bool
     */
    public static function checkApiSign(array $param, string $token, int $timestamp, string $sign, string $key = ''): bool {
        if ($timestamp + self::expired < time())
            return false;
        unset($param['token']);
        unset($param['timestamp']);
        unset($param['sign']);
        ksort($param);
        $param['timestamp'] = $timestamp;
        $param['token'] = $token;
        $str = \json_encode($param, JSON_UNESCAPED_UNICODE);
        return ($sign === md5(md5($str) . $key));
    }
}
