@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Permission')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

    <script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection


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
            <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus p-2 pt-0 pb-0"></i>Add
                a User</a>
            <div class="search-container ">
                <form action="{{ route('users.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search Role..." name="search"
                            value="">
                        <button class="btn  btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
            <form action="{{ route('users.index') }}" method="GET">
                <div class="input-group ">
                    <select name="filter" class="form-select" id="inputGroupSelect04"
                        aria-label="Example select with button addon" equired>
                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Role</option>
                        <option value="1" {{ $filter == '1' ? 'selected' : '' }}>Activated Role</option>
                        <option value="0" {{ $filter == '0' ? 'selected' : '' }}>InActivated Role</option>
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
                            <th scope="col">Role</th>
                            <th>Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-danger h5">No data available</td>
                            </tr>
                        @endif
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->first_name }}</td>
                                <td>
                                    @php
                                        $total = $user->role->count();
                                        $count = 0;
                                    @endphp
                                    @foreach ($user->role as $role)
                                        @if ($count++ < 2)
                                            {{ $role->role_name . ', ' }}
                                        @else
                                            {{ ' +' . $total - 2 }} more
                                        @break;
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <form action="{{ route('users.updateIsActive', ['id' => $user->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="is_active" value="">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" onchange="submit()" type="checkbox"
                                            role="switch" {{ $user->is_active == 1 ? 'checked' : '' }}>
                                    </div>
                                </form>
                            </td>
                            <td class="pt-0">
                                <div class="dropdown" style="position: absolute">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('users.edit', ['id' => $user->id]) }}"><i
                                                class="ti ti-pencil me-1"></i> Edit</a>
                                        {{-- <form action="{{ route('users.delete', ['id' => $user->id]) }}" method="post"
                                            dropdown-item>
                                            @csrf
                                            <button type="submit" class="btn text-danger"><i
                                                    class="ti ti-trash me-1"></i> Delete</button>
                                        </form> --}}
                                        {{-- <button type="button" class="btn btn-primary" class="confirm-color">
                                          Delete
                                        </button> --}}
                                        <button data-id="{{ $user->id }}" type="button" class="btn btn-primary" id="confirm-color">
                                          Delete
                                        </button>
                                        <a href="#"
                                            data-route="{{ route('users.resetPassword', ['id' => $user->id]) }}"
                                            data-email="{{ $user->email }}" class="dropdown-item add-new-role"
                                            data-bs-target="#addRoleModal" data-bs-toggle="modal"
                                            class="dropdown-item add-new-role"><i class="ti ti-key me-1"></i> Reset
                                            Password</a>
                                        <form method="post" action="{{ route('users.forceLogout') }}" dropdown-item>
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit" class="btn text-danger"><i
                                                    class='ti ti-logout me-2'></i> Force Logout</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links('pagination::bootstrap-5') }}

    </div>
</div>
@include('admin.users.resetPassword')

@endsection
{{-- @section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var links = document.querySelectorAll('a[data-bs-toggle="modal"]');
        links.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default action of the link
                var email = this.getAttribute('data-email');
                var form = document.getElementById('resetPasswordForm');
                if (form) {
                    var emailInput = document.getElementById('email');
                    if (emailInput) {
                        emailInput.value = email;
                        var route = this.getAttribute('data-route');
                        if (route) {
                            form.setAttribute('action',
                                route); // Set form action to the route URL
                        } else {
                            console.error('Data-route attribute not found on link.');
                        }
                    } else {
                        console.error('Email input not found.');
                    }
                } else {
                    console.error('Form not found.');
                }
            });
        });
    });

</script>
@endsection --}}

@section('page-script')
<script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
    {{-- <script src="{{ asset('assets/js/app-access-roles.js') }}"></script> --}}
    <script src="{{ asset('assets/js/modal-add-role.js') }}"></script>

    <script src="{{asset('assets/js/extended-ui-sweetalert2.js')}}"></script>

    <script>
      confirmColor = document.querySelector('#confirm-color')
      confirmColor.onclick = function () {

        console.log($(this).data('id'));

        var userId = $(this).data('id');

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
        }).then(function (result) {
          if (result.value) {
            $.ajax ({
              type: 'GET',
              dataType:"json",
              url: "/admin/users/delete/"+userId,
              succces: function(data){
                  console.log('success',data);
                  if(data.success){
                    Swal.fire({
                      icon: 'success',
                      title: 'Deleted!',
                      text: 'Your file has been deleted.',
                      customClass: {
                        confirmButton: 'btn btn-success'
                      }
                  });
                }
              }
            });
          }
        });
    };
    </script>
@endsection
