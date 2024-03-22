@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Modules 2')

@section('content')




<div class="card">
  <div class="card-header d-flex justify-content-between m-5 mb-2">
    <a href="{{ route('permissions.create') }}" class="btn btn-primary">Add a Permission</a>
    <div class="search-container ">
      <form action="{{ route('permissions.index') }}" method="GET">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search Permission..." name="search" value="">
          <button class="btn  btn-primary" type="submit"><i class="fas fa-search"></i></button>
        </div>
      </form>
    </div>
    <form action="{{ route('permissions.index') }}" method="GET">
      <div class="input-group ">
        <select name="filter" class="form-select" id="inputGroupSelect04" aria-label="Example select with button addon" equired>
          <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Permission</option>
          <option value="1" {{ $filter == '1' ? 'selected' : '' }}>Activated Permission</option>
          <option value="0" {{ $filter == '0' ? 'selected' : '' }}>InActivated Permission</option>
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
        @foreach ($permissions as $permission)
            <tr>
              <td>{{ $permission->permission_name }}</td>
              <td>{{ $permission->description }}</td>
              <td>
                <form action="{{ route('permissions.updateIsActive', ['id' => $permission->id]) }}" method="POST">
                  @csrf
                  <input type="hidden" name="is_active" value="">
                  <div class="form-check form-switch">
                      <input class="form-check-input" onchange="submit()" type="checkbox" role="switch" id="switchCheckDefault" {{ $permission->is_active == 1 ? 'checked' : '' }}>
                  </div>
                </form>
              </td>
              <td class="d-flex">
                <a href="{{ route('permissions.edit', ['id' => $permission->id]) }}" class="m-2"><i class="fa-solid fa-pen-to-square"></i></a>
                <form action="{{ route('permissions.delete', ['id' => $permission->id]) }}" method="post">
                  @csrf
                  <button type="submit" onclick="return confirm('Are you sure you want to Delete?')" class="btn text-danger"><i class="fa-solid fa-trash"></i></button>
                </form>
              </td>
            </tr>
        @endforeach
      </tbody>
    </table>
    </div>
  </div>
</div>

@endsection
