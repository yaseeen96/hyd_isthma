@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $batch->id ?? '', 'ptitle' => 'Qr Batch Registration', 'ctitle' => $batch->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Programs')
@section('content')
    <!-- Main content -->
    <x-content-wrapper>
        <x-slot:title>
            {{ $batch->id ? 'Edit' : 'Add' }} Qr Batch Registration
        </x-slot>
        <form
            action="{{ $batch->id ? route('qrBatchRegistrations.update', $batch->id) : route('qrBatchRegistrations.store') }}"
            autocomplete="off" method="post" enctype="multipart/form-data">
            @csrf
            {{ $batch->id ? method_field('PUT') : '' }}
            <div class="card-body ">
                <div class="row">
                    <div class="col-lg-12">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show">
                                {{ session('warning') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        {{-- Name --}}
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label for="topic">Batch ID</label>
                                <span class="form-control">{{ $batch->batch_id }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{-- Type --}}
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label for="topic">Batch Type</label>
                                <span class="form-control">{{ $batch->batch_type }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{-- Name --}}
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label for="full_name">Full Name</label>
                                <input type="text" class="form-control" name="full_name" id="full_name"
                                    value="{{ old('full_name', $batch->full_name) }}">
                                @if ($errors->has('full_name'))
                                    <span class="text-danger">
                                        {{ $errors->first('full_name') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{-- Gender --}}
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label for="gender">Gender</label>
                                <select class="form-control" name="gender">
                                    <option {{ old('gender', $batch->gender) == 'Male' ? 'selected' : '' }} value="Male">
                                        Male
                                    </option>
                                    <option {{ old('gender', $batch->gender) == 'Female' ? 'selected' : '' }}
                                        value="Femae">
                                        Female</option>
                                </select>
                                @if ($errors->has('gender'))
                                    <span class="text-danger">
                                        {{ $errors->first('gender') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{-- Email --}}
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email" id="email"
                                    value="{{ old('email', $batch->email) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{-- Phone Number --}}
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" id="phone_number"
                                    value="{{ old('phone_number', $batch->phone_number) }}">
                                @if ($errors->has('phone_number'))
                                    <span class="text-danger">
                                        {{ $errors->first('phone_number') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="zone_name">ZONE NAME</label>
                            <select class="form-control select2bs4" style="width: 100%;" id="zone_name" name="zone_name"
                                onchange="getLocations('zone_name', 'division_name')">
                                @isset($locationsList['distnctZoneName'])
                                    <option value="">All</option>
                                    @foreach ($locationsList['distnctZoneName'] as $name)
                                        <option {{ old('zone_name', $batch->zone_name) == $name->zone_name ? 'selected' : '' }}
                                            value="{{ $name->zone_name }}"> {{ $name->zone_name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            @if ($errors->has('zone_name'))
                                <span class="text-danger">
                                    {{ $errors->first('zone_name') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="division_name">DISTRICT NAME</label>
                            <select class="form-control select2bs4" style="width: 100%;" id="division_name"
                                name="division_name" onchange="getLocations('division_name', 'unit_name')">
                                <option value="">All</option>
                                @if (old('division_name', $batch->division_name))
                                    <option selected value="{{ old('division_name', $batch->division_name) }}">
                                        {{ old('division_name', $batch->division_name) }}
                                    </option>
                                @endif
                            </select>
                            @if ($errors->has('division_name'))
                                <span class="text-danger">
                                    {{ $errors->first('division_name') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit_name">UNIT NAME</label>
                            <select class="form-control select2bs4" style="width: 100%;" id="unit_name" name="unit_name"
                                placeholder="Select Unit Name">
                                <option value="">All</option>
                                @if (old('unit_name', $batch->unit_name))
                                    <option selected value="{{ old('unit_name', $batch->unit_name) }}">
                                        {{ old('unit_name', $batch->unit_name) }}
                                    </option>
                                @endif
                            </select>
                            @if ($errors->has('unit_name'))
                                <span class="text-danger">
                                    {{ $errors->first('unit_name') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12 text-right">
                        <button class="btn btn-purple  mr-2">{{ $batch->id ? 'Update' : 'Save' }}</button>
                        <a href="{{ route('qrBatchRegistrations.index') }}" class="btn btn-secondary ">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </x-content-wrapper>
    <!-- /.content -->
@endsection
@push('scripts')
@endpush
