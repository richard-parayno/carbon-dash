@extends('layouts.main')

@section('styling')
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
  #box-form table {
    color: white;
  }
  #box-form table th {
    text-align: center;
  }
</style>
@endsection

@section('content')
<div class="ten columns offset-by-one" id="box-form">
  <h1>Manage Active Vehicles</h1>
  @if(Session::has('success'))
      <div class="twelve columns" id="success-message" style="color: green; margin-bottom: 20px;">
          <strong>Success! </strong> {{ Session::get('message', '') }}
      </div>
  @endif    
  <br>
  <table class="u-max-full-width">
    <thead>
      <tr>
        <th>Car Type</th>
        <th>Car Model</th>
        <th>Plate Number</th>
        <th>Home Campus</th>
        <th>Fuel Type</th>
        <th>Status</th>
        <th>Vehicle Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($vehicles as $vehicle)
      <tr>
        @foreach($cartypes as $cartype)
          @if($vehicle->carTypeID == $cartype->carTypeID)
            <td>{{ $cartype->carTypeName }}</td>
          @endif
        @endforeach


        <td>{{ $vehicle->modelName }}</td>
        <td>{{ $vehicle->plateNumber }}</td>

        @foreach($institutions as $institution)
          @if($vehicle->institutionID == $institution->institutionID)
            <td>{{ $institution->institutionName }}</td>
          @endif
        @endforeach

        @foreach($fueltype as $fuel)
          @if($vehicle->fuelTypeID == $fuel->fuelTypeID)
            <td>{{ $fuel->fuelTypeName }}</td>
          @endif
        @endforeach

        @if($vehicle->active >= 1)
          <td>Active</td>
        @else
          <td>Inactive</td>
        @endif

        <td style="text-align: center;">
          <a href="{{ route('vehicle-editinfo', array('vehicle' => $vehicle->plateNumber)) }}">Update Vehicle Info</a> <br> <br>
          <a href="{{ route('vehicle-decommission', array('vehicle' => $vehicle->plateNumber)) }}">Update Vehicle Status</a>
        </td>
      </tr>
      @endforeach
    </tbody>
    <!-- action shortcuts -->
    <a href="{{ route('vehicle-add') }}">
      <button class="button-primary">New Vehicle</button>
    </a>
    <!--
    <div class="u-pull-right">
      <span>Search Vehicle: </span>
      <input type="text" placeholder="Accord" id="searchBox">
    </div>
    -->
    <!-- action shortcuts -->              
  </table>
</div>

@php
$inactive = DB::table('vehicles_mv')->where('active', 0)->get();
@endphp

@if ($inactive->isNotEmpty())
<div class="ten columns offset-by-one" id="box-form">
  <h1>Manage Inactive Vehicles</h1>    
  <table class="u-max-full-width">
    <thead>
      <tr>
        <th>Car Type</th>
        <th>Car Model</th>
        <th>Plate Number</th>
        <th>Home Campus</th>
        <th>Fuel Type</th>
        <th>Status</th>
        <th>Vehicle Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($inactive as $inactiveVehicle)
      <tr>
        @foreach($cartypes as $cartype)
          @if($vehicle->carTypeID == $cartype->carTypeID)
            <td>{{ $cartype->carTypeName }}</td>
          @endif
        @endforeach


        <td>{{ $inactiveVehicle->modelName }}</td>
        <td>{{ $inactiveVehicle->plateNumber }}</td>

        @foreach($institutions as $institution)
          @if($inactiveVehicle->institutionID == $institution->institutionID)
            <td>{{ $institution->institutionName }}</td>
          @endif
        @endforeach

        @foreach($fueltype as $fuel)
          @if($inactiveVehicle->fuelTypeID == $fuel->fuelTypeID)
            <td>{{ $fuel->fuelTypeName }}</td>
          @endif
        @endforeach

        @if($inactiveVehicle->active >= 1)
          <td>Active</td>
        @else
          <td>Inactive</td>
        @endif

        <td style="text-align: center;">
          <a href="{{ route('vehicle-editinfo', array('vehicle' => $inactiveVehicle->plateNumber)) }}">Update Vehicle Info</a> <br> <br>
          <a href="{{ route('vehicle-decommission', array('vehicle' => $inactiveVehicle->plateNumber)) }}">Update Vehicle Status</a>
        </td>
      </tr>
      @endforeach
    </tbody>
    <!-- action shortcuts -->
    <!--
    <div class="u-pull-right">
      <span>Search Vehicle: </span>
      <input type="text" placeholder="Accord" id="searchBox">
    </div>
    -->
    <!-- action shortcuts -->              
  </table>
</div>
@endif
@endsection