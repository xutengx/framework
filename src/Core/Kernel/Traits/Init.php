<?php

declare(strict_types = 1);
namespace Gaara\Core\Kernel\Traits;

use Exception;
use Gaara\Core\{Conf, Kernel, Pipeline, Request, Route};

trait Init {

	/**
	 * 初始化配置
	 * @return Kernel
	 */
	public function init(): Kernel {
		$this->execute($this, 'confInit');
		$this->registerServiceProvider();
		$this->pipeline = $this->make(Pipeline::class);
		$this->request  = $this->make(Request::class);
		$this->route    = $this->make(Route::class);
		return $this;
	}

	/**
	 * 注册服务提供者
	 */
	protected function registerServiceProvider() {
		$providers = $this->conf->app['providers'];

		foreach ($providers as $provider) {
			(new $provider($this))->alias();
		}
		foreach ($providers as $provider) {
			(new $provider($this))->boot();
		}
		foreach ($providers as $provider) {
			(new $provider($this))->register();
		}
	}

	/**
	 * 初始化配置
	 * @param Conf $conf 配置对象
	 * @return void
	 * @throws \Exception
	 */
	protected function confInit(Conf $conf): void {
		// 配置
		$confApp = $conf->app;

		// 时区
		date_default_timezone_set($confApp['timezone']);

		// php.ini
		$serverIni = $conf->getServerConf('php');
		foreach ($serverIni as $k => $v)
			if (ini_set($k, $v) === false)
				throw new Exception("ini_set($k, $v) is Fail");

		// 报错
		$this->debug = ($confApp['debug'] == '1');

		// 控制台
		$this->cli = (php_sapi_name() === 'cli');
	}

}
