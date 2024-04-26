@extends('layouts/layoutMaster')

@section('title', 'Company - Create')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <span class="app-brand-text demo text-body fw-bold ms-1">Add Company Details</span>
                        </div>
                        @if ($errors && $errors->any())
                            @dd($errors->all())
                        @endif
                        <form action="{{ route('company.store') }}" method="post" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Company Name *</label>
                                <input type="text" class="form-control" name="company_name" autofocus>
                                @error('company_name')
                                    @dd('company_name')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Owner Name *</label>
                                <input type="text" class="form-control" name="owner_name" autofocus>
                                @error('owner_name')
                                    @dd('owner_name')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Industry *</label>
                                <input type="text" class="form-control" name="industry" autofocus>
                                @error('industry')
                                    @dd('industry')
                                    <div class="pt-2 text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Add</button>
                                    <a href="{{ route('company.index') }}" class="btn btn-label-secondary">Cancle</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
