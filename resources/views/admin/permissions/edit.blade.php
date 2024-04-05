@php
$configData = Helper::appClasses();
@endphp

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/extended-ui-sweetalert2.js')}}"></script>
@endsection

@extends('layouts/layoutMaster')

@section('title', 'Edit Permission')

@section('content')

<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4 mx-auto">
      <div class="card">
        <div class="card-body">
          <div class="app-brand justify-content-center mb-4 mt-2">
            <span class="app-brand-text demo text-body fw-bold ms-1">Edit Permission</span>
          </div>
          <form id="formAuthentication" class="mb-3" action="{{ route('permissions.update', ['id' => $permission->id]) }}" method="post">
            @csrf
            <div class="mb-3">
              <label class="form-label">Permission Name *</label>
              <input type="text" class="form-control" id="email" name="permission_name" value="{{ $permission->permission_name }}" autofocus required>
              @error('permission_name')
                <div class="text-danger pt-2">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group mb-3">
              <label for="exampleFormControlTextarea1">Description</label>
              <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description">{{ $permission->description }}</textarea>
            </div>

            <table class="table">
              <thead>
                <tr>
                  <th>Module_name</th>
                  <th>Add</th>
                  <th>View</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              {{-- {{ dd($permission) }} --}}
              <tbody class="table-border-bottom-0">
                @foreach ($modules as $module)
                  @php
                    $permissionModules = $permission->module ?? collect();
                    $moduleData = $permissionModules->firstWhere('code', $module->code);
                    $pivotData = $moduleData ? $moduleData->pivot : null;
                  @endphp
                  <tr>
                    <td><strong>{{ $module->module_name }}</strong></td>
                    <td><input class="form-check-input" name="modules[{{ $module->code }}][add_access]" type="checkbox" id="flexCheckChecked" {{ optional($pivotData)->add_access == '1' ? 'checked' : '' }}></td>
                    <td><input class="form-check-input" name="modules[{{ $module->code }}][view_access]" type="checkbox" id="flexCheckChecked" {{ optional($pivotData)->view_access == '1' ? 'checked' : '' }}></td>
                    <td><input class="form-check-input" name="modules[{{ $module->code }}][edit_access]" type="checkbox" id="flexCheckChecked" {{ optional($pivotData)->edit_access == '1' ? 'checked' : '' }}></td>
                    <td><input class="form-check-input" name="modules[{{ $module->code }}][delete_access]" type="checkbox" id="flexCheckChecked" {{ optional($pivotData)->delete_access == '1' ? 'checked' : '' }}></td>
                    {{-- <td><input class="form-check-input" name="modules[{{ $module->code }}][add_access]" type="checkbox" id="flexCheckChecked" {{ isset($pivotData) && $pivotData->add_access == '1' ? 'checked' : '' }}></td>
                    <td><input class="form-check-input" name="modules[{{ $module->code }}][view_access]" type="checkbox" id="flexCheckChecked" {{ isset($pivotData) && $pivotData->view_access == '1' ? 'checked' : '' }}></td>
                    <td><input class="form-check-input" name="modules[{{ $module->code }}][edit_access]" type="checkbox" id="flexCheckChecked" {{ isset($pivotData) && $pivotData->edit_access == '1' ? 'checked' : '' }}></td>
                    <td><input class="form-check-input" name="modules[{{ $module->code }}][delete_access]" type="checkbox" id="flexCheckChecked" {{ isset($pivotData) && $pivotData->delete_access == '1' ? 'checked' : '' }}></td> --}}
                  </tr>
                  @foreach ($module->submodules as $submodule)
                    @php
                      $permissionModules = $permission->module ?? collect();
                      $moduleData = $permissionModules->firstWhere('code', $submodule->code);
                      $pivotData = $moduleData ? $moduleData->pivot : null;
                    @endphp
                    <tr>
                      <td class="p-5 pb-0 pt-0">{{ $submodule->module_name }}</td>
                      <td><input class="form-check-input" name="modules[{{ $submodule->code }}][add_access]" type="checkbox" id="flexCheckChecked" {{ optional($pivotData)->add_access == '1' ? 'checked' : '' }}></td>
                      <td><input class="form-check-input" name="modules[{{ $submodule->code }}][view_access]" type="checkbox" id="flexCheckChecked" {{ optional($pivotData)->view_access == '1' ? 'checked' : '' }}></td>
                      <td><input class="form-check-input" name="modules[{{ $submodule->code }}][edit_access]" type="checkbox" id="flexCheckChecked" {{ optional($pivotData)->edit_access == '1' ? 'checked' : '' }}></td>
                      <td><input class="form-check-input" name="modules[{{ $submodule->code }}][delete_access]" type="checkbox" id="flexCheckChecked" {{ optional($pivotData)->delete_access == '1' ? 'checked' : '' }}></td>
                    </tr>
                  @endforeach
                @endforeach
              </tbody>
            </table>

            <div class="row">
              <div class="mt-3">
                <button type="submit" class="btn btn-primary me-2">Update</button>
                <button type="button" class="btn btn-label-secondary"><a href="{{ route('permissions.index') }}">Cancle</a></button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>


@endsection
