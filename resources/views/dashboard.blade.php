<?php
    $schoolSort = false;
    $userType = Auth::user()->userTypeID;  
    if($userType > 2){
        $userSchool = Auth::user()->institutionID;
        $schoolSort = true;
    }
    //logic on usertype

    if($schoolSort){


    //include institution
    //get most department type contributions (emission total)
        
    $where="trips.institutionID=".$userSchool;
    $columnTable = DB::table('trips')
        ->select(DB::raw('sum(trips.emissions) as emissions'))
        ->whereRaw($where)
        ->get();
    $column = "round((SUM(trips.emissions) * 100 / ".$columnTable[0]->emissions."),2) as percentage";
        
    //get most vehicle type contributions (emission total)
    $vehicleContributions = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('cartype_ref', 'cartype_ref.carTypeID','=', 'vehicles_mv.carTypeID')
        ->select('cartype_ref.carTypeName', DB::raw($column))
        ->whereRaw('trips.institutionID = '.$userSchool)
        ->groupBy('carTypeName')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();
        
    $deptContributions = DB::table('trips')
        ->join('deptsperinstitution', 'deptsperinstitution.deptID', '=', 'trips.deptID')
        ->select('deptsperinstitution.deptName', DB::raw($column))
        ->whereRaw('trips.institutionID = '.$userSchool)
        ->groupBy('deptsperinstitution.deptID')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();

    //get most car type contributions (emission total)
    $carContributions = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('institutions', 'institutions.institutionID','=', 'vehicles_mv.institutionID')
        ->select('vehicles_mv.modelName', DB::raw($column))
        ->whereRaw('trips.institutionID = '.$userSchool)
        ->groupBy('vehicles_mv.modelName')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();
        
     //get most car brand type contributions (emission total)
    $carBrandContributions = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('carbrand_ref', 'carbrand_ref.carbrandID','=', 'vehicles_mv.carbrandID')
        ->select('carbrand_ref.carBrandName', DB::raw($column))
        ->whereRaw('trips.institutionID = '.$userSchool)
        ->groupBy('carbrand_ref.carbrandName')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();

    $columnTable = DB::table('trips')
        ->select(DB::raw('count(trips.emissions) as tripCount'))
        ->whereRaw($where)
        ->get();
    $column = "round((count(trips.emissions) * 100 / ".$columnTable[0]->tripCount."),2) as percentage";    
        
    //trip number
    //get most car brand type contributions (trip number)
     $carBrandTripNumber = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('carbrand_ref', 'carbrand_ref.carbrandID','=', 'vehicles_mv.carbrandID')
        ->select('carbrand_ref.carbrandName', DB::raw($column))
        ->whereRaw('trips.institutionID = '.$userSchool)
        ->groupBy(DB::raw('1'))
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();
        
        //get most car contributions (trip number)
    $carTripNumber = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('institutions', 'institutions.institutionID','=', 'vehicles_mv.institutionID')
        ->select('vehicles_mv.modelName', DB::raw($column))  
        ->whereRaw('trips.institutionID = '.$userSchool)
        ->groupBy('modelName')
        ->orderByRaw('2 desc')
        ->limit(2)
        ->get();
        
    //get most vehicle type contributions (trip number)
    $vehicleTripNumber = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('cartype_ref', 'cartype_ref.carTypeID','=', 'vehicles_mv.carTypeID')
        ->select('cartype_ref.carTypeName', DB::raw($column))
        ->whereRaw('trips.institutionID = '.$userSchool)
        ->groupBy('carTypeName')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();
    
    //get most department type contributions (trip number)
    $deptTripNumber = DB::table('trips')
        ->join('deptsperinstitution', 'deptsperinstitution.deptID', '=', 'trips.deptID')
        ->select('deptsperinstitution.deptName', DB::raw($column))
        ->whereRaw('trips.institutionID = '.$userSchool)
        ->groupBy('deptsperinstitution.deptName')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();
        
    }else{

    $columnTable = DB::table('trips')
        ->select(DB::raw('sum(trips.emissions) as emissions'))
        ->get();
    $column = "round((SUM(trips.emissions) * 100 / ".$columnTable[0]->emissions."),2) as percentage";
    
    $institutionEmissions = DB::table('trips')
        ->join('institutions', 'trips.institutionID', '=', 'institutions.institutionID')
        ->select('institutions.institutionName', DB::raw($column))
        ->orderByRaw('2 DESC')
        ->groupBy(DB::raw('1'))
        ->limit(2)
        ->get();
        
    //get most vehicle type contributions (emission total)
    $vehicleContributions = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('cartype_ref', 'cartype_ref.carTypeID','=', 'vehicles_mv.carTypeID')
        ->select('cartype_ref.carTypeName', DB::raw($column))
        ->groupBy('carTypeName')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();
        
    $deptContributions = DB::table('trips')
        ->join('deptsperinstitution', 'deptsperinstitution.deptID', '=', 'trips.deptID')
        ->select('deptsperinstitution.deptName', DB::raw($column))
        ->groupBy('deptsperinstitution.deptID')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();

    //get most car type contributions (emission total)
    $carContributions = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('institutions', 'institutions.institutionID','=', 'vehicles_mv.institutionID')
        ->select('vehicles_mv.modelName', DB::raw($column))
        ->groupBy('vehicles_mv.modelName')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();
        
     //get most car brand type contributions (emission total)
    $carBrandContributions = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('carbrand_ref', 'carbrand_ref.carbrandID','=', 'vehicles_mv.carbrandID')
        ->select('carbrand_ref.carBrandName', DB::raw($column))
        ->groupBy('carbrand_ref.carbrandName')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();

    $columnTable = DB::table('trips')
        ->select(DB::raw('count(trips.emissions) as tripCount'))
        ->get();
    $column = "round((count(trips.emissions) * 100 / ".$columnTable[0]->tripCount."),2) as percentage";    
        
    //trip number
    //get most car brand type contributions (trip number)
     $carBrandTripNumber = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('carbrand_ref', 'carbrand_ref.carbrandID','=', 'vehicles_mv.carbrandID')
        ->select('carbrand_ref.carbrandName', DB::raw($column))
        ->groupBy(DB::raw('1'))
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();
        
        //get most car contributions (trip number)
    $carTripNumber = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('institutions', 'institutions.institutionID','=', 'vehicles_mv.institutionID')
        ->select('vehicles_mv.modelName', DB::raw($column))  
        ->groupBy('modelName')
        ->orderByRaw('2 desc')
        ->limit(2)
        ->get();
        
    //get most vehicle type contributions (trip number)
    $vehicleTripNumber = DB::table('trips')
        ->join('vehicles_mv', 'vehicles_mv.plateNumber', '=', 'trips.plateNumber')
        ->join('cartype_ref', 'cartype_ref.carTypeID','=', 'vehicles_mv.carTypeID')
        ->select('cartype_ref.carTypeName', DB::raw($column))
        ->groupBy('carTypeName')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();
    
    //get most department type contributions (trip number)
    $deptTripNumber = DB::table('trips')
        ->join('deptsperinstitution', 'deptsperinstitution.deptID', '=', 'trips.deptID')
        ->select('deptsperinstitution.deptName', DB::raw($column))
        ->groupBy('deptsperinstitution.deptName')
        ->orderByRaw('2 DESC')
        ->limit(2)
        ->get();
        
    }    
?>
    @extends('layouts.main') @section('styling')
    <style>
        /** TODO: Push margin more to the right. Make the box centered to the user. **/

        #box-form {
            background-color: #363635;
            margin-top: 20px;
            padding: 40px;
            border-radius: 10px;
        }

        #box-form h1 {
            text-align: center;
            color: white;
        }

        #box-form input {
            color: white;
        }

        #institutionChartDiv {
            width: 100%;
            height: 500px;
        }

        #deptChartDiv {
            width: 100%;
            height: 500px;
        }
    </style>
    @endsection @section('content')
    <div class="ten columns offset-by-one" id="box-form" ng-app="myapp">
        <div ng-controller="MyController">
            <div class="row">
                <!--General Chart-->
                <div class="twelve columns" id="allChartDiv" style="width: 100%; height: 400px; background-color: #222222;"></div><br>
            </div>
            <div class="row">
               <div class="twelve columns">
                <h7>Unit of measurement:&nbsp;</h7>
                <select style="color:black;" ng-model="dboard">
                    <option ng-repeat="type in dboardType" value="<?php echo '{{type}}'; ?>" style="color:black;"><?php echo "{{type}}";?></option>
                </select>
               </div>
            </div>
            <!--Div of filtered dashboard-->
            <div class="twelve columns" ng-show="dboard=='Emissions'">
                        <?php
                            if(!$schoolSort){
                                
                            echo '<div class="twelve columns"> <a href=""><div class="four columns offset-by-four">';
                            echo "Top Institutions on {{dboard}}: <br>";
                             for($x = 0; $x < count($institutionEmissions); $x++){
                                 $top = $x + 1;
                                 echo $top.". ".$institutionEmissions[$x]->institutionName." - ".$institutionEmissions[$x]->percentage."%<br>";
                             }
                                echo " </div></div></a>";
                            }
                        ?>
                <div class="twelve columns">
                    <a href="">
                    <div class="four columns">
                        <?php
                            echo   "Top Departments on {{dboard}}: <br>";
                             for($x = 0; $x < count($deptContributions); $x++){
                                 $top = $x + 1;
                                 echo $top.". ".$deptContributions[$x]->deptName." - ".$deptContributions[$x]->percentage."%<br>";
                             }
                        ?>
                    </div>
                    </a>
                    <a href="">
                    <div class="four columns">
                        <?php
                            echo   "Top Car on {{dboard}}: <br>";
                             for($x = 0; $x < count($carContributions); $x++){
                                 $top = $x + 1;
                                 echo $top.". ".$carContributions[$x]->modelName." - ".$carContributions[$x]->percentage."%<br>";
                             }
                        ?>
                    </div>
                    </a>
                    <a href="">  
                    <div class="four columns">
                        <?php
                            echo   "Top Car Type on {{dboard}}: <br>";
                             for($x = 0; $x < count($vehicleContributions); $x++){
                                 $top = $x + 1;
                                 echo $top.". ".$vehicleContributions[$x]->carTypeName." - ".$vehicleContributions[$x]->percentage."%<br>";
                             }
                        ?>
                    </div>
                    </a>
                </div>
                <div class="twelve columns">
                    <a href="">  
                    <div class="four columns offset-by-four">
                        <?php
                            echo   "Top Car Brand on {{dboard}}: <br>";
                             for($x = 0; $x < count($carBrandContributions); $x++){
                                 $top = $x + 1;
                                 echo $top.". ".$carBrandContributions[$x]->carBrandName." - ".$carBrandContributions[$x]->percentage."%<br>";
                             }
                        ?>
                    </div>
                    </a>
                </div>
            </div>
            <div class="twelve columns" ng-show="dboard=='Number of Trips'">
               <?php
                    if(!$schoolSort){

                    echo '<div class="twelve columns"> <a href=""><div class="four columns offset-by-four">';
                    echo "Top Institutions on {{dboard}}: <br>";
                     for($x = 0; $x < count($institutionEmissions); $x++){
                         $top = $x + 1;
                         echo $top.". ".$institutionEmissions[$x]->institutionName." - ".$institutionEmissions[$x]->percentage."%<br>";
                     }
                        echo " </div></div></a>";
                    }
                ?>
                <div class="twelve columns">
                <a href="">
                    <div class="four columns">
                        <?php
                            echo   "Top Departments on {{dboard}}: <br>";
                             for($x = 0; $x < count($deptTripNumber); $x++){
                                 $top = $x + 1;
                                 echo $top.". ".$deptTripNumber[$x]->deptName." - ".$deptTripNumber[$x]->percentage."%<br>";
                             }
                        ?>
                    </div>
                </a>
                <a href="">
                    <div class="four columns">
                        <?php
                            echo   "Top Vehicle on {{dboard}}: <br>";
                             for($x = 0; $x < count($carTripNumber); $x++){
                                 $top = $x + 1;
                                 echo $top.". ".$carTripNumber[$x]->modelName." - ".$carTripNumber[$x]->percentage."%<br>";
                             }
                        ?>
                    </div>
                </a>   
                <a href="">
                    <div class="four columns">
                        <?php
                            echo   "Top Car Type on {{dboard}}: <br>";
                             for($x = 0; $x < count($vehicleTripNumber); $x++){
                                 $top = $x + 1;
                                 echo $top.". ".$vehicleTripNumber[$x]->carTypeName." - ".$vehicleTripNumber[$x]->percentage."%<br>";
                             }
                        ?>
                    </div>
                </a>
                </div>
                <div class="twelve columns">
                <a href="">
                    <div class="four columns offset-by-four">
                        <?php
                            echo   "Top Car Brand on {{dboard}}: <br>";
                             for($x = 0; $x < count($carBrandTripNumber); $x++){
                                 $top = $x + 1;
                                 echo $top.". ".$carBrandTripNumber[$x]->carbrandName." - ".$carBrandTripNumber[$x]->percentage."%<br>";
                             }
                        ?>
                    </div>
                </a>
                </div>
            </div>
        </div>
    </div>
    @endsection @section('scripts')
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.10/angular.min.js" type="text/javascript"></script>

    <!--angular js script-->
    <script>
        var app = angular
            .module("myapp", [])
            .controller("MyController", function($scope) {
                $scope.dboardType = ['Emissions', 'Number of Trips'];

                /*
                $scope.operate = function(input) {
                    $scope.holder = $scope.answer;
                    $scope.operation = input;
                    $scope.reset();
                };

                $scope.equals = function() {
                    switch ($scope.operation) {
                        case '+':
                            {
                                $scope.answer = $scope.holder + $scope.answer;
                                break;
                            }
                        case '-':
                            {
                                $scope.answer = $scope.holder - $scope.answer;
                                break;
                            }
                        case '*':
                            {
                                $scope.answer = $scope.holder * $scope.answer;
                                break;
                            }
                        case '/':
                            {
                                $scope.answer = $scope.holder / $scope.answer;
                                break;
                            }
                        default:
                            {
                                $scope.reset();
                            }
                    }
                };

                //cart functions
                $scope.itemList = [];
                $scope.cart = [];
                $scope.name = "";
                $scope.price = "";

                //adds item x quantity to inventory
                $scope.addItem = function(name, priceEach) {
                    $scope.itemList.push({
                        itemName: name,
                        priceEach: priceEach
                    });
                    $scope.name = "";
                    $scope.price = "";
                };

                //adds item and quantity to cart
                $scope.addToCart = function(name, priceeach, quantity) {
                    $scope.cart.push({
                        itemName: name,
                        priceEach: priceeach,
                        itemQuantity: quantity
                    });
                };

                //totals all in cart
                $scope.totalCart = 0;
                $scope.checkout = function() {
                    for (var x = 0; x < $scope.cart.length; x++) {
                        $scope.totalCart += $scope.cart[x].priceEach * $scope.cart[x].itemQuantity;
                    }
                };

                //resets all in cart
                $scope.resetCart = function() {
                    $scope.cart = [];
                };
                */
            });
    </script>
    <!--angular js script-->

    <!-- general emission chart-->
    <script>
        <?php
        {
        function getRegressionLine($emissionData){
                    //step 1
                    //calculate pearson's correlation coefficient - r
                    //step 2
                    //compute for the standard deviation of months (x) and emisisons (y) - Sx and Sy
                    //step 3
                    //compute for slope - b
                    //step 4
                    //compute for y-intercept - a
                    //Linear Regression
                    //y = a + bx

                    //Pearson's Correlation Coefficient calculation
                    //numerator calculation
                    $r = 0;
                    $summationOfNumerator = 0;
                    $xAve = 0;
                    $yAve = 0;
                    for($x = 1; $x <= count($emissionData); $x++) {
                        $xAve += $x;
                    }
                    for($x = 0; $x < count($emissionData); $x++) {
                        $yAve += $emissionData[$x][1];
                    }
                    $xAve = $xAve/count($emissionData);
                    $yAve = $yAve/count($emissionData);
                    for($x = 1; $x <= count($emissionData); $x++) {
                        $summationOfNumerator+=($x - $xAve)*($emissionData[$x - 1][1] - $yAve);
                    }

                    //denominator 
                    $denominator = 0;
                    $summationTerm1 = 0;
                    $summationTerm2 = 0;
                    for($x = 1; $x <= count($emissionData); $x++) {
                        $summationTerm1+=($x - $xAve)*($x - $xAve);
                        $summationTerm2+=($emissionData[$x - 1][1] - $yAve)*($emissionData[$x - 1][1] - $yAve);
                    }

                    $denominator = sqrt($summationTerm1 * $summationTerm2);
                    $r = $summationOfNumerator/$denominator;

                    //standard deviation calculation
                    $Sy = sqrt($summationTerm2/(count($emissionData)-1));
                    $Sx = sqrt($summationTerm1/(count($emissionData)-1));

                    //slope calculation
                    $b = $r * ($Sy/$Sx);

                    //y-intercept calculation
                    $a;
                    $a = $yAve - ($b * $xAve);

                    $regressionLine = array($a, $b);

                    return $regressionLine;
                }

        if(!isset($data)){
            $chartTitle = 'All Universities';   
            $emissionData = DB::table('trips')
            ->join('deptsperinstitution', 'trips.deptID', '=', 'deptsperinstitution.deptID')
            ->join('monthlyemissionsperschool', DB::raw('CONCAT(YEAR(trips.tripDate), "-",MONTH(trips.tripDate))'), '=',  DB::raw('CONCAT(YEAR(monthlyemissionsperschool.monthYear), "-",MONTH(monthlyemissionsperschool.monthYear))'))
            ->join('vehicles_mv', 'trips.plateNumber', '=', 'vehicles_mv.plateNumber')
            ->join('cartype_ref', 'vehicles_mv.carTypeID', '=', 'cartype_ref.carTypeID')
            ->join('fueltype_ref', 'vehicles_mv.carTypeID', '=', 'cartype_ref.carTypeID')
            ->select('trips.tripDate', 'trips.tripTime', 'deptsperinstitution.deptName' , 'trips.plateNumber', 'trips.kilometerReading',             'trips.remarks', 'trips.emissions', DB::raw('CONCAT(YEAR(trips.tripDate), "-",MONTH(trips.tripDate)) as monthYear'),'monthlyemissionsperschool.emission', 'fueltype_ref.fuelTypeName', 'cartype_ref.carTypeName', 'vehicles_mv.modelName', 'vehicles_mv.active')
            ->orderBy('trips.tripDate', 'asc')
            ->get();
            $emissionCount = DB::table('trips')
            ->join('monthlyemissionsperschool', DB::raw('CONCAT(YEAR(trips.tripDate), "-",MONTH(trips.tripDate))'), '=',  DB::raw('CONCAT(YEAR(monthlyemissionsperschool.monthYear), "-",MONTH(monthlyemissionsperschool.monthYear))'))
            ->select(DB::raw('count(CONCAT(YEAR(trips.tripDate), "-",MONTH(trips.tripDate))) as monthYearCount'))
            ->groupBy(DB::raw('CONCAT(YEAR(monthlyemissionsperschool.monthYear), "-",MONTH(monthlyemissionsperschool.monthYear))'))
            ->get();
        } 
            else{
            $rawDB = "";
            $add = false;
            if($data['institutionID'] != null){
                $rawDB .= " vehicles_mv.institutionID = " . $data['institutionID'];
                $add = true;
            }
            if($data['carTypeID'] != null){
                if($add){
                    $rawDB .= " AND ";
                }
                $rawDB .= "cartype_ref.carTypeID = " . $data['carTypeID'];
                $add = true;
            }
            if($data['fuelTypeID'] != null){
                if($add){
                    $rawDB .= " AND ";
                }
                $add = true;
                $rawDB .= "fueltype_ref.fueltypeID = " . $data['fuelTypeID'];
            }
            if($data['fromDate'] != null && $data['toDate'] != null){
                if($add){
                    $rawDB .= " AND ";
                }
                $rawDB .= "trips.tripDate <= '" . $data['toDate'] . "' AND trips.tripDate >= '" . $data['fromDate'] . "'";
            }elseif($data['fromDate'] != null && $data['toDate'] != null){
                if($add){
                    $rawDB .= " AND ";
                }
                $rawDB .= "trips.tripDate <= '" . $toDate . "'";
            }elseif($data['fromDate'] != null && $data['toDate'] != null){
                if($add){
                    $rawDB .= " AND ";
                }
                $rawDB .= "trips.tripDate >= '" . $data['fromDate'] . "'";
            } 
            $emissionData = DB::table('trips')
            ->join('deptsperinstitution', 'trips.deptID', '=', 'deptsperinstitution.deptID')
            ->join('monthlyemissionsperschool', DB::raw('CONCAT(YEAR(trips.tripDate), "-",MONTH(trips.tripDate))'), '=',  DB::raw('CONCAT(YEAR(monthlyemissionsperschool.monthYear), "-",MONTH(monthlyemissionsperschool.monthYear))'))
            ->join('vehicles_mv', 'trips.plateNumber', '=', 'vehicles_mv.plateNumber')
            ->join('cartype_ref', 'vehicles_mv.carTypeID', '=', 'cartype_ref.carTypeID')
            ->join('fueltype_ref', 'vehicles_mv.carTypeID', '=', 'cartype_ref.carTypeID')
            ->select('trips.tripDate', 'trips.tripTime', 'deptsperinstitution.deptName' , 'trips.plateNumber', 
                    'trips.kilometerReading', 'trips.remarks', 'trips.emissions', DB::raw('CONCAT(YEAR(trips.tripDate), "-",MONTH(trips.tripDate)) as monthYear'), 'monthlyemissionsperschool.emission', 'fueltype_ref.fuelTypeName', 'cartype_ref.carTypeName', 'vehicles_mv.modelName', 'vehicles_mv.active') 
            ->whereRaw($rawDB)
            ->orderBy('trips.tripDate', 'asc')
            ->get();
             $emissionCount = DB::table('trips')
            ->join('monthlyemissionsperschool', DB::raw('CONCAT(YEAR(trips.tripDate), "-",MONTH(trips.tripDate))'), '=',  DB::raw('CONCAT(YEAR(monthlyemissionsperschool.monthYear), "-",MONTH(monthlyemissionsperschool.monthYear))'))
            ->select(DB::raw('count(CONCAT(YEAR(trips.tripDate), "-",MONTH(trips.tripDate))) as monthYearCount'))
            ->groupBy(DB::raw('CONCAT(YEAR(monthlyemissionsperschool.monthYear), "-",MONTH(monthlyemissionsperschool.monthYear))'))
            ->get();
        }
        }
    ?>
        var allChart;
        AmCharts.theme = AmCharts.themes.dark;
        var allChartTitle = "Carbon Emission Chart"
        var allChartDataIndexes = [];
        var allChartData = [
            <?php
            {
            $x = 1;
            $prev;
            $monthlyEmissions = [];
            $monthCtr = 0;
              foreach($emissionData as $emission) {
                    if($x == 1){
                        $monthSum = 0;
                        $prev = substr($emission->tripDate, 0, 7);
                    }
                    if($prev == substr($emission->tripDate, 0, 7)){
                        $monthSum += $emission->emission; 
                        $x++;
                        if($x == count($emissionData) - 1){
                            $monthlyEmissions[$monthCtr] = [$prev, $monthSum];
                            $prev = substr($emission->tripDate, 0, 7);
                            $monthSum = 0;
                            $monthCtr++;
                        }
                    }else{
                            $monthlyEmissions[$monthCtr] = [$prev, $monthSum];
                            $prev = substr($emission->tripDate, 0, 7);
                            $monthSum = 0;
                            $monthCtr++;
                        };
              }

            $regressionLine = getRegressionLine($monthlyEmissions);
            $saveIndex = 0;
            for($x = 0 ; $x < count($monthlyEmissions); $x++) {
                echo '{
                "date": "' . $monthlyEmissions[$x][0].'",';
                echo '
                "value": ' . $monthlyEmissions[$x][1].',';
                echo ' 
                "regression": ' . ($regressionLine[0] + ($regressionLine[0] * $x)) . ', ';
                echo '
                "sequestration": 30,';
                echo ' 
                "bullet": "round",';
                echo '
                "subSetTitle": "Monthly Emissions",';
                echo  '
                "subSet": [';
                for($y = $saveIndex; $y < count($emissionData); $y++){
                    if($y==$saveIndex){
                        $prev = substr($emissionData[$y]->tripDate, 0 , 7);
                    }
                    if(substr($emissionData[$y]->tripDate, 0 , 7) == $prev){
                        echo '
                        {
                        "date": "' . $emissionData[$y]->tripDate . '",';
                        echo '
                        "value": "' . $emissionData[$y]->emission . '",';
                        echo '
                        "regression": ' . ($regressionLine[0] + ($regressionLine[0] * $x)) . ', ';
                        echo '
                        "sequestration": "' . 30 . '",';
                        echo '
                        "bullet": "round"  ';
                        $test = $y + 1;
                        if(substr($emissionData[$test]->tripDate, 0 , 7) != $prev){
                            $saveIndex = $y;
                            echo '}]},';
                        }else {
                            echo '},';
                        }

                    }
                }
            }
        }
        ?> {
                "date": <?php
                $yrmonth = end($monthlyEmissions);
                $month = (int) substr($yrmonth[0], 5, 2);
                $yr = (int) substr($yrmonth[0], 0, 4);
                if($month==12){
                    $month = (string) "01";
                    $yr++;
                }elseif($month>=9){
                    $month = (string) $month++;
                }else{
                    $month = (string) "0" . ($month + 1);
                }
                echo '"'.$yr . "-" . $month.'",
                ';
                 ?>
                "regression": <?php echo $regressionLine[0] + ($regressionLine[0] * count($monthlyEmissions) + 1); ?>
            }
        ];
        allChart = AmCharts.makeChart("allChartDiv", {
            "backgroundAlpha": 1,
            "export": {
                "enabled": true
            },
            "type": "serial",
            "titles": [{
                "text": allChartTitle
            }],
            "colors": [
                "#de4c4f",
                "#d8854f",
                "#77ee38",
                "#a7a737"
            ],
            "allLabels": [{
                "text": "",
                "x": 10,
                "y": 15,
                "url": "javascript: goBackChart();void(0);"
            }],
            "dataProvider": allChartData,
            "valueAxes": [{
                "axisAlpha": 0,
                "dashLength": 4,
                "position": "left"
            }],
            "graphs": [{
                "valueField": "value",
                "fillAlphas": 0,
                "bulletField": "bullet"
            }, {
                "valueField": "regression",
                "fillAlphas": 0,
                "bulletField": "bullet"
            }, {
                "valueField": "sequestration",
                "fillAlphas": 0,
                "bulletField": "bullet"
            }],
            "chartCursor": {
                "zoomable": false,
                "fullWidth": true,
                "cursorAlpha": 0.1,
                "categoryBalloonEnabled": false
            },
            "dataDateFormat": "YYYY-MM-DD HH:NN:SS",
            "categoryField": "date",
            "categoryAxis": {
                "parseDates": true,
                "minPeriod": "mm",
                "axisAlpha": 0,
                "minHorizontalGap": 50,
                "gridAlpha": 0,
                "tickLength": 0
            },
        });

        allChart.addListener('clickGraphItem', function(evt) {
            if (evt.item.dataContext.subSet) {
                chartDataIndexes.push({
                    index: evt.index,
                    title: evt.item.dataContext.subSetTitle,
                    prev: evt.chart.titles[0].text
                });
                evt.chart.dataProvider = evt.item.dataContext.subSet;
                evt.chart.allLabels[0].text = "Go Back " + evt.chart.titles[0].text;
                evt.chart.titles[0].text = evt.item.dataContext.subSetTitle;
                evt.chart.validateData();

            }
        });

        function goBackChart() {
            var previousData = allChartData;
            var tmp = {
                prev: ""
            }

            // Remove latest
            chartDataIndexes.pop();

            // Get previous cached object
            for (var i = 0; i < chartDataIndexes.length; i++) {
                tmp = chartDataIndexes[i];
                previousData = previousData[tmp.index].subSet;
            }

            // Apply titles and stuff
            allChart.allLabels[0].text = tmp.prev ? "Go Back " + tmp.prev : "";
            allChart.titles[0].text = tmp.title || allChartTitle;
            allChart.dataProvider = previousData;
            allChart.validateData();
        }
    </script>
    <!-- general emission chart-->


    @endsection