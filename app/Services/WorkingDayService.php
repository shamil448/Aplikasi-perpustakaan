<?php

namespace App\Services;

use App\Models\Holiday;
use Carbon\Carbon;

class WorkingDayService
{
    /**
     * Menghitung jumlah hari kerja
     * antara jatuh tempo dan hari ini.
     */
    public static function lateDays(Carbon $dueDate, Carbon $today): int
    {
        $days = 0;

        $date = $dueDate->copy()->addDay();

        while ($date->lte($today)) {

            // Minggu
            if ($date->isSunday()) {
                $date->addDay();
                continue;
            }

            // Sabtu
            if ($date->isSaturday()) {
                $date->addDay();
                continue;
            }

            // Hari Libur Nasional
            $holiday = Holiday::whereDate(
                'tanggal',
                $date
            )->exists();

            if ($holiday) {

                $date->addDay();

                continue;
            }

            $days++;

            $date->addDay();
        }

        return $days;
    }

    public static function calculateFine(
        Carbon $dueDate,
        Carbon $today,
        int $perDay = 1000
    ): int {

        return self::lateDays(
            $dueDate,
            $today
        ) * $perDay;
    }
}