<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\Connection;

use Xutengx\Model\Connection\PersistentConnection as ConnectionObj;
use Gaara\Core\Model\Connection\Traits\SqlLog;

/**
 * Class PersistentConnection
 * @package Xutengx\Model\Connection
 */
class PersistentConnection extends ConnectionObj {
	use SqlLog;
}
