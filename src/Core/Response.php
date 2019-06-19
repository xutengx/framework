<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Xutengx\Response\Response as ResponseObj;

class Response extends ResponseObj {

	/**
	 * 返回页面
	 * @param string $file
	 * @return Response
	 * @throws \ReflectionException
	 * @throws \Xutengx\Container\Exception\BindingResolutionException
	 * @throws \Xutengx\Exception\Http\NotAcceptableHttpException
	 */
	public function view(string $file): Response {
		$data = obj(Template::class)->view($file);
		return $this->setContentType('html')->setContent($data);
	}
}
