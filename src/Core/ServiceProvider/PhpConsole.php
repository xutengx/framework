<?php

declare(strict_types = 1);
namespace Gaara\Core\ServiceProvider;

use Gaara\Core\ServiceProvider;
use Gaara\Expand\PhpConsole as PhpConsoleObj;

/**
 * Class PhpConsole
 * @package Gaara\Core\ServiceProvider
 */
class PhpConsole extends ServiceProvider {


	/**
	 * 对象的注册绑定
	 * @return void
	 */
	public function register(): void {
		// 单例缓存对象
		$this->kernel->singleton(PhpConsoleObj::class);
	}

}