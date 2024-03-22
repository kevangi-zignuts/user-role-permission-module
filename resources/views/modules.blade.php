@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Modules 2')

@section('content')





@php
  $i = 1;
@endphp
<div class="card">
  <div class="card-header d-flex justify-content-between m-5 mb-2">
    <div class="search-container ">
      <form action="{{ route('pages-page-2') }}" method="GET">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search modules..." name="search" value="">
          <button class="btn  btn-primary" type="submit"><i class="fas fa-search"></i></button>
        </div>
      </form>
    </div>
    <form action="{{ route('pages-page-2') }}" method="GET">
      <div class="input-group ">
        <select name="filter" class="form-select" id="inputGroupSelect04" aria-label="Example select with button addon" equired>
          <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Modules</option>
          <option value="1" {{ $filter == '1' ? 'selected' : '' }}>Activated Modules</option>
          <option value="0" {{ $filter == '0' ? 'selected' : '' }}>InActivated Modules</option>
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
          {{-- <th></th> --}}
          <th></th>
          <th scope="col">Name</th>
          <th scope="col">Description</th>
          <th>Status</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($modules as $module)
          {{-- @if($module->parent_code === null) --}}
            <tr>
              <td class="clickable" data-toggle="collapse" data-target="#subModules_{{ $module->code }}_{{ $i }}" aria-expended="false" aria-controls="subModules_{{ $module->code }}_{{ $i }}"><button class="btn btn-default btn-xs"><i class="fa-solid fa-caret-down"></i></button></td>
              <td>{{ $module->module_name }}</td>
              <td>{{ $module->description }}</td>
              <td>
                <form action="{{ route('modules.updateIsActive', ['code' => $module->code]) }}" method="POST">
                  @csrf
                  <input type="hidden" name="is_active" value="{{ $module->is_active ? '0' : '1' }}">
                  <div class="form-check form-switch">
                      <input class="form-check-input" onchange="submit()" type="checkbox" role="switch" id="switchCheckDefault" {{ $module->is_active == 1 ? 'checked' : '' }}>
                  </div>
                </form>
              </td>
              <td><a href="{{ route('modules.edit', ['code' => $module->code]) }}"><i class="fa-solid fa-pen-to-square"></i></a></td>
            </tr>
            <tr id="subModules_{{ $module->code }}_{{ $i }}" class="collapse">
              <td colspan="4">
                  <table class="table">
                    <thead class="">
                      <tr class="info">
                        <th scope="col">Submodule Name</th>
                        <th scope="col">Description</th>
                        <th>Status</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($module->submodules as $submodule)
                        <tr>
                          <td>{{ $submodule->module_name }}</td>
                          <td>{{ $submodule->description }}</td>
                          <td>
                            <form action="{{ route('modules.updateIsActive', ['code' => $submodule->code]) }}" method="POST">
                              @csrf
                              <input type="hidden" name="is_active" value="{{ $submodule->is_active ? '0' : '1' }}">
                              <div class="form-check form-switch">
                                  <input class="form-check-input" onchange="submit()" type="checkbox" role="switch" id="switchCheckDefault" {{ $submodule->is_active == 1 ? 'checked' : '' }}>
                              </div>
                            </form>
                          </td>
                          <td><a href="{{ route('modules.edit', ['code' => $submodule->code]) }}"><i class="fa-solid fa-pen-to-square"></i></a></td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
              </td>
            </tr>
          {{-- @endif --}}
          @php
            $i++;
          @endphp
        @endforeach
      </tbody>
    </table>
    </div>
    </div>

  </div>
</div>


@endsection
