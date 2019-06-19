<?php

declare(strict_types = 1);
namespace Gaara\Core\ServiceProvider;

use Gaara\Core\{ServiceProvider, Tool as ToolObj};

/**
 * Class Tool
 * @package Gaara\Core\ServiceProvider
 */
class Tool extends ServiceProvider {

	/**
	 * 抽象的注册绑定
	 * @return void
	 */
	public function register(): void {
		$this->kernel->singleton(ToolObj::class);
	}
}