<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;

class ImportCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a CSV file from a URL';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = $this->argument('url');

        // download the CSV file
        $temp_file = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($temp_file, file_get_contents($url));

        // use the CSV Reader to read the CSV file
        $csv = Reader::createFromPath($temp_file, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
        //echo "<pre>";
        //print_r($records); die;
        // insert the records into the database
        foreach ($records as $offset => $record) {
            DB::table('customers')->insert($record);
        }

        // delete the temporary file
        unlink($temp_file);

        $this->info('CSV imported successfully!');
    }
}
