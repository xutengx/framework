<?php
// 开发, 测试, demo 功能3合1
namespace Apptest\development\Contr;
use \Gaara\Core\Controller;
//use \Gaara\Core\F;
defined('IN_SYS') || exit('ACC Denied');
    
class testContr extends Controller\Controller {
    
    public function route(){
        echo 'this is route';
    }
    
    
}
