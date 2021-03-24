<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentExport;

class GenerateCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:generate_csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Basic Salary and Salary Bonus File';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $request = Request::create(route('export-file'), 'GET');
        $response = app()->handle($request);
        $responseBody = json_decode($response->getContent(), true);
        dump($response);
        return $responseBody;
    }
}
