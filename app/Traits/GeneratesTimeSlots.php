<?php

namespace App\Traits;

use Carbon\Carbon;

trait GeneratesTimeSlots
{
    public function getAvailableTimeSlots()
    {
        $startTime = Carbon::createFromTime(9, 0); // Start at 9am
        $endTime = Carbon::createFromTime(17, 0); // End at 5pm
        $interval = 30; // 30 minutes interval
        $timeSlots = [];

        while ($startTime->lessThanOrEqualTo($endTime)) {
            $timeSlots[] = $startTime->format('H:i');
            $startTime->addMinutes($interval);
        }

        return $timeSlots;
    }
}
