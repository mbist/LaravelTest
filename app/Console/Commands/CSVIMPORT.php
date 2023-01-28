<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use League\Csv\Statement;
//use App\Models\ExampleModel;
use Illuminate\Support\Facades\Log;

class CSVIMPORT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:import {url : The URL of the CSV file} {model : The Eloquent model to import the data into}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a CSV file from a URL and store the data in a Laravel Eloquent model';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = $this->argument('url');
        $model = $this->argument('model');
        $imported = 0;
        $notImported = 0;

        try {
            $file = file_get_contents($url);
            $file = mb_convert_encoding($file, 'UTF-8', 'UTF-8');
            $csv = Reader::createFromString($file);
            $csv->setHeaderOffset(0);
            $stmt = (new Statement())->offset(0);
            $records = $stmt->process($csv);
            foreach ($records as $record) {
                $data = new $model();
                $data->fill($record);
                if ($data->save()) {
                    $imported++;
                } else {
                    $notImported++;
                }
            }
        } catch (\Exception $e) {
            Log::error("An error occurred while importing the CSV file: " . $e->getMessage());
            $this->error('An error occurred while importing the CSV file. Please check the logs for more information.');
            return;
        }

        Log::info("Import Results: Total datasets imported: {$imported}, Total datasets not imported: {$notImported}");
        $this->info("The CSV file was imported successfully! {$imported} datasets imported, {$notImported} datasets not imported. Check logs for more information.");
    }
}
