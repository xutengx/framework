<?php

return [
	/*
	  |--------------------------------------------------------------------------
	  | PDO对象实例化属性
	  |--------------------------------------------------------------------------
	  | 在每次 mysql 数据库连接时, 都会调用
	  | http://php.net/manual/zh/pdo.setattribute.php
	 */
	'pdo_attr' => [
		/*
		  |--------------------------------------------------------------------------
		  | PDO 初始化命令
		  |--------------------------------------------------------------------------
		  | ONLY_FULL_GROUP_BY				`严格group(与oracle一致)`
		  | STRICT_TRANS_TABLES				`严格模式, 进行数据的严格校验，错误数据不能插入，报error错误`
		  | ERROR_FOR_DIVISION_BY_ZERO		`如果被零除(或MOD(X，0))，则产生错误(否则为警告)`
		  | NO_AUTO_CREATE_USER				`防止GRANT自动创建新用户，除非还指定了密码`
		  | NO_ENGINE_SUBSTITUTION			`如果需要的存储引擎被禁用或未编译，那么抛出错误`
		 */
		PDO::MYSQL_ATTR_INIT_COMMAND       => "SET SESSION SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'",
		/*
		  |--------------------------------------------------------------------------
		  | 指定超时的秒数
		  |--------------------------------------------------------------------------
		  | int
		  |
		 */
		PDO::ATTR_TIMEOUT                  => 60,
		/*
		  |--------------------------------------------------------------------------
		  | PDO 错误模式
		  |--------------------------------------------------------------------------
		  | PDO::ERRMODE_EXCEPTION			`抛出 exceptions 异常`
		  | PDO::ERRMODE_WARNING			`引发 E_WARNING 错误`
		  | PDO::ERRMODE_SILENT				`仅设置错误代码`
		  |
		 */
		PDO::ATTR_ERRMODE                  => PDO::ERRMODE_EXCEPTION,
		/*
		  |--------------------------------------------------------------------------
		  | 启用或禁用预处理语句的模拟
		  |--------------------------------------------------------------------------
		  | true
		  | false
		  |
		 */
		PDO::ATTR_EMULATE_PREPARES         => false,
		/*
		  |--------------------------------------------------------------------------
		  | 是否使用缓冲查询
		  |--------------------------------------------------------------------------
		  | true
		  | false
		  | 无缓冲模式下,MySQL查询执行查询,同时数据等待从MySQL服务器进行获取,
		  | 在PHP取回所有结果前,在当前数据库连接下不能发送其他的查询请求
		  |
		 */
		PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false,
		/*
		  |--------------------------------------------------------------------------
		  | 强制列名为指定的大小写
		  |--------------------------------------------------------------------------
		  | PDO::CASE_LOWER					`强制列名小写`
		  | PDO::CASE_NATURAL				`保留数据库驱动返回的列名`
		  | PDO::CASE_UPPER					`强制列名大写`
		  |
		 */
		PDO::ATTR_CASE                     => PDO::CASE_LOWER,
		/*
		  |--------------------------------------------------------------------------
		  | 转换 NULL 和空字符串
		  |--------------------------------------------------------------------------
		  | PDO::NULL_NATURAL				`不转换`
		  | PDO::NULL_EMPTY_STRING			`将空字符串转换成 NULL`
		  | PDO::NULL_TO_STRING				`将 NULL 转换成空字符串`
		  |
		 */
		PDO::ATTR_ORACLE_NULLS             => PDO::NULL_TO_STRING,
		/*
		  |--------------------------------------------------------------------------
		  | 提取的时候将数值转换为字符串
		  |--------------------------------------------------------------------------
		  | true
		  | false
		  |
		 */
		PDO::ATTR_STRINGIFY_FETCHES        => false,
		/*
		  |--------------------------------------------------------------------------
		  | 是否自动提交每个单独的语句
		  |--------------------------------------------------------------------------
		  | true
		  | false
		  |
		 */
		PDO::ATTR_AUTOCOMMIT               => true,
	],
	/*
	  |--------------------------------------------------------------------------
	  | 会话初始sql
	  |--------------------------------------------------------------------------
	  | 在每次数据库连接时, 都会调用
	  |
	 */
	'ini_sql'  => [
		/*
		  |--------------------------------------------------------------------------
		  | 设置数据库事物隔离级别
		  |--------------------------------------------------------------------------
		  | READ UNCOMMITTED			`读未提交`
		  | READ COMMITTED				`读已提交`
		  | REPEATABLE READ				`可重复读`
		  | SERIALIZABLE				`可串行化`
		  |
		 */
		'SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ',
		/*
		  |--------------------------------------------------------------------------
		  | 设置数据库字符集
		  |--------------------------------------------------------------------------
		  | UTF8
		  |
		 */
		'SET NAMES UTF8',
	]
];
