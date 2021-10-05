<?php

namespace App\Console\Commands;

use App\Services\CaptureDataService;
use Illuminate\Console\Command;

class GetPlanets extends Command
{

    public $captureDataService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'planets:get {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get planets from api';

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

        $response = $this->captureDataService->request('planets', $this->argument('id'), 'GET', false);
        $this->captureDataService->savePlanets($response, $group);

        echo 'success';
    }
}
