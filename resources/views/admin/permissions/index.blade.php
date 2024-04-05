@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('title', 'Permission')

@section('content')


    @if (session('success'))
        <div class="alert alert-success" id="alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between m-5 mb-2">
            <a href="{{ route('permissions.create') }}" class="btn btn-primary"><i
                    class="fa-solid fa-plus p-2 pt-0 pb-0"></i> Add a Permission</a>
            <div class="search-container ">
                <form action="{{ route('permissions.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Permission..." name="search"
                            value="">
                        <button class="btn  btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
            <form action="{{ route('permissions.index') }}" method="GET">
                <div class="input-group ">
                    <select name="filter" class="form-select" id="inputGroupSelect04"
                        aria-label="Example select with button addon" equired>
                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Permission</option>
                        <option value="1" {{ $filter == '1' ? 'selected' : '' }}>Activated Permission</option>
                        <option value="0" {{ $filter == '0' ? 'selected' : '' }}>InActivated Permission</option>
                    </select>
                    <button class="btn btn-outline-primary" type="submit">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap p-2">

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
                        @if ($permissions->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->permission_name }}</td>
                                <td>{{ $permission->description }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input data-id="{{ $permission->id }}" class="form-check-input toggle-class"
                                            type="checkbox" role="switch" id="switchCheckDefault" data-onstyle="danger"
                                            data-offstyle="info" data-toggle="toggle" data-on="Pending" data-off="Approved"
                                            {{ $permission->is_active == 1 ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="pt-0">
                                    <div class="dropdown" style="position: absolute;">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('permissions.edit', ['id' => $permission->id]) }}"><i
                                                    class="ti ti-pencil me-1"></i> Edit</a>
                                            {{-- <form action="{{ route('permissions.delete', ['id' => $permission->id]) }}"
                                                method="post" dropdown-item>
                                                @csrf
                                                <button type="submit" class="btn text-danger"
                                                    onclick="return confirm('Are you sure you want to Delete?')"><i
                                                        class="ti ti-trash me-1"></i> Delete</button>
                                            </form> --}}
                                            <button data-id="{{ $permission->id }}" class="btn text-danger delete-class"
                                              type="button"><i class="ti ti-trash me-1"></i> Delete</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $permissions->links('pagination::bootstrap-5') }}
        </div>
    </div>
    </div>

@endsection


@section('page-script')
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
    <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            toggleSwitches = document.querySelectorAll('.toggle-class');
            toggleSwitches.forEach(function(toggleSwitch) {
                toggleSwitch.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        customClass: {
                            confirmButton: 'btn btn-primary me-3',
                            cancelButton: 'btn btn-label-secondary'
                        },
                        buttonsStyling: false
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            var status = $(toggleSwitch).prop('checked') == true ? 1 : 0;
                            var id = $(toggleSwitch).data('id');
                            $.ajax({
                                type: "GET",
                                dataType: "json",
                                url: "/admin/permissions/status/" + id,
                                success: function(data) {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Status Updated!!',
                                            text: data.message,
                                            customClass: {
                                                confirmButton: 'btn btn-success'
                                            }
                                        });
                                    }
                                }
                            });
                        }else {
                            var currentState = $(toggleSwitch).prop('checked');
                            $(toggleSwitch).prop('checked', !currentState);
                        }
                    });
                });
            });
        });
    </script>


<script>
  document.addEventListener("DOMContentLoaded", function() {
      deleteButtons = document.querySelectorAll('.delete-class');
      deleteButtons.forEach(function(deleteButton) {
          deleteButton.addEventListener('click', function() {
              var row = this.closest('tr');
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Yes, delete it!',
                  customClass: {
                      confirmButton: 'btn btn-primary me-3',
                      cancelButton: 'btn btn-label-secondary'
                  },
                  buttonsStyling: false
              }).then(function(result) {
                  if (result.isConfirmed) {
                      var id = $(deleteButton).data('id');
                      $.ajax({
                          type: "GET",
                          dataType: "json",
                          url: "/admin/permissions/delete/" + id,
                          success: function(data) {
                              if (data.success) {
                                  row.remove();
                                  Swal.fire({
                                      icon: 'success',
                                      title: 'Status Updated!!',
                                      text: data.message,
                                      customClass: {
                                          confirmButton: 'btn btn-success'
                                      }
                                  });
                              }
                          }
                      });
                  }
              });
          })
      });
  });
</script>
@endsection
