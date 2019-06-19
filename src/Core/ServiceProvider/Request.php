<?php

declare(strict_types = 1);
namespace Gaara\Core\ServiceProvider;

use Gaara\Core\{Request as RequestObj, ServiceProvider, Tool};
use Xutengx\Request\Component\{File};

/**
 * Class Request
 * @package Gaara\Core\ServiceProvider
 */
class Request extends ServiceProvider {

	/**
	 * 抽象的注册绑定
	 * @return void
	 */
	public function register(): void {
		$this->kernel->singleton(RequestObj::class);
		$this->kernel->bind(File::class, function() {
			return new File($this->kernel->make(Tool::class), $this->kernel['app']['storage']);
		});
	}
}