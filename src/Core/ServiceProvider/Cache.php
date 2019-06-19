<?php

declare(strict_types = 1);
namespace Gaara\Core\ServiceProvider;

use Gaara\Core\{ServiceProvider, Cache as CacheObj};
use Xutengx\Cache\Driver\{File, Redis};
use Xutengx\Contracts\Cache\Driver;

/**
 * Class Cache
 * @package Gaara\Core\ServiceProvider
 */
class Cache extends ServiceProvider {

	/**
	 * 对象的注册绑定
	 * @return void
	 */
	public function register(): void {
		// 单例缓存对象
		$this->kernel->singleton(CacheObj::class);

		// 文件缓存对象实现
		$this->kernel->bind(File::class, function() {
			return new File($this->kernel['cache']['file']['dir']);
		});

		// 内存缓存对象实现
		$this->kernel->bind(Redis::class, function() {
			$redisConfig = $this->kernel['redis'][$this->kernel['cache']['redis']['connection']];
			return new Redis(...$redisConfig);
		});

		// 对象实现选择
		$driver = $this->kernel['cache']['driver'] === 'redis' ? Redis::class : File::class;
		$this->kernel->bind(Driver::class, $driver);

	}

}