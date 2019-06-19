<?php

declare(strict_types = 1);
namespace Gaara\Core\Controller\Traits;

use Gaara\Core\Request;
use Xutengx\Exception\Http\UnprocessableEntityHttpException;
use Xutengx\Request\Exception\{IllegalArgumentException, NotFoundArgumentException};

/**
 * 请求过滤
 */
trait RequestTrait {

	/**
	 * @param string|null $key
	 * @param string|null $rule
	 * @param string|null $msg
	 * @return mixed
	 * @throws UnprocessableEntityHttpException
	 * @throws \ReflectionException
	 * @throws \Xutengx\Container\Exception\BindingResolutionException
	 */
	protected function input(string $key = null, string $rule = null, string $msg = null) {
		return $this->requestFun($key, $rule, $msg, 'input');
	}

	/**
	 * 请求参数获取, 将会中断响应
	 * @param string|null $key 字段
	 * @param string|null $rule 验证规则
	 * @param string|null $msg 验证失败后的文字响应
	 * @param string $fun 要获取的参数,所在的http方法
	 * @return mixed
	 * @throws UnprocessableEntityHttpException
	 * @throws \ReflectionException
	 * @throws \Xutengx\Container\Exception\BindingResolutionException
	 */
	protected function requestFun(string $key = null, string $rule = null, string $msg = null, string $fun = 'get') {
		$request = obj(Request::class);
		if (!is_null($key)) {
			try {
				return $request->{$fun}($key, $rule);
			} catch (NotFoundArgumentException $e) {
				return $this->requestArgumentNotFound($key, $fun, $msg, $rule);
			} catch (IllegalArgumentException $e) {
				return $this->requestArgumentInvalid($key, $fun, $msg, $rule);
			}
		}
		else {
			$array = $request->$fun();
			foreach ($array as $k => $v) {
				if ($request->hasRule($k)) {
					$array[$k] = $this->{$fun}($k, $k);
				}
			}
			return $array;
		}
	}

	/**
	 * 定义当参数不合法时的响应
	 * @param string $key
	 * @param string $fun
	 * @param string $msg
	 * @param string $rule
	 * @return void
	 * @throws UnprocessableEntityHttpException
	 */
	protected function requestArgumentInvalid(string $key, string $fun, string $msg = null, string $rule): void {
		$message = $msg ?? 'Invalid request argument : ' . $key . ' [ Rule : ' . $rule . ' ]'. ' [ Method : ' . $fun . ' ]';
		throw new UnprocessableEntityHttpException($message);
	}

	/**
	 * 定义当参数不存在时的响应
	 * @param string $key
	 * @param string $fun
	 * @param string $msg
	 * @param string $rule
	 * @return void
	 * @throws UnprocessableEntityHttpException
	 */
	protected function requestArgumentNotFound(string $key, string $fun, string $msg = null, string $rule): void {
		$message = $msg ?? 'Not found request argument : ' . $key . ' [ Method : ' . $fun . ' ]';
		throw new UnprocessableEntityHttpException($message);
	}

	protected function get(string $key = null, string $rule = null, string $msg = null) {
		return $this->requestFun($key, $rule, $msg, 'get');
	}

	protected function put(string $key = null, string $rule = null, string $msg = null) {
		return $this->requestFun($key, $rule, $msg, 'put');
	}

	protected function post(string $key = null, string $rule = null, string $msg = null) {
		return $this->requestFun($key, $rule, $msg, 'post');
	}

	protected function delete(string $key = null, string $rule = null, string $msg = null) {
		return $this->requestFun($key, $rule, $msg, 'delete');
	}

}
