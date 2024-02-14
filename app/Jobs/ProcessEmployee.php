<?php

namespace App\Jobs;

use App\Models\Employee;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessEmployee implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $employeeData;

    /**
     * Create a new job instance.
     */
    public function __construct($employeeData)
    {
        $this->queue = 'employee';
        $this->employeeData = $employeeData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('jobs run');
        foreach ($this->employeeData as $data) {
            $employee = new Employee();
            $employee->name = $data['First Name'].' '.$data['Last Name'];
            $employee->save();
        }
        Log::info('jobs run');
    }
}
