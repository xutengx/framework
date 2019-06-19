<?php

declare(strict_types = 1);
namespace Gaara\Core\ServiceProvider;

use Gaara\Core\{ServiceProvider, Tool, Session as SessionObj};
use Xutengx\Contracts\Session\Driver;
use Xutengx\Session\Driver\{File, Redis};

/**
 * Class Session
 * @package Gaara\Core\ServiceProvider
 */
class Session extends ServiceProvider {

	/**
	 * 抽象的注册绑定
	 * @return void
	 */
	public function register(): void {
		$this->kernel->singleton(SessionObj::class);

		// 文件缓存对象实现
		$this->kernel->bind(File::class, function() {
			return new File($this->kernel['session']['file']['dir'], $this->kernel->make(Tool::class));
		});

		// 内存缓存对象实现
		$this->kernel->bind(Redis::class, function() {
			$redisConfig = $this->kernel['redis'][$this->kernel['session']['redis']['connection']];
			return new Redis(...$redisConfig);
		});

		// 对象实现选择
		$driver = $this->kernel['session']['driver'] === 'redis' ? Redis::class : File::class;
		$this->kernel->bind(Driver::class, $driver);
	}
}