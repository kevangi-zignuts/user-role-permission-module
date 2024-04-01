@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')


{{-- @section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/extended-ui-sweetalert2.js')}}"></script>
@endsection --}}

@section('title', 'Permission')

@section('content')


@if (session('success'))
      <div class="alert alert-success" role="alert">
          {{ session('success') }}
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger" role="alert">
          {{ session('error') }}
      </div>
    @endif

<div class="card">
  <div class="card-header d-flex justify-content-between m-5 mb-2">
    <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add a Role</a>
    <div class="search-container ">
      <form action="{{ route('roles.index') }}" method="GET">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search Role..." name="search" value="">
          <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        </div>
      </form>
    </div>
    <form action="{{ route('roles.index') }}" method="GET">
      <div class="input-group ">
        <select name="filter" class="form-select" id="inputGroupSelect04" aria-label="Example select with button addon" equired>
          <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Role</option>
          <option value="1" {{ $filter == '1' ? 'selected' : '' }}>Activated Role</option>
          <option value="0" {{ $filter == '0' ? 'selected' : '' }}>InActivated Role</option>
        </select>
        <button class="btn btn-outline-primary" type="submit">Filter</button>
      </div>
    </form>
  </div>
  <div class="card-body">
    <div class="table-responsive text-nowrap">

    <table class="table">
      <thead class="table-dark">
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Description</th>
          <th>Status</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        @if ($roles->isEmpty())
          <tr >
            <td colspan="5" class="text-center text-danger h5">No data available</td>
          </tr>
        @endif
        @foreach ($roles as $role)
            <tr>
              <td>{{ $role->role_name }}</td>
              <td>{{ $role->description }}</td>
              <td>
                <form action="{{ route('roles.updateIsActive', ['id' => $role->id]) }}" method="get">
                  {{-- @csrf --}}
                  <input type="hidden" name="is_active" value="">
                  <div class="form-check form-switch">
                      <input class="form-check-input" onchange="submit()" type="checkbox" role="switch" id="switchCheckDefault" {{ $role->is_active == 1 ? 'checked' : '' }}>
                  </div>
                </form>
              </td>
              <td class="pt-0">
                <div class="dropdown" style="position: absolute">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('roles.edit', ['id' => $role->id]) }}"><i class="ti ti-pencil me-1"></i> Edit</a>
                    <form action="{{ route('roles.delete', ['id' => $role->id]) }}" method="post" dropdown-item>
                      @csrf
                      <button type="submit" class="btn text-danger" onclick="confirm('Are you sure you Want to delete?')"><i class="ti ti-trash me-1"></i> Delete</button>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
        @endforeach
      </tbody>
    </table>
    </div>
  </div>
</div>

@endsection
