<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentExport;
use App\Imports\PaymentImport;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ImportExportController extends Controller
{
    //
    public function importExport(){
        return view('welcome');
    }

    public function importFile(Request $request){
        Excel::import(new PaymentImport, $request->file('file')->store('temp'));
        return back();
    }

    public function exportFile(){
        //$arrays = $this->generatePaymentList(Carbon::now());
        $period = CarbonPeriod::create(Carbon::now(), '1 month', Carbon::now()->addMonths(12));
        $months = array();
        foreach ($period as $dt) {
            $payDay = $this->isWorkDay($dt);
            $bonusDay = $this->isTenth($dt);
            $months[] = [
                $dt->format("M/y"),
                $payDay,
                $bonusDay
            ];
        }
        $export = new PaymentExport([$months]);
        return Excel::download($export, 'Basic Salary and Salary Bonus.csv');
    }

    public function isWorkDay(Carbon $searchDate){
        $searchDay = 'Friday';
        $lastDay = Carbon::createFromTimeStamp(strtotime("last $searchDay", $searchDate->timestamp));
        return $lastDay->format("Y-m-d");
    }

    public function isTenth(Carbon $searchDate){
        $month = $searchDate->format('F');
        $year = $searchDate->format('Y');
        $tenth = Carbon::parse("first day of $month $year")->addDays(9);
        if($this->isWeekend($tenth)){
            return $this->isWorkDay($searchDate);
        } else {
            return $tenth->format("Y-m-d");
        }
    }

    public function isWeekend(Carbon $date){
        $weekDay = date('w', strtotime($date));
        return ($weekDay == 0 || $weekDay == 6);
    }

}
