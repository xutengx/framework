<?php

declare(strict_types = 1);
namespace Gaara\Core;

/**
 * Class ServiceProvider
 * @package Gaara\Core
 */
abstract class ServiceProvider {

	/**
	 * @var Kernel
	 */
	protected $kernel;

	/**
	 * ServiceProvider constructor.
	 * @param Kernel $kernel
	 */
	public function __construct(Kernel $kernel) {
		$this->kernel = $kernel;
	}

	/**
	 * 抽象的初始化
	 * @return void
	 */
	public function boot(): void {

	}

	/**
	 * 抽象的别名
	 * @return void
	 */
	public function alias(): void{

	}

	/**
	 * 抽象的注册绑定
	 * @return void
	 */
	public function register(): void {

	}
}