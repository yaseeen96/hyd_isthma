@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $place->id ?? '', 'ptitle' => 'Check In Out Places', 'ctitle' => $place->id ? 'Edit' : 'Add'])
@section('pagetitle', 'Operators')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $place->id ? 'Edit' : 'Add' }} Check In Out Place
                    </h3>
                </div>
                <form
                    action="{{ $place->id ? route('checkInOutPlaces.update', $place->id) : route('checkInOutPlaces.store') }}"
                    method="post">
                    @csrf
                    {{ $place->id ? method_field('PUT') : '' }}
                    <div class="card-body ">
                        <div class="row">
                            {{-- Place Name --}}
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="place_name">Place Name</label>
                                        <input type="text" class="form-control" name="place_name" id="place_name"
                                            value="{{ old('place_name', $place->place_name) }}">
                                        @if ($errors->has('place_name'))
                                            <span class="text-danger">
                                                {{ $errors->first('place_name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Phone Number --}}
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="member_type">Members Type</label>
                                        <select class="form-control select2bs4" multiple="multiple" name="member_types[]"
                                            id="member_type">
                                            @foreach ($members_types as $key => $value)
                                                <option {{ in_array($key, $place->member_types ?? []) ? 'selected' : '' }}
                                                    value="{{ $key }}">
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- Gender --}}
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="gender">Gender</label>
                                        <select class="form-control select2bs4" multiple="multiple" name="gender[]"
                                            id="gender">
                                            @foreach ($genders as $key => $value)
                                                <option {{ in_array($key, $place->gender ?? []) ? 'selected' : '' }}
                                                    value="{{ $key }}">
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- Zone Name --}}
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="zone_names">Zone Names</label>
                                        <select class="form-control select2bs4" multiple="multiple" name="zone_names[]"
                                            id="zone_names">
                                            <option value="">Select Zone Names</option>
                                            @isset($locationsList['distnctZoneName'])
                                                <option value="">All</option>
                                                @foreach ($locationsList['distnctZoneName'] as $name)
                                                    <option
                                                        {{ in_array($name->zone_name, $place->zone_names ?? []) ? 'selected' : '' }}
                                                        value="{{ $name->zone_name }}"> {{ $name->zone_name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- Min Age --}}
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="min_age">Min Age</label>
                                        <input type="text" class="form-control" name="min_age" id="min_age"
                                            value="{{ old('min_age', $place->min_age) }}">
                                    </div>
                                </div>
                            </div>
                            {{-- Max Age --}}
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="max_age">Max Age</label>
                                        <input type="text" class="form-control" name="max_age" id="max_age"
                                            value="{{ old('max_age', $place->max_age) }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button class="btn btn-purple  mr-2">{{ $place->id ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('checkInOutPlaces.index') }}" class="btn btn-secondary ">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@push('scripts')
@endpush
