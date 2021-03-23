<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentExport;
use App\Imports\PaymentImport;

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
        return Excel::download(new PaymentExport, 'Basic Salary and Salary Bonus.csv');
    }
}
