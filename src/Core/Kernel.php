<?php

declare(strict_types = 1);
namespace Gaara\Core;

use ArrayAccess;
use Closure;
use Exception;
use Gaara\Core\Kernel\Traits\{Config, Init};
use Xutengx\Container\Container;

/**
 * Class Kernel
 * @package Gaara\Core
 */
class Kernel extends Container implements ArrayAccess{

	use Init, Config;

	/**
	 * 缓存自己
	 * @var Kernel
	 */
	protected static $ins;
	/**
	 * 调试模式
	 * @var bool
	 */
	public $debug = true;
	/**
	 * 命令行
	 * @var bool
	 */
	public $cli = false;
	/**
	 * 管道对象
	 * @var Pipeline
	 */
	protected $pipeline;
	/**
	 * 请求对象
	 * @var Request
	 */
	protected $request;
	/**
	 * 路由对象
	 * @var Route
	 */
	protected $route;

	/**
	 * 全局中间件
	 * @var array
	 */
	protected $middlewareGlobal = [];
	/**
	 * 路由中间件组
	 * @var array
	 */
	protected $middlewareGroups = [];

	/**
	 * Kernel constructor.
	 */
	final protected function __construct() { }

	/**
	 * @return Kernel
	 */
	public static function getIns(): Kernel {
		return static::$ins ?? (static::$ins = new static);
	}

	/**
	 * 执行路由
	 */
	public function start() {
		$request = $this->request;
		$route   = $this->route;
		// 路由匹配成功
		if ($route->Start()) {
			// 匹配成功对象
			$MR = $route->matchedRouting;

			// 全局中间件 + 路由中间件
			$MR->middleware = $this->getMiddleware($MR->middlewareGroups);

			$request->methods = $MR->methods;

			// 路由参数
			$request->setParameters($MR->domainParameters, $MR->staticParameters);

			// 执行
			$this->run($MR->subjectMethod, $MR->urlParameters, $MR->middleware);
		}
		// 是否存在默认的404响应
		elseif (is_null($rule404 = $route->rule404))
			// 默认的404响应
			$this->make(Response::class)->setStatus(404)->setContent('Not Found ..')->sendExit();
		else
			// 设置的404响应
			$this->run($rule404, ['pathInfo' => $request->pathInfo]);
	}

	/**
	 * 将中间件数组, 加入管道流程 Pipeline::pipesPush(string)
	 * @param array $middlewareGroups
	 * @return array
	 */
	protected function getMiddleware(array $middlewareGroups): array {
		$arr = [];
		// 全局中间件
		foreach ($this['middleware']['global'] as $middleware) {
			$arr[] = $middleware;
		}
		// 路由中间件
		foreach ($middlewareGroups as $middlewareGroup) {
			foreach ($this['middleware']['groups'][$middlewareGroup] as $middleware) {
				$arr[] = $middleware;
			}
		}
		return $arr;
	}

	/**
	 * 执行中间件以及用户业务代码
	 * @param string|Closure $subjectMethod
	 * @param array $parameters
	 * @param array $middlewareArray
	 * @return void
	 */
	protected function run($subjectMethod, array $parameters = [], array $middlewareArray = []): void {
		$this->statistic();
		$this->pipeline->setPipes($middlewareArray);
		$this->pipeline->setDefaultClosure($this->doSubject($subjectMethod, $parameters));
		$this->pipeline->then();
	}

	/**
	 * 运行统计
	 * @return void
	 */
	protected function statistic(): void {
		$GLOBALS['statistic']['框架路由执行后时间'] = microtime(true);
		$GLOBALS['statistic']['框架路由执行后内存'] = memory_get_usage();
	}

	/**
	 * 主题代码
	 * @param string|Closure $subjectMethod
	 * @param array $parameters
	 * @return Closure
	 */
	protected function doSubject($subjectMethod, array $parameters = []): Closure {
		return function() use ($subjectMethod, $parameters) {
			// 形如 'app\index\Contr\IndexContr@indexDo'
			if (is_string($subjectMethod)) {
				$temp = explode('@', $subjectMethod);
				return $this->execute($temp[0], $temp[1], $parameters);
			}
			// 形如 function($param_1, $param_2 ) {return 'this is a function !';}
			elseif ($subjectMethod instanceof Closure) {
				return $this->executeClosure($subjectMethod, $parameters);
			}
			throw new Exception();
		};
	}

}
