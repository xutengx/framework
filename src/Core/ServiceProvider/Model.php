<?php

declare(strict_types = 1);
namespace Gaara\Core\ServiceProvider;

use Gaara\Core\{Cache, ServiceProvider};
use Gaara\Core\Model\Connection\{Connection, PersistentConnection};
use Xutengx\Model\Model as ModelObj;

/**
 * Class Model
 * @package Gaara\Core\ServiceProvider
 */
class Model extends ServiceProvider {

	/**
	 * 对象的注册绑定
	 * @return void
	 */
	public function register(): void {
		ModelObj::init($this->kernel->make(Cache::class), $this->kernel['db']['connection']);
		$conn = $this->kernel->cli === true ? PersistentConnection::class : Connection::class;
		foreach ($this->kernel['db']['connections'] as $connection => $info) {
			ModelObj::addConnection($connection,
				new $conn($info['write'] ?? [], $info['read'] ?? [], $this->kernel['server/mysql']['pdo_attr'],
					$this->kernel['server/mysql']['ini_sql']));
		}

	}

}