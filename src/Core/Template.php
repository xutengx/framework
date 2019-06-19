<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Gaara\Expand\JavaScriptPacker;

/**
 * Class Template
 * @package Gaara\Core
 */
class Template {

	/**
	 * jquery 引入
	 * @var string
	 */
	const jqueryDir = '/Views/include/jquery/';
	/**
	 * js 引入
	 * @var string
	 */
	const jsDir = '/Views/include/js/';
	/**
	 * js_plugins 引入
	 * @var string
	 */
	const pluginsDir = '/Views/include/plugins/';
	/**
	 * js_plugins 所需的css 引入
	 * @var string
	 */
	const pluginsCssDir = '/Views/include/css/';
	/**
	 * Views目录
	 * @var string
	 */
	protected $dir;
	/**
	 * 跳转中间页面
	 * @var string
	 */
	protected $jumpTo = 'jumpTo';
	/**
	 * 自动压缩后js 存放的目录, public 下
	 * @var string
	 */
	protected $openMinStatic = 'open/minStatic/';

	/**
	 * Template constructor.
	 * @param string|null $dir
	 */
	public function __construct(string $dir = null) {
		$this->dir = $dir ?? dirname(__DIR__);
	}
	/**
	 * 插入页面内容
	 * @param string $file
	 * @return bool
	 */
	//	public function show(string $file): bool {
	//		include ROOT . 'App/' . APP . '/View/template/' . $file . '.html';
	//		return true;
	//	}

	/**
	 * 返回页面内容
	 * @param string $file
	 * @return string
	 */
	public function view(string $file): string {
		return file_get_contents(dirname(__DIR__) . '/Views/tpl/' . $file . '.html');
	}

	/**
	 * 跳转中间页
	 * @param string $url
	 * @param string $message
	 * @param int $waitSecond
	 */
	public function jumpTo(string $url, string $message, int $waitSecond = 3) {
		include $this->dir . '/Views/tpl/' . $this->jumpTo . '.html';
		exit;
	}

	/**
	 * 自动加载静态文件 , 目前仅在控制器父类 Controller->display() 中调用并缓存
	 * @return string JS引入语句 (直接echo即可使用)
	 * @throws \ReflectionException
	 * @throws \Xutengx\Container\Exception\BindingResolutionException
	 */
	public function includeFiles(): string {
		$str = '';
		$str .= $this->createMin($this->dir . self::jqueryDir);
		$str .= '<script>jQuery.extend({inpath:"' . $this->openMinStatic . '"});</script>';
		$str .= $this->createMin($this->dir . self::jsDir);
		$this->createMin($this->dir . self::pluginsDir);
		$this->createMin($this->dir . self::pluginsCssDir);
		return $str;
	}

	/**
	 * 生成压缩文件
	 * @param string $originalDir 需要压缩的js所在目录
	 * @param string $newDir 压缩后的js存放目录
	 * @return string JS引入语句 (直接echo即可使用)
	 * @throws \ReflectionException
	 * @throws \Xutengx\Container\Exception\BindingResolutionException
	 */
	protected function createMin(string $originalDir, $newDir = null): string {
		$newDir = $newDir ?? $this->openMinStatic;
		$files  = obj(Tool::class)->getFiles($originalDir);
		$str    = '';
		foreach ($files as $v) {
			$ext = strrchr($v, '.');
			if ($ext === '.js') {
				$jsName = $newDir . str_replace($originalDir, '', $v);
				if (!is_file($jsName) || filemtime($v) > filectime($jsName)) {
					$content = $this->automaticPacking(file_get_contents($v));
					obj(Tool::class)->filePutContents($jsName, $content);
				}
				$str .= '<script src="' . obj(Request::class)->hostStaticInfo . $jsName . '"></script>';
			}
			elseif ($ext === '.css') {
				$jsName = $newDir . str_replace($originalDir, '', $v);
				if (!is_file($jsName) || filemtime($v) > filectime($jsName)) {
					$content = $this->compressCss(file_get_contents($v));
					obj(Tool::class)->printInFile($jsName, $content);
				}
				$str .= '<link rel="stylesheet" type="text/css" href="' . obj(Request::class)->hostStaticInfo .
				        $jsName . '" />';
			}
		}
		return $str;
	}

	/**
	 * 压缩js,比较2种模式
	 * @param string $content 压缩前js内容
	 * @return string 压缩后js
	 */
	protected function automaticPacking(string $content): string {
		$packerNormal = (new JavaScriptPacker($content, 'Normal', false, false))->pack();
		$packerNone   = (new JavaScriptPacker($content, 'None', false, false))->pack();
		return strlen($packerNormal) > strlen($packerNone) ? $packerNone : $packerNormal;
	}

	/**
	 * 压缩css
	 * @param string $content 压缩前css内容
	 * @return string 压缩后css内容
	 */
	protected function compressCss(string $content): string {
		/* remove comments */
		$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
		/* remove tabs, spaces, newlines, etc. */
		$content = str_replace([
			"
",
			"\r",
			"\n",
			"\t",
			' ',
			'  ',
			'   ',
			'    '
		], '', $content);
		return $content;
	}

}
