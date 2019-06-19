<?php

declare(strict_types = 1);
namespace Apptest\yh\c\user;
defined('IN_SYS') || exit('ACC Denied');

use Apptest\yh\m;
use Gaara\Core\Controller;

class Info extends Controller {

    public function select() {
        echo 'this is select';
    }

    public function update() {
        echo 'this is update';
    }

    public function destroy() {
        echo 'this is destroy';
    }

    public function create() {
        echo 'this is create';
    }
}
