<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\Connection\Traits;

use Gaara\Core\Log;

trait SqlLog {

	/**
	 * 普通sql记录
	 * @param string $sql
	 * @param array $bindings
	 * @param bool $manual
	 * @return bool
	 * @throws \ReflectionException
	 * @throws \Xutengx\Container\Exception\BindingResolutionException
	 */
	protected function logInfo(string $sql, array $bindings = [], bool $manual = false): bool {
		return obj(Log::class)->dbInfo('', [
			'sql'            => $sql,
			'bindings'       => $bindings,
			'manual'         => $manual,
			'connection'     => $this->connection,
			'masterSlave'    => $this->masterSlave,
			'type'           => $this->type,
			'transaction'    => $this->transaction,
			'conn'           => static::class,
			'identification' => $this->identification
		]);
	}

	/**
	 * 异常sql记录
	 * @param string $msg
	 * @param string $sql
	 * @param array $bindings
	 * @param bool $manual
	 * @return bool
	 * @throws \ReflectionException
	 * @throws \Xutengx\Container\Exception\BindingResolutionException
	 */
	protected function logError(string $msg, string $sql, array $bindings = [], bool $manual = false): bool {
		return obj(Log::class)->dbError($msg, [
			'sql'            => $sql,
			'bindings'       => $bindings,
			'manual'         => $manual,
			'connection'     => $this->connection,
			'masterSlave'    => $this->masterSlave,
			'type'           => $this->type,
			'transaction'    => $this->transaction,
			'conn'           => static::class,
			'identification' => $this->identification
		]);
	}
}