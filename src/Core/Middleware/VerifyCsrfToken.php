<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Exception;
use Xutengx\Exception\Http\ForbiddenHttpException;
use Gaara\Core\{Middleware, Request, Response};

/**
 * CsrfToken 依赖 session(cookie)
 */
class VerifyCsrfToken extends Middleware {

	// 有效时间
	protected $effectiveTime;

	/**
	 * 初始化 过期时间
	 */
	public function __construct($effectiveTime = 3600) {
		$this->effectiveTime = (int)$effectiveTime;
	}

	/**
	 * 放置 校验token
	 * @param Request $request
	 * @return void
	 * @throws Exception
	 */
	public function handle(Request $request): void {
		if (!$this->hasSession()) {
			throw new Exception('VerifyCsrfToken is dependent on Session');
		}
		$this->addCookie($request);
		if ($this->isReturnHtml($request) && $this->isReading($request) || $this->tokensMatch($request)) {

		}
		else {
			throw new ForbiddenHttpException('Csrf Token Error');
		}
	}

	/**
	 * 检测session是否可用
	 * return bool
	 */
	protected function hasSession(): bool {
		return isset($_SESSION);
	}

	/**
	 * 生成 X-CSRF-TOKEN 加入cookie
	 * @param Request $request
	 * @return void
	 */
	protected function addCookie(Request $request): void {
		$token = $this->theToken();
		$request->setCookie('X-XSRF-TOKEN', $token, $this->effectiveTime, '', '', false, false);
	}

	/**
	 * 计算token
	 * @return string
	 */
	protected function theToken(): string {
		return md5(session_id());
	}

	/**
	 * 当前请求是否为非ajax请求
	 * @param Request $request
	 * @return bool
	 */
	protected function isReturnHtml(Request $request): bool {
		return !$request->isAjax;
	}

	/**
	 * 当前请求是否为'读'请求
	 * @param Request $request
	 * @return bool
	 */
	protected function isReading(Request $request): bool {
		return in_array($request->method, ['head', 'get', 'options']);
	}

	/**
	 * 比较是否token相等
	 * @param Request $request
	 * @return bool
	 */
	protected function tokensMatch(Request $request): bool {
		$token = $request->header('X-CSRF-TOKEN') ?? $request->header('HTTP_X_XSRF_TOKEN');
		return $token === $this->theToken();
	}

}
