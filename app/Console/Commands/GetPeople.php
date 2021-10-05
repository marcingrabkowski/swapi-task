<?php

namespace App\Console\Commands;

use App\Services\CaptureDataService;
use Illuminate\Console\Command;

class GetPeople extends Command
{

    public $captureDataService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'people:get {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get poeple from api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CaptureDataService $captureDataService)
    {
        $this->captureDataService = $captureDataService;
        parent::__construct();
    }

    public function handle(): void
    {
        $group = true;

        if($this->argument('id')) {
            $group = false;
        }

        $response = $this->captureDataService->request('people', $this->argument('id'), 'GET', false);

        $saveData = $this->captureDataService->savePeople($response, $group);

        if(!$saveData) {
            echo 'Error! Run planets and starships command first!';

            return;
        }

        echo 'success';
    }
}
