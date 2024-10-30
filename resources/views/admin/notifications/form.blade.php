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
                        <div class="row">'
                            <div class="col-lg-12">
                                <h6 class="my-3 font-weight-bold text-md border-bottom py-1">Region</h6>
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
                        <div class="row border-bottom py-1">
                            <div class="col-lg-1 flex">
                                <h6 class="font-weight-bold mt-2">Condition </h6>
                            </div>
                            <div class="col-lg-3" style="display: flex;gap:2px;align-items: center">
                                <select class="form-control select2bs4" name="filter_type" id="filter_type"
                                    style="width: 100%;">
                                    <option value=""> -- select Condition </option>
                                    <option value="gender">Gender</option>
                                    <option value="confirm_arrival">Registration Status</option>
                                    <option value="arrival_date">Arrival Date</option>
                                    <option value="departure_date">Departure Date</option>
                                    <option value="email">Email ID</option>
                                    <option value="dob">DOB</option>
                                    <option value="arrival_mode">Arrival Mode</option>
                                    <option value="departure_mode">Departure Mode</option>
                                    <option value="arrival_mode_identifier">Arrival Vehicle Number</option>
                                    <option value="departure_mode_identifier">Departure Vehicle Number</option>
                                    <option value="hotel_required">Hotel Required</option>
                                    <option value="sight_seeing_required">Sight Seeing Opted</option>
                                    <option value="need_attendant">Need Special Care</option>
                                    <option value="cot_or_bed">Sleep Mode</option>
                                    <option value="year_of_rukniyat">Year of Rukniyat</option>
                                </select>
                                <span class="ml-2 cursor-pointer" onclick="addNewCondition()"><i
                                        class="fas fa-plus text-primary bg-primary p-1 rounded-circle"></i></span>
                            </div>
                        </div>
                        <div id="conditions_rows" class="mt-2">
                            {{-- Empty DOM  --}}
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
                                <input class="form-control" placeholder="Enter title" name="title" id="title"
                                    value="{{ old('title') }}" />
                                @if ($errors->has('title'))
                                    <span class="text-danger">
                                        {{ $errors->first('title') }}
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="title">Message</label>
                                <textarea class="form-control" rows="4" name="message" id="message" style="height: 200px"
                                    placeholder="Enter notification message...">{{ old('message') }}</textarea>
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
                                    {{-- <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="notificaiton_doc">Notification Document</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="notificaiton_doc"
                                            name="notificaiton_doc">
                                        <label class="custom-file-label" for="notificaiton_doc">Choose file</label>
                                    </div>
                                    {{-- <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="youtube_url">Youtube URL</label>
                                <input type="text" name="youtube_url" class="form-control">
                            </div>
                            <div class="row justify-content-end">
                                <button type="submit" class="btn btn-purple"><i class="fas mr-2 fa-paper-plane"></i>Send
                                </button>
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
        const filteredKeys = [];

        function addNewCondition() {

            const filter_type = $('#filter_type').val();
            $('#filter_type').val('').trigger('change');
            if (!filter_type) {
                return Toast.fire({
                    icon: 'error',
                    title: "Please Select Condition Type"
                })
            }
            if (filteredKeys.includes(filter_type)) {
                return Toast.fire({
                    icon: 'error',
                    title: "Condition Already Added"
                })
            }
            filteredKeys.push(filter_type);
            let dom = `<div class="row" id="${filter_type}">`;

            dom += `<div class="col-lg-3 form-group">
                                <input type="text" disabled class="form-control" value="${getInputLabel(filter_type)}" name="${filter_type}">
                        </div>`;
            dom += `<div class="col-lg-3 form-group">
                            <select class="form-control" name="${filter_type}_condition" style="width: 100%;">
                                <option value=""> -- select Condition </option>
                                <option value="=">Equal (=)</option>
                                <option value="!=">Not Equal (!=)</option>
                                <option value=">">Greater Than (>) </option>
                                <option value="<">Less Than (<) </option>
                                <option value=">=">Greater Than or Equal (>=) </option>
                                <option value="<=">Less Than or Equal (<=)</option>
                            </select>
                        </div>`;
            dom += `<div class="col-lg-3 form-group">`;
            const conditionValue = getConditionValue(filter_type);
            if (typeof conditionValue == 'object') {
                dom += getInput('select', conditionValue, filter_type);
            } else {
                dom += getInput(conditionValue, '', filter_type);
            }
            dom += `</div>`;
            dom += `<div class="col-lg-1 form-group">
                        <button type="button" class="btn btn-danger"
                            onclick="removeCondition('${filter_type}')"><i class="fas fa-trash"></i></button>
                    </div>`;
            dom += '</div>';
            $('#conditions_rows').append(dom);
        }

        function removeCondition(type) {
            const index = filteredKeys.indexOf(type);
            if (index > -1) {
                filteredKeys.splice(index, 1);
            }
            const dom = document.getElementById(type);
            dom.remove();
        }

        function getInput(type, value, key) {
            switch (type) {
                case 'select': {
                    let options = `<option value=""> -- select value </option>`;
                    options += Object.keys(value).map((key) =>
                        `<option value="${key}">${value[key]}</option>`
                    ).join('');
                    return `<select class="form-control select2bs4" name="${key}_value" style="width: 100%;">${options}</select>`;
                }
                case 'date':
                    return `<input type="date" class="form-control" name="${key}_value" id="${key}" >`;

                default:
                    return `<input type="text" class="form-control" name="${key}_value" value="${value}">`;
            }

        }

        function getConditionValue(type) {
            if (type == 'gender') {
                return {
                    Male: 'Male',
                    Female: 'Female'
                };
            }
            if (type == 'confirm_arrival') {
                return {
                    1: 'Confirmed',
                    0: 'Not Confirmed'
                }
            }
            if (type == 'arrival_mode' || type == 'departure_mode') {
                // keep the key value pair same
                return {
                    BUS: 'BUS',
                    TRAIN: 'Train',
                    FLIGHT: 'Flight',
                    SELF: 'Own Vehicle',
                }
            }
            if (type == 'hotel_required' || type == 'sight_seeing_required' || type ==
                'need_attendant' || type == 'cot_or_bed') {
                return {
                    Yes: 'Yes',
                    No: 'No'
                }
            }

            if (type == 'dob' || type == 'arrival_date' || type == 'departure_date') {
                return 'date';
            }

            if (type == 'email', type == 'year_of_rukniyat') {
                return 'text';
            }
        }

        function getInputLabel(string) {
            console.log(string);
            return string
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }
    </script>
@endpush
