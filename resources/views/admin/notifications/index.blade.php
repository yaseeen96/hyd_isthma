@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Notifications'])
@section('content')
    <section class="content">
        <div class="container-fluid  mt-3 px-3  rounded-2 ">
            <div class="card show-sm p-4">
                <div class="row">
                    <div class="col-lg-12">
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
                                        id="zone" name="region" value="zone">
                                    <label for="zone" class="custom-control-label">Zone / State</label>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" onclick="zoneController(event)"
                                        id="district" name="region" value="district">
                                    <label for="district" class="custom-control-label">Distrcit</label>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" onclick="zoneController(event)"
                                        id="unit" name="region" value="unit">
                                    <label for="unit" class="custom-control-label">Unit</label>
                                </div>
                            </div>
                        </div>
                        <div class="row ml-2 mt-3">
                            <div class="col-md-6" id="zone_cntr">
                                <div class="form-group">
                                    <label>Zone</label>
                                    <select class="form-control select2bs4" style="width: 100%;">
                                        @isset($locationsList['distnctZoneName'])
                                            <option value="">All</option>
                                            @foreach ($locationsList['distnctZoneName'] as $name)
                                                <option value="{{ $name->zone_name }}"> {{ $name->zone_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="district_cntr">
                                <div class="form-group">
                                    <label>District</label>
                                    <select class="form-control select2bs4" style="width: 100%;">
                                        <option value=""> -- select Division Name </option>
                                        @isset($locationsList['distnctDivisionName'])
                                            <option value="">All</option>
                                            @foreach ($locationsList['distnctDivisionName'] as $name)
                                                <option value="{{ $name->division_name }}"> {{ $name->division_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="unit_cntr">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <select class="form-control select2bs4" style="width: 100%;"
                                        placeholder="Select Unit Name">
                                        <option value=""> -- select Unit Name </option>
                                        @isset($locationsList['distnctUnitName'])
                                            <option value="">All</option>
                                            @foreach ($locationsList['distnctUnitName'] as $name)
                                                <option value="{{ $name->unit_name }}"> {{ $name->unit_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
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
                                    <select class="form-control" id="gender">
                                        <option value=""> -- Select Gender -- </option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 px-3">
                                <div class="form-group row">
                                    <label for="gender">Registration</label>
                                    <select class="form-control" id="gender">
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
                        <div class="row ml-3">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="title">Title</label>
                                    <input class="form-control" placeholder="Enter title" name="title" id="title" />
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="title">Message</label>
                                    <textarea class="form-control" rows="4" name="message" id="message" style="height: 200px"
                                        placeholder="Enter notification message..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script type="text/javascript">
        let current = '';
        const regionDivs = ['zone_cntr', 'district_cntr', 'unit_cntr'];
        hideRegioFilters()

        function zoneController(event) {
            current = event.target.value + "cntr";
            hideRegioFilters();
            const ele = document.getElementById(event.target.value + "_cntr");
            ele.style.display = "block";
        }

        function hideRegioFilters() {
            for (let i = 0; i < regionDivs.length; i++) {
                if (regionDivs[i] !== current) {
                    const ele = document.getElementById(regionDivs[i]);
                    ele.style.display = "none";
                }
            }
        }
    </script>
@endpush
