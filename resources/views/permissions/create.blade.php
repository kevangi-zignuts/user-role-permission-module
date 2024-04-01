@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Create Module')

@section('content')

<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4 mx-auto">
      <div class="card">
        <div class="card-body">
          <div class="app-brand justify-content-center mb-4 mt-2">
            <span class="app-brand-text demo text-body fw-bold ms-1">Create Permission</span>
          </div>
          <form id="formAuthentication" class="mb-3" action="{{ route('permissions.store') }}" method="post">
            @csrf
            <div class="mb-3">
              <label class="form-label">Permission Name</label>
              <input type="text" class="form-control" name="permission_name" autofocus>
              <div class="valid-feedback"> Looks good! </div>
              @error('role_name')
                <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group mb-3">
              <label for="exampleFormControlTextarea1">Description</label>
              <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"></textarea>
            </div>

            <table class="table">
              <thead>
                <tr>
                  <th>Module Name</th>
                  <th>Add</th>
                  <th>View</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach ($modules as $module)
                <tr>
                  <td><strong>{{ $module->module_name }}</strong></td>
                  <td><input class="form-check-input" name="modules[{{ $module->code }}][add_access]" type="checkbox" id="add_{{ $module->code }}"></td>
                  <td><input class="form-check-input" name="modules[{{ $module->code }}][view_access]" type="checkbox" id="view_{{ $module->code }}"></td>
                  <td><input class="form-check-input" name="modules[{{ $module->code }}][edit_access]" type="checkbox" id="edit_{{ $module->code }}"></td>
                  <td><input class="form-check-input" name="modules[{{ $module->code }}][delete_access]" type="checkbox" id="delete_{{ $module->code }}"></td>
                </tr>
                @foreach ($module->submodules as $submodule)
                  <tr>
                    <td class="p-5 pb-0 pt-0">{{ $submodule->module_name }}</td>
                    <td><input class="form-check-input" name="modules[{{ $submodule->code }}][add_access]" type="checkbox" id="add_{{ $submodule->code }}"></td>
                    <td><input class="form-check-input" name="modules[{{ $submodule->code }}][view_access]" type="checkbox" id="view_{{ $submodule->code }}"></td>
                    <td><input class="form-check-input" name="modules[{{ $submodule->code }}][edit_access]" type="checkbox" id="edit_{{ $submodule->code }}"></td>
                    <td><input class="form-check-input" name="modules[{{ $submodule->code }}][delete_access]" type="checkbox" id="delete_{{ $submodule->code }}"></td>
                  </tr>
                  @endforeach
                @endforeach
              </tbody>
            </table>

            <div class="mb-3 mt-3">
              <button class="btn btn-primary d-grid" type="submit">Create</button>
            </div>
          </form>
        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>

@endsection
