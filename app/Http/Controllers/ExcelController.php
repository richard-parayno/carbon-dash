<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\Input;
use DB;
use App\Models\Trip;
use App\Models\Monthlyemissionsperschool;
use DateTime;
use Debugbar;
use PHPExcel_Cell;
use PHPExcel_Cell_DataType;
use PHPExcel_Cell_IValueBinder;
use PHPExcel_Cell_DefaultValueBinder;
use Illuminate\Support\Carbon;

class ExcelController extends Controller
{
    public function show() {
        $trips = Trip::all();

        return view('upload-files', compact('trips'));
    }

    public function showUploaded() {
        $trips = Trip::all();

        return view('upload-view', compact('trips'));
    }

    public function process() {
        // initialize the excelfile that we will be loading
        $excelFile = Input::file('excelFile');

        $data = array();
        
        //load the content of the excel file into an array called $load. we also format the dates into m/d/Y before we pass all of the data to the array.
        // ONLY 1 SHEET WILL BE LOADED.
        $load = Excel::load($excelFile, function($reader) {

        })->get()->toArray();
        // run the conditional if $load has stuff in it
        if($load){
            // initialize the counters
            $ctr = 0;
            $totalEmission = 0;
            //dd($load);
            foreach ($load as $key => $row) {
                //check row if there's actually anything written. line is scrapped if one is missing
                if ($row['date'] != null && $row['requesting_department'] != null && $row['plate_number'] != null && $row['kilometer_reading'] != null && $row['destinations'] != null && $row['departure_time'] != null) {
                    // in the current row....... 
                    //convert the date
                    $convertd1 = (string)$row['date'];


                    Debugbar::info("convertd1 ".$convertd1);                
                    Debugbar::info("convertd1 strtotime".strtotime($convertd1));                
                    $currentMonth = date("Y-m-d", strtotime($convertd1));
                    $currentd1Time = date("H:i", strtotime($row['departure_time']));   
                    Debugbar::info("currentd1Time strtotime".strtotime($currentd1Time));                
                        
                    Debugbar::info("currentMonth ".$currentMonth);
                    Debugbar::info("currentTime ".$currentd1Time);
                    

                    // place the current row's data into variables you can manipulate easier
                    $currentPlateNumber = $row['plate_number'];
                    $currentKMReading = $row['kilometer_reading'];
                    $destinations = $row['destinations'];
                    $currentDepartment = $row['requesting_department'];
                    $currentInstitution = DB::table('deptsperinstitution')->where('deptName', $currentDepartment)->value('institutionID');

                    //do the same date conversion but declare it as the currrent month in excel.
                    $convertd2 = (string)$row['date'];
                    $currentd2Time = $row['departure_time'];                              
                    Debugbar::info("convertd2 ".$convertd2);                
                    Debugbar::info("convertd2 strtotime".strtotime($convertd2));  
                    $currentMonthInExcel = date("Y-m-d", strtotime($convertd2));
                    Debugbar::info("currentMonthInExcel ".$currentMonthInExcel);
                    
                    
                    // place current row into the array that we'll pass to the next page
                    $data[$ctr]['date'] = $currentMonth;
                    $data[$ctr]['requesting_department'] = $currentDepartment;
                    $data[$ctr]['departure_time'] = $currentd2Time;
                    $data[$ctr]['plate_number'] = $currentPlateNumber;
                    $data[$ctr]['kilometer_reading'] = $currentKMReading;
                    $data[$ctr]['destinations'] = $destinations;
                    $data[$ctr]['tripTime'] = $currentd1Time;
                    
                    //iterate the ctr to go to the next row
                    $ctr++;
                    /**
                     * if gasoline emission in tonnes -- ((6760 / MPG) * kilometer reading) * 100000000000000000000)
                     * if diesel emission in tonnes -- (((kilometer reading * 0.621371) / mpg) * 19.36) / 2204.6 
                     * 
                     * after each computation (per row), add it to the total (acts as counter) emission variable
                     */
                }
            }

        }
        return view('display-table', compact('data', 'load'));

    }

    public function saveToDb(Request $request) {
        $load = json_decode($request->data, true);
        //audit vars
        if (DB::table('trips')->max('batch') == null) {
            $lastTripsBatchNumber = 0;
        }  else {
            $lastTripsBatchNumber = DB::table('trips')->max('batch'); 
        }
        Debugbar::info("lastTripsBatchNumber pre loop: ".$lastTripsBatchNumber);
         
        $currentAuditDate = Carbon::now();
        $formattedCurrentAuditDate = $currentAuditDate->toDateTimeString();   
        
        

        // initialize the counters
        $ctr = 0;
        $totalEmission = 0;
        foreach ($load as $key => $row) {
            $allEmissions = Monthlyemissionsperschool::all();

            if ($allEmissions->isEmpty()) {
                $firstrun = true;
            } else {
                $firstrun = false;
            }
            // in the current row....... 
            //convert the date
            $convertd1 = (string)$row['date'];
            //dd($row);
            $departureTime = Carbon::parse($row['departure_time']['date']);

            Debugbar::info("convertd1 ".$convertd1);                
            $currentMonth = date("Y-m-d", strtotime($convertd1));
            Debugbar::info("currentMonth strtotime".strtotime($currentMonth));                
            $currentd1Time = date("H:i", strtotime($departureTime));
            Debugbar::info("currentd1Time strtotime".strtotime($currentd1Time));                                    
            Debugbar::info("currentMonth ".$currentMonth);
            Debugbar::info("currentTime ".$currentd1Time);
            

            // place the current row's data into variables you can manipulate easier
            $currentPlateNumber = $row['plate_number'];
            $currentKMReading = $row['kilometer_reading'];
            $destinations = $row['destinations'];
            $currentDepartment = $row['requesting_department'];
            $currentInstitution = DB::table('deptsperinstitution')->where('deptName', $currentDepartment)->value('institutionID');

            //do the same date conversion but declare it as the currrent month in excel.
            $convertd2 = (string)$row['date'];
            $currentd2Time = $departureTime;                              
            Debugbar::info("convertd2 ".$convertd2);                
            Debugbar::info("convertd2 strtotime".strtotime($convertd2));  
            $currentMonthInExcel = date("Y-m-d", strtotime($convertd2));

            Debugbar::info("currentMonthInExcel ".$currentMonthInExcel);

            //get the current dept id from the deptsperinstitution table
            $currentDeptID = DB::table('deptsperinstitution')->where('deptName', $row['requesting_department'])->value('deptID');
                
            //check the vehicles_mv table if theres a match for the currentplatenumber
            if (DB::table('vehicles_mv')->where('plateNumber', $currentPlateNumber)->first()) {
                //get the values if there's a match
                $selected = DB::table('vehicles_mv')->where('plateNumber', $currentPlateNumber)->first();
                $carType = DB::table('vehicles_mv')->where('plateNumber', $currentPlateNumber)->value('carTypeID');
                $fuelType = DB::table('vehicles_mv')->where('plateNumber', $currentPlateNumber)->value('fuelTypeID');
                $selectedFuelType = DB::table('fueltype_ref')->where('fuelTypeID', $fuelType)->value('fuelTypeID');
                $selectedCarTypeMPG = DB::table('cartype_ref')->where('carTypeID', $carType)->value('mpg');
                
                //check the fuel type
                switch ($selectedFuelType) {
                    //if it's diesel
                    case 1:
                    $dieselEmissionInTonnes = (($currentKMReading * 0.621371) * 19.36) / 2204.6;
                    $totalEmission += $dieselEmissionInTonnes;
                    
                    //create a new trip object (to be placed in the db)
                    $trips = new Trip;
                    $trips->deptID = $currentDeptID;
                    $trips->remarks = $destinations;
                    $trips->plateNumber = $currentPlateNumber;
                    $trips->kilometerReading = $currentKMReading;
                    $trips->emissions = $dieselEmissionInTonnes;
                    $trips->tripTime = $currentd1Time;
                    $trips->tripDate = $currentMonth;
                    $trips->batch = $lastTripsBatchNumber + 1;                    
                    Debugbar::info("lastTripsBatchNumber post insert: ".$trips->batch);

                    //add date
                    $trips->uploaded_at = $formattedCurrentAuditDate;

                    $trips->save();
                    
                    //MONTHLY EMISSIONS
                    $currentMonth = $currentMonthInExcel;
                    $carbonMonthYearExcel = Carbon::parse($currentMonth);
                    Debugbar::info("current carbon month excel: ".$carbonMonthYearExcel);
                    

                    if ($firstrun == true) {
                        $monthlyEmission = new Monthlyemissionsperschool;
                        $monthlyEmission->institutionID = $currentInstitution;
                        $monthlyEmission->emission = $totalEmission;
                        $newDate = Carbon::create($carbonMonthYearExcel->year, $carbonMonthYearExcel->month, 1, 0);                        
                        $monthlyEmission->monthYear = $newDate;
                        $monthlyEmission->save();
                        $firstrun = false;
                    } elseif ($firstrun == false) {
                        foreach($allEmissions as $all) {
                            $carbonMonthYearDB = Carbon::parse($all->monthYear);
                            $newDateExcel = Carbon::create($carbonMonthYearExcel->year, $carbonMonthYearExcel->month, 1, 0);
                            $newDateDB = Carbon::create($carbonMonthYearDB->year, $carbonMonthYearDB->month, 1, 0);
                            Debugbar::info("current carbon month db: ".$newDateDB);
                            

                            if (Monthlyemissionsperschool::where('monthYear', $newDateExcel)->exists()) {
                                $updateMonthlyEmissions = DB::table('monthlyemissionsperschool')->where('monthYear', $newDateExcel)->update(['emission' => $totalEmission]);
                            } else {
                                $newMonthlyEmissions = new Monthlyemissionsperschool;
                                $newMonthlyEmissions->institutionID = $currentInstitution;
                                $newMonthlyEmissions->emission = $totalEmission;                        
                                $newMonthlyEmissions->monthYear = $newDateExcel;
                                $newMonthlyEmissions->save();
                            }
                        }
                    }
                   

                    
                    break;
                    //if it's gas
                    case 2:
                    $gasEmissionInTonnes = ((6760 / $selectedCarTypeMPG) * $currentKMReading) * 100000000000000000000;
                    $totalEmission += $gasEmissionInTonnes;
                    
                    //create a new trip object (to be placed in the db)                        
                    $trips = new Trip;
                    $trips->deptID = $currentDeptID;   
                    $trips->remarks = $destinations;                         
                    $trips->plateNumber = $currentPlateNumber;
                    $trips->kilometerReading = $currentKMReading;
                    $trips->emissions = $gasEmissionInTonnes;
                    $trips->tripTime = $currentd1Time;  
                    $trips->tripDate = $currentMonth;
                    $trips->batch = $lastTripsBatchNumber + 1;                    
                    Debugbar::info("lastTripsBatchNumber post insert: ".$trips->batch);

                    //add date
                    $trips->uploaded_at = $formattedCurrentAuditDate;
                    
                                                                
                    $trips->save();   
                    
                    //MONTHLY EMISSIONS
                    $currentMonth = $currentMonthInExcel;
                    $carbonMonthYearExcel = Carbon::parse($currentMonth);
                    Debugbar::info("current carbon month excel: ".$carbonMonthYearExcel);
                    

                    if ($firstrun == true) {
                        $monthlyEmission = new Monthlyemissionsperschool;
                        $monthlyEmission->institutionID = $currentInstitution;
                        $monthlyEmission->emission = $totalEmission;
                        $newDate = Carbon::create($carbonMonthYearExcel->year, $carbonMonthYearExcel->month, 1, 0);                        
                        $monthlyEmission->monthYear = $newDate;
                        $monthlyEmission->save();
                        $firstrun = false;
                    } elseif ($firstrun == false) {
                        foreach($allEmissions as $all) {
                            $carbonMonthYearDB = Carbon::parse($all->monthYear);
                            $newDateExcel = Carbon::create($carbonMonthYearExcel->year, $carbonMonthYearExcel->month, 1, 0);
                            $newDateDB = Carbon::create($carbonMonthYearDB->year, $carbonMonthYearDB->month, 1, 0);
                            Debugbar::info("current carbon month db: ".$newDateDB);
                            

                            if (Monthlyemissionsperschool::where('monthYear', $newDateExcel)->exists()) {
                                $updateMonthlyEmissions = DB::table('monthlyemissionsperschool')->where('monthYear', $newDateExcel)->update(['emission' => $totalEmission]);
                            } else {
                                $newMonthlyEmissions = new Monthlyemissionsperschool;
                                $newMonthlyEmissions->institutionID = $currentInstitution;
                                $newMonthlyEmissions->emission = $totalEmission;                        
                                $newMonthlyEmissions->monthYear = $newDateExcel;
                                $newMonthlyEmissions->save();
                            }
                        }
                    }

                    
                    break;
                }
            }
            
            //iterate the ctr to go to the next row
            $ctr++;
            /**
             * if gasoline emission in tonnes -- ((6760 / MPG) * kilometer reading) * 100000000000000000000)
             * if diesel emission in tonnes -- (((kilometer reading * 0.621371) / mpg) * 19.36) / 2204.6 
             * 
             * after each computation (per row), add it to the total (acts as counter) emission variable
             */
        }

        $trips = Trip::all();

        return redirect('/dashboard/upload-view')->with(compact('trips'));

    }
}
