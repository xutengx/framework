<?php

declare(strict_types = 1);
namespace Gaara\Core\ServiceProvider;

use Gaara\Core\{Request, Route as RouteObj, ServiceProvider};
use Xutengx\Route\Component\MatchedRouting;

/**
 * Class Cache
 * @package Gaara\Core\ServiceProvider
 */
class Route extends ServiceProvider {

	/**
	 * 对象的注册绑定
	 * @return void
	 */
	public function register(): void {
		$this->kernel->singleton(RouteObj::class, function() {
			return new RouteObj($this->kernel->make(Request::class), $this->kernel['route']['file'],
				$this->kernel->make(MatchedRouting::class));
		});

	}

}