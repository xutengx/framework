<?php

declare(strict_types = 1);
namespace Gaara\Core\ServiceProvider;

use Gaara\Core\Response as ResponseObj;
use Gaara\Core\ServiceProvider;

/**
 * Class Response
 * @package Gaara\Core\ServiceProvider
 */
class Response extends ServiceProvider {

	/**
	 * 抽象的注册绑定
	 * @return void
	 */
	public function register(): void {
		$this->kernel->singleton(ResponseObj::class);
	}
}