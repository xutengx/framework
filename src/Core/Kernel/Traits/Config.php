<?php

declare(strict_types = 1);
namespace Gaara\Core\Kernel\Traits;

use Gaara\Core\Conf;

trait Config {

	/**
	 * 配置对象
	 * @var Conf
	 */
	protected $conf;

	public function setConf(Conf $conf) {
		$this->conf = $conf;
	}

	public function offsetExists($offset) {

	}

	public function offsetGet($offset) {
		return $this->conf->{$offset};
	}

	public function offsetSet($offset, $value) {

	}

	public function offsetUnset($offset) {

	}

}
