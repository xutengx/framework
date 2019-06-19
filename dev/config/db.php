<?php

return [
	/*
	  |--------------------------------------------------------------------------
	  | 数据库默认使用的数据库连接
	  |--------------------------------------------------------------------------
	  |
	  |
	 */
	'connection'  => 'default',

	/*
	  |--------------------------------------------------------------------------
	  | 数据库可使用的数据库连接
	  |--------------------------------------------------------------------------
	  |
	  |
	 */
	'connections' => [
		'default' => [
			'write' => [
				[
					'weight' => 10,
					'type'   => 'mysql',
					'host'   => '127.0.0.1',
					'port'   => 3306,
					'user'   => 'root',
					'pwd'    => 'root',
					'db'     => 'yh',
				]
			],
			'read'  => [
				[
					'weight' => 1,
					'type'   => 'mysql',
					'host'   => '127.0.0.1',
					'port'   => 3306,
					'user'   => 'root',
					'pwd'    => 'root',
					'db'     => 'yh',
				],
				[
					'weight' => 1,
					'type'   => 'mysql',
					'host'   => '127.0.0.1',
					'port'   => 3306,
					'user'   => 'root',
					'pwd'    => 'root',
					'db'     => 'yh',
				]
			]
		],
		'con2'    => [
			'write' => [
				[
					'weight' => 10,
					'type'   => 'mysql',
					'host'   => '47.90.124.253',
					'port'   => 21406,
					'user'   => 'cdr',
					'pwd'    => 'DB_BEST_PASSWORD',
					'db'     => 'cdr_report',
				]
			]
		],
		'con3'    => [
			'write' => [
				[
					'weight' => 10,
					'type'   => 'mysql',
					'host'   => '127.0.0.1',
					'port'   => 3306,
					'user'   => 'root',
					'pwd'    => 'root',
					'db'     => 'test'
				]
			],
			'read'  => [
				[
					'weight' => 1,
					'type'   => 'mysql',
					'host'   => '127.0.0.1',
					'port'   => 3306,
					'user'   => 'root',
					'pwd'    => 'root',
					'db'     => 'test'
				],
				[
					'weight' => 2,
					'type'   => 'mysql',
					'host'   => '127.0.0.1',
					'port'   => 3306,
					'user'   => 'root',
					'pwd'    => 'root',
					'db'     => 'test'
				]
			]
		]
	]
];
