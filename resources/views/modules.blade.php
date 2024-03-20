@php
$configData = Helper::appClasses();
$currentFilter = request()->input('filter', 'all');
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Modules 2')

@section('content')

<form action="{{ route('pages-page-2') }}" method="GET">
  <div class="filter m-3">
      <label for="" class="mb-2">Filter  :- </label>
      <select name="filter" class="form-control" required>
          {{-- <option value="" disabled selected>Choose...</option> --}}
          <option value="all" {{ $currentFilter == 'all' ? 'selected' : '' }}>All Modules</option>
          <option value="active" {{ $currentFilter == 'active' ? 'selected' : '' }}>Activated Modules</option>
          <option value="inactive" {{ $currentFilter == 'inactive' ? 'selected' : '' }}>InActivated Modules</option>
      </select>
      <button type="submit" class="btn btn-primary">Filter</button>
  </div>
</form>

<table class="table">
  <thead class="thead-dark">
    <tr>
      <th></th>
      <th scope="col">Name</th>
      <th scope="col">Description</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($modules as $module)
      @if($module->parent_code === null)
        <tr data-toggle="collapse" data-target="#submodule_{{ $module->id }}" class="accordion-toggle">
          <td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></td>
          <td>{{ $module->module_name }}</td>
          <td>{{ $module->description }}</td>
          <td><a href="{{ route('modules.edit', ['code' => $module->code]) }}"><i class="fa-solid fa-pen-to-square"></i></a></td>
        </tr>
        <tr>
          <td colspan="4" class="hiddenRow">
            <div class="collapse" id="submodule_{{ $module->id }}">
              <table class="table table-striped">
                <thead class="thead-dark">
                  <tr class="info">
                    <th scope="col">Submodule Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($module->submodules as $submodule)
                    <tr>
                      <td>{{ $submodule->module_name }}</td>
                      <td>{{ $submodule->description }}</td>
                      <td><a href="{{ route('modules.edit', ['code' => $module->code]) }}"><i class="fa-solid fa-pen-to-square"></i></a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </td>
        </tr>
      @endif
    @endforeach
  </tbody>
</table>

@endsection
