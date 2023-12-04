<?php

declare(strict_types=1);

use Doctrine\Bundle;

/** @var Bundle\DoctrineBundle\Registry $registry */
$registry = require __DIR__.'/doctrine-registry.php';

return $registry->getManager('default');
