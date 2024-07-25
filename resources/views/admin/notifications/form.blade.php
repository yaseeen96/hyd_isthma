@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Notifications'])
@section('content')
    <section class="content">
        <form method="POST" action="{{ route('notifications.store') }}" class="container-fluid  mt-3 px-3  rounded-2"
            enctype="multipart/form-data">
            @csrf
            <div class="card show-sm p-4">
                <div class="row">
                    <div class="col-lg-12">
                        @php
                            $message = Session::get('success') ?? Session::get('error');
                        @endphp
                        @if ($message)
                            <div class="alert {{ Session::get('success') ? 'alert-success' : 'alert-danger' }} alert-block">
                                <button type="button" class="close text-white" data-dismiss="alert">X</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <h4 class="text-secondary font-weight-bold">1. Audience</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 class="my-3 text-md border-bottom py-1">Region</h5>
                            </div>
                        </div>
                        <div class="row ml-2">
                            <div class="col-lg-2">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" onclick="zoneController(event)"
                                        id="zone" name="region" {{ old('region') === 'zone' ? 'checked' : '' }}
                                        value="zone">
                                    <label for="zone" class="custom-control-label">Zone / State</label>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" onclick="zoneController(event)"
                                        id="division" name="region" {{ old('region') === 'division' ? 'checked' : '' }}
                                        value="division">
                                    <label for="division" class="custom-control-label">Distrcit</label>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" onclick="zoneController(event)"
                                        id="unit" name="region" {{ old('region') === 'unit' ? 'checked' : '' }}
                                        value="unit">
                                    <label for="unit" class="custom-control-label">Unit</label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                @if ($errors->has('region'))
                                    <span class="text-danger">
                                        {{ $errors->first('region') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row ml-2 mt-3">
                            <div class="col-md-6" id="zone_cntr">
                                <div class="form-group">
                                    <label>Zone</label>
                                    <select class="form-control select2bs4" name="zone_name" style="width: 100%;">
                                        @isset($locationsList['distnctZoneName'])
                                            <option value="">All</option>
                                            @foreach ($locationsList['distnctZoneName'] as $name)
                                                <option value="{{ $name->zone_name }}"> {{ $name->zone_name }}</option>
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
                            <div class="col-md-6" id="division_cntr">
                                <div class="form-group">
                                    <label>District</label>
                                    <select class="form-control select2bs4" name="division_name" style="width: 100%;">
                                        <option value=""> -- select Division Name </option>
                                        @isset($locationsList['distnctDivisionName'])
                                            <option value="">All</option>
                                            @foreach ($locationsList['distnctDivisionName'] as $name)
                                                <option value="{{ $name->division_name }}"> {{ $name->division_name }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @if ($errors->has('division_name'))
                                        <span class="text-danger">
                                            {{ $errors->first('division_name') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6" id="unit_cntr">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <select class="form-control select2bs4" name="unit_name" style="width: 100%;"
                                        placeholder="Select Unit Name">
                                        <option value=""> -- select Unit Name </option>
                                        @isset($locationsList['distnctUnitName'])
                                            <option value="">All</option>
                                            @foreach ($locationsList['distnctUnitName'] as $name)
                                                <option value="{{ $name->unit_name }}"> {{ $name->unit_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @if ($errors->has('unit_name'))
                                        <span class="text-danger">
                                            {{ $errors->first('unit_name') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 class="my-3 text-md  border-bottom py-1">Condition</h5>
                            </div>
                        </div>
                        <div class="row ml-2">
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value=""> -- Select Gender -- </option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="gender">Registration</label>
                                    <select class="form-control" id="reg_status" name="reg_status">
                                        <option value=""> -- Select Option -- </option>
                                        <option value="1">Registered</option>
                                        <option value="0">Not Registered</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm p-4">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="text-secondary font-weight-bold mb-4">2. Message</h4>
                        <div class="column ml-3">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input class="form-control" placeholder="Enter title" name="title" id="title" />
                                @if ($errors->has('title'))
                                    <span class="text-danger">
                                        {{ $errors->first('title') }}
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="title">Message</label>
                                <textarea class="form-control" rows="4" name="message" id="message" style="height: 200px"
                                    placeholder="Enter notification message..."></textarea>
                                @if ($errors->has('message'))
                                    <span class="text-danger">
                                        {{ $errors->first('message') }}
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="notification_image">Notification Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="notification_image"
                                            name="notification_image">
                                        <label class="custom-file-label" for="notification_image">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="youtube_url">Youtube URL</label>
                                <input type="text" name="youtube_url" class="form-control">
                            </div>
                            <div class="row justify-content-end">
                                <button class="btn btn-purple"><i class="fas mr-2 fa-paper-plane"></i>Send </button>
                                <a href="{{ route('notifications.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection
@push('scripts')
    <script type="text/javascript">
        const selectedRegion = document.querySelector('input[name="region"]:checked');
        let current = selectedRegion == null || selectedRegion == undefined ? '' : selectedRegion.value + "_cntr";

        const regionDivs = ['zone_cntr', 'division_cntr', 'unit_cntr'];
        hideRegioFilters()

        function zoneController(event) {
            current = event.target.value + "_cntr";
            hideRegioFilters();
            const ele = document.getElementById(event.target.value + "_cntr");
            ele.style.display = "block";
        }

        function hideRegioFilters() {
            for (let i = 0; i < regionDivs.length; i++) {
                const ele = document.getElementById(regionDivs[i]);
                if (regionDivs[i] !== current) {
                    ele.style.display = "none";
                } else {
                    ele.style.display = "block";
                }
            }
        }
    </script>
@endpush
