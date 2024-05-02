@extends('layouts/layoutMaster')

@section('title', 'Create Permission')

@section('content')

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1">Create Permission</span>
                        </div>
                        <form class="mb-3" action="{{ route('permissions.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Permission Name *</label>
                                <input type="text" class="form-control" name="permission_name" autofocus required>
                                @error('permission_name')
                                    <div class="text-danger pt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleFormControlTextarea1">Description</label>
                                <textarea class="form-control" rows="3" name="description"></textarea>
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
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][add_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][add_access]" type="checkbox"
                                                    value="1">
                                            </td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][view_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][view_access]" type="checkbox"
                                                    value="1">
                                            </td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][edit_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][edit_access]" type="checkbox"
                                                    value="1">
                                            </td>
                                            <td>
                                                <input type="hidden" name="modules[{{ $module->code }}][delete_access]"
                                                    value="0">
                                                <input class="form-check-input"
                                                    name="modules[{{ $module->code }}][delete_access]" type="checkbox"
                                                    value="1">
                                            </td>
                                        </tr>
                                        @foreach ($module->submodules as $submodule)
                                            @if ($submodule->is_active == '1')
                                                <tr class="submodule">
                                                    <td class="p-5 pb-0 pt-0">{{ $submodule->module_name }}</td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][add_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][add_access]"
                                                            type="checkbox" value="1">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][view_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][view_access]"
                                                            type="checkbox" value="1">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][edit_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][edit_access]"
                                                            type="checkbox" value="1">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="modules[{{ $submodule->code }}][delete_access]"
                                                            value="0">
                                                        <input class="form-check-input"
                                                            name="modules[{{ $submodule->code }}][delete_access]"
                                                            type="checkbox" value="1">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Create</button>
                                    <a href="{{ route('permissions.index') }}" class="btn btn-label-secondary">Cancle</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--/ Toast message -->
    <div class="bs-toast toast toast-ex animate__animated animate__tada my-2" role="alert" aria-live="assertive"
        aria-atomic="true" data-bs-delay="2000">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2 "></i>
            <div class="me-auto fw-semibold toast-title"></div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        </div>
    </div>
    <!--/ Toast message -->

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('input[type="checkbox"]').change(function() {
                var $checkbox = $(this);
                var module = $checkbox.attr('name');
                var module_access = module.substring(module.lastIndexOf("[") + 1, module
                    .lastIndexOf("]"));
                var isSubmodule = $checkbox.closest('tr').hasClass(
                    'submodule'); // Check if it's a submodule

                if (isSubmodule) {
                    // Find the main module checkbox
                    var $mainModuleRow = $checkbox.closest('tr').prevAll('tr:not(.submodule)').first();

                    var nameAttribute = $mainModuleRow.find('input[type="checkbox"]').attr('name');

                    var moduleCode = nameAttribute.substring(nameAttribute.indexOf("[") + 1, nameAttribute
                        .indexOf("]"));

                    var mainModuleCheckbox = document.querySelector('input[name="modules[' +
                        moduleCode +
                        '][view_access]"][type="checkbox"]');

                    if (mainModuleCheckbox && !mainModuleCheckbox.checked) {
                        mainModuleCheckbox.checked = true;
                    }

                    if (module_access !== 'view_access') {
                        mainModuleCheckbox = document.querySelector('input[name="modules[' +
                            moduleCode +
                            '][' + module_access + ']"][type="checkbox"]');
                        if (mainModuleCheckbox && !mainModuleCheckbox.checked) {
                            mainModuleCheckbox.checked = true;
                        }
                    }
                }
            });
        });
    </script>


@endsection

@section('page-script')
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" type="text/javascript"></script>
    {{-- Script for toast message --}}
    @if (session('error'))
        @php
            $errorMessage = session('error');
            session()->forget('error');
        @endphp

        <script>
            var errorMessage = {!! json_encode($errorMessage) !!};
            $('.toast-body').text(errorMessage);
            $('i.ti-bell').addClass('text-danger');
            $('.toast-title').text('Error');
            new bootstrap.Toast($('.toast-ex')[0]).show();
        </script>
    @endif
    {{-- Script for toast message --}}
@endsection
