<?php

// 开发, 测试, demo 功能3合1
namespace Apptest\Dev\development\Contr;

use Gaara\Core\Controller;
use Apptest\development\Dev\test;
use Apptest\development\Dev\asyncDev;
use Apptest\development\Model;
use Cache;
use Request;

class indexContr extends Controller {
	
	public function indexDo(Request $request, $test, \Gaara\Core\Cache $c, \HTMLPurifier $htmlpurifier) {
		$passwd = password_hash('123123', PASSWORD_DEFAULT);
		var_dump($passwd);
		$passwd2 = password_hash('123123', PASSWORD_BCRYPT);
		var_dump($passwd2);
		$passwd3 = password_hash('123123', PASSWORD_BCRYPT);
		var_dump($passwd3);
		
		$s12 = '123123123131';
		$qeqweq = $s12;
		$qweqwe = $qeqweq;
		var_dump(password_verify(1231231, $passwd));
		
		exit();
		// Cache::set('www','www1',4);
		// $e = $c->get('www');
		
		// var_dump($request);exit;
		var_dump($purifier->purify($dirty_html));
		
		exit();
		
		$sql = Model\visitorInfoModel::select(['id','name','phone'])->where(['id' => ['>','101']])
			->where(['id' => ['<','104']])
			->getAll();
		var_dump($sql);
		exit();
		
		echo 'qewq';
		// $this->display();
	}
	
	private function test($request) {
		// 'account' => '/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/',
		var_dump($request->get);
		var_dump($this->get());
		exit();
		exit('test');
	}
	
	public function tt(?string $ttt = null): void {
		echo 'qweqwe';
		return ;
	}
	
	public function __destruct() {
		\statistic();
	}
	
}
