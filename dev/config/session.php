<?php

return [
	/*
	  |--------------------------------------------------------------------------
	  | session (cookie) 有效时间
	  |--------------------------------------------------------------------------
	  |
	  | 将会使用 session.cookie_lifetime 以及 session.gc_maxlifetime 设置此值
	  |
	 */
	'lifetime'  => 3600 * 24 * 7,
	/*
	  |--------------------------------------------------------------------------
	  | session js不获取cookie
	  |--------------------------------------------------------------------------
	  |
	  | 将会使用 session.cookie_httponly 设置此值
	  |
	 */
	'httponly'  => true,
	/*
	  |--------------------------------------------------------------------------
	  | session 自动开启
	  |--------------------------------------------------------------------------
	 */
	'autostart' => true,
	/*
	  |--------------------------------------------------------------------------
	  | session 驱动类型(建议redis)
	  |--------------------------------------------------------------------------
	  |
	  | 使用 redis/file 设置此值
	  |
	 */
	'driver'    => env('SESSION_DRIVER', 'redis'),
	/*
	  |--------------------------------------------------------------------------
	  | 当session使用redis驱动时的redis连接
	  |--------------------------------------------------------------------------
	  |
	  | redis 对象的依赖数组
	  |
	 */
	'redis'     => [
		'connection' => env('REDIS_CONNECTION'),
	],
	/*
	  |--------------------------------------------------------------------------
	  | 当session使用file驱动时的文件存放目录
	  |--------------------------------------------------------------------------
	  |
	  | file 对象的依赖数组
	  |
	 */
	'file'      => [
		'dir' => 'data/Session'
	]
];
