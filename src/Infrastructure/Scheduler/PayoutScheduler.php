<?php

namespace Chip\InterestAccount\Infrastructure\Scheduler;

use Chip\InterestAccount\Application\ClosePayouts;
use Crunz\Schedule;

class PayoutScheduler
{
    private $schedule;
    private $closePayouts;
    private const DESCRIPTION = "Start the routine to calculate payouts for all users.";

    public function __construct(Schedule $schedule, ClosePayouts $closePayouts)
    {
        $this->closePayouts = $closePayouts;
        $this->schedule = $schedule;
    }

    public function execute(): Schedule
    {
        $closePayouts = $this->closePayouts;

        $task = $this->schedule->run(function () use ($closePayouts) {
            $closePayouts->execute();
        });

        $task
            ->everySeventyTwoHours()
            ->description(PayoutScheduler::DESCRIPTION);

        return $this->schedule;
    }
}
