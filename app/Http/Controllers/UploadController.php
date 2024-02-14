<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmployee;
use App\Jobs\ProcessTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function index() {
        return view("upload");
    }

    public function progress() {
        return view("progress");
    }

    public function uploadFile(Request $request) { 
        try{    
            if ($request->has("csvFile")) {
                $fileName = $request->csvFile->getClientOriginalName();
                $fileNamePath = public_path('uploads').'/'.$fileName;
                if (!file_exists($fileNamePath)) {
                    $request->csvFile->move(public_path('uploads'), $fileName);
                }

                $records = array_map('str_getcsv', file($fileNamePath));

                $header = array_shift($records);
                $employeeData = array();

                $data = array_chunk($records , 300);

                $batch = Bus::batch([])->dispatch();

                foreach($data as $index => $record) {
                    foreach($record as $value) {
                        $employeeData[$index][] = array_combine($header, $value);
                    }

                    $batch->add(new ProcessEmployee($employeeData[$index]));
                    ProcessTest::dispatch();
                }

                session()->put('lastUploadBatchId', $batch->id);

                return redirect('/progress?id='.$batch->id);
            }
        } catch(\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
