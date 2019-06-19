<?php

namespace Apptest\jurisdiction\Contr;
use \Apptest\jurisdiction\jurisdictionContr;
defined('IN_SYS') || exit('ACC Denied');

class enable extends jurisdictionContr {
    public function index(){
        return '     这个控制器, 你如果访问到, 说明 is ok';
    }
    
    
}
