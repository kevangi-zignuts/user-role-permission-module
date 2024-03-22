@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
<h4>Home Page</h4>
<p>For more layout options refer <a href="{{ config('variables.documentation') ? config('variables.documentation') : '#' }}" target="_blank" rel="noopener noreferrer">documentation</a>.</p>

<div class="row mb-5">
  <div class="col-md-6 col-xl-4">
    <div class="card bg-primary text-white mb-3">
      <div class="card-body">
        <h3 class="card-title text-white mt-3 text-center">Module Count :- {{ $module_count }}</h3>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-4">
    <div class="card bg-primary text-white mb-3">
      <div class="card-body">
        <h3 class="card-title text-white mt-3 text-center">Permission Count :- {{ $permission_count }}</h3>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-4">
    <div class="card bg-primary text-white mb-3">
      <div class="card-body">
        <h3 class="card-title text-white mt-3 text-center">Role Count :- </h3>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-4">
    <div class="card bg-primary text-white mb-3">
      <div class="card-body">
        <h3 class="card-title text-white mt-3 text-center">Users Count :- </h3>
      </div>
    </div>
  </div>

</div>

@endsection
