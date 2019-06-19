<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\Connection;

use Xutengx\Model\Connection\Connection as ConnectionObj;
use Gaara\Core\Model\Connection\Traits\SqlLog;

/**
 * Class Connection 建议http环境下使用
 * @package Xutengx\Model\Connection
 */
class Connection extends ConnectionObj {
	use SqlLog;
}
