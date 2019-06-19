<?php

declare(strict_types = 1);
namespace Gaara\Core\ServiceProvider;

use Gaara\Core\{Secure as SecureObj, ServiceProvider};

/**
 * Class Secure
 * @package Gaara\Core\ServiceProvider
 */
class Secure extends ServiceProvider {

	/**
	 * 抽象的注册绑定
	 * @return void
	 */
	public function register(): void {
		$this->kernel->singleton(SecureObj::class);
	}
}