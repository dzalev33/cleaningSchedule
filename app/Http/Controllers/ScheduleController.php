<?php

namespace App\Http\Controllers;


use App\Models\Schedules;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ScheduleController extends Controller
{

    public function index()
    {
        $workingDaysDates = Schedules::all()->toArray();
        $vacuming = $this->loopvacuumingEveryTuesdayAndThursday();
        $getDayDates = $this->getDayDates();

        return view('schedule', compact('workingDaysDates'));
    }

    public function vacuming()
    {
        return 'Vacuuming 21minutes';
    }

    public function loopvacuumingEveryTuesdayAndThursday()
    {
        $vacuming = $this->vacuming();
        $addMonth = $this->getNext3Months();

        foreach ($addMonth as $month) {
            $nowTimeDate = Carbon::createFromDate($month)->toDateString();
            $lastDayofMonth = Carbon::parse($nowTimeDate)->endOfMonth()->toDateString();
            $period = CarbonPeriod::between($nowTimeDate, $lastDayofMonth)->filter('isWeekday');
            foreach ($period as $date) {
                if ($date->isThursday() || $date->isTuesday()) {
                    $days[] = $date->format('Y-m-d');
                }
            }
        }
        return $days;
    }

    public function getWorkDaysForTheNextThreeMonths()
    {
        $nowTimeDate = Carbon::createFromDate(2021, 2, 1)->toDateString();
        $lastDayofMonth = Carbon::parse($nowTimeDate)->endOfMonth()->addMonths(2)->toDateString();
        $period = CarbonPeriod::between($nowTimeDate, $lastDayofMonth)->filter('isWeekday');

        foreach ($period as $date) {
            $days[] = $date->dayName;

        }
        return $days;
    }

    public function getDayDates()
    {
        $nowTimeDate = Carbon::createFromDate(2021, 2, 1)->toDateString();
        $lastDayofMonth = Carbon::parse($nowTimeDate)->endOfMonth()->addMonths(2)->toDateString();
        $period = CarbonPeriod::between($nowTimeDate, $lastDayofMonth)->filter('isWeekday');

        foreach ($period as $date) {
            $days[] = $date->format('Y-m-d');
        }

        $daysName = $this->getWorkDaysForTheNextThreeMonths();
        $codes = $days;
        $names = $daysName;
        foreach (array_combine($codes, $names) as $code => $name) {

            if ($name === 'Thursday' || $name === 'Tuesday') {
                Schedules::insert([
                    'working_days_date' => $code,
                    'working_days' => $name,
                    'activities' => 'cisti'
                ]);

            } else {
                Schedules::insert([
                    'working_days_date' => $code,
                    'working_days' => $name,
                    'activities' => ''

                ]);
            }
        }
    }

    public function vacuumingEveryTuesdayAndThursday()
    {
        //● Vacuuming is done every Tuesday and Thursday
        $nowTimeDate = Carbon::createFromDate(2021, 4, 1)->toDateString();
        $lastDayofMonth = Carbon::parse($nowTimeDate)->endOfMonth()->toDateString();

        $period = CarbonPeriod::between($nowTimeDate, $lastDayofMonth)->filter('isWeekday');

        foreach ($period as $date) {
            if ($date->isThursday() || $date->isTuesday()) {
                $days[] = $date->format('Y-m-d');
            }
        }
        return $days;
    }


    public function refrigeratorCleaning()
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->year;

        $currentMonth = Carbon::createFromDate($year, $month, 1)->toDateString();
        $lastDayOfMonth = Carbon::parse($currentMonth)->endOfMonth()->toDateString();
        $period = CarbonPeriod::between($currentMonth, $lastDayOfMonth)->filter('isWeekday');

        foreach ($period as $date) {
            if ($date->isThursday() || $date->isTuesday()) {
                $days[] = $date->format('Y-m-d');
            }
        }
        $first_working_Tues_Thrs = $days[0];

        return $first_working_Tues_Thrs;
    }

    public function windowsCleaning()
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->year;
        $getNext3Months = $this->getNext3Months();

        $currentMonth = Carbon::createFromDate($year, $month, 1)->toDateString();
        $lastDayOfMonth = Carbon::parse($currentMonth)->endOfMonth()->toDateString();
        $period = CarbonPeriod::between($currentMonth, $lastDayOfMonth)->filter('isWeekday');

        //The windows are cleaned on the last working day of the month
        $lastWorkingDay = $period->last();

        return $lastWorkingDay->toDateString();

    }


    public function next3MonthsWindowsCleaning()
    {
        $next3Months = $this->getNext3Months();
        dd($next3Months);
    }

    public function getNext3Months()
    {
        $data = [];
        for ($i = 0; $i <= 2; $i++) {
            $data[] = Carbon::now()->addMonths($i)->day(1)->toDateString();
        }
        return $data;
    }
}
