<?php

use Chip\InterestAccount\Infrastructure\Scheduler\PayoutScheduler;


$container = require '/Users/mauricio.junior/workspace/interest-account/app/bootstrap.php';
$payoutScheduler = $container->get(PayoutScheduler::class);

return $payoutScheduler->execute();
