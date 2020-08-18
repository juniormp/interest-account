<?php

use Chip\InterestAccount\Infrastructure\Scheduler\PayoutScheduler;

$container = require './app/bootstrap.php';
$payoutScheduler = $container->get(PayoutScheduler::class);

return $payoutScheduler->execute();
