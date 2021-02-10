<?php

namespace App\Http\Controllers;


use App\Models\Schedules;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ScheduleController extends Controller
{
    // view
    public function index()
    {


       $getWorkDaysForTheNextThreeMonths = $this->getWorkDaysForTheNextThreeMonths();
        $getDayDates = $this->getDayDates();
//        dd($getWorkDaysForTheNextThreeMonths);
        $loopvacuumingEveryTuesdayAndThursday = $this->loopvacuumingEveryTuesdayAndThursday();

        $vacuumingEveryTuesdayAndThursday = $this->vacuumingEveryTuesdayAndThursday();
        $refrigeratorCleaning = $this->refrigeratorCleaning();
        $windowsCleaning = $this->windowsCleaning();
        $nextMonths = $this->getNext3Months();

        $generateCleaning = $this->generateCleaning();

//$preparedData = $this->preparedData();
        $vacuming = $this->generatevacuming();

        return view('schedule', compact(['vacuming']));
    }


    public function generateCleaning()
    {
        $allWorkingDays = $this->getWorkDaysForTheNextThreeMonths();
        $vacuming = $this->loopvacuumingEveryTuesdayAndThursday();


    }

    public function generatevacuming()
    {
        $vacumming = $this->loopvacuumingEveryTuesdayAndThursday();
        return $vacumming;
    }

    public function vacuming()
    {
        return 'cistenje';
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
                    $days[] = $date->format('Y-m-d') .' ' . $this->vacuming();
                } else {
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
           $days[] = $date->dayName ;

        }
return $days;
//
//        $count = count($days);
//
//        for($i = 0; $i < $count; $i++){
//            $data = array(
//                'working_days' => $days[$i],
//            );
//
//            $insertData[] = $data;
//        }
//
//        Schedules::insert($insertData);

    }

    public function getDayDates()
    {
        $nowTimeDate = Carbon::createFromDate(2021, 2, 1)->toDateString();
        $lastDayofMonth = Carbon::parse($nowTimeDate)->endOfMonth()->addMonths(2)->toDateString();
        $period = CarbonPeriod::between($nowTimeDate, $lastDayofMonth)->filter('isWeekday');

        foreach ($period as $date) {
            $days[] = $date->format('Y-m-d') ;

        }
//        $count = count($days);
//
//        for($i = 0; $i < $count; $i++){
//            $data = array(
//                'working_days_date' => $days[$i],
//            );
//
//            $insertData[] = $data;
//        }
//
//        Schedules::insert($insertData);


      return $days;

    }


    public function vacuumingEveryTuesdayAndThursday()
    {
        //● Vacuuming is done every Tuesday and Thursday
        $nowTimeDate = Carbon::createFromDate(2021, 4, 1)->toDateString();
        $lastDayofMonth = Carbon::parse($nowTimeDate)->endOfMonth()->toDateString();

        $period = CarbonPeriod::between($nowTimeDate, $lastDayofMonth)->filter('isWeekday');

        foreach ($period as $date) {
            if ($date->isThursday() || $date->isTuesday()) {
                $days[] = $date->format('Y-m-d') ;
            }
        }
        return $days;
//        echo implode('<br>', );
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
