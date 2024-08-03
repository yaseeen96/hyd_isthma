@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $member->id ?? '', 'ptitle' => 'Registration', 'ctitle' => $member->id ? $member->member->name : ''])
@section('pagetitle', 'Dashboard')
@section('content')
    <div class="container-fluid mt-3 px-3 rounded-2 registration-dtls">
        <div class="row">
            <div class="col-md-12">
                <div class="col-12 col-sm-12">
                    <div class="card card-purple card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">

                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-profileDtls-tab" data-toggle="pill"
                                        href="#custom-tabs-one-profileDtls" role="tab"
                                        aria-controls="custom-tabs-one-profileDtls" aria-selected="true">Profile Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-regDtls-tab" data-toggle="pill"
                                        href="#custom-tabs-one-regDtls" role="tab"
                                        aria-controls="custom-tabs-one-regDtls" aria-selected="false">Registration
                                        Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-famlyDtls-tab" data-toggle="pill"
                                        href="#custom-tabs-one-famlyDtls" role="tab"
                                        aria-controls="custom-tabs-one-famlyDtls" aria-selected="false">Family
                                        Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-purchsDtls-tab" data-toggle="pill"
                                        href="#custom-tabs-one-purchsDtls" role="tab"
                                        aria-controls="custom-tabs-one-purchsDtls" aria-selected="false">Purchase
                                        Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-moreDtls-tab" data-toggle="pill"
                                        href="#custom-tabs-one-moreDtls" role="tab"
                                        aria-controls="custom-tabs-one-moreDtls" aria-selected="false">More
                                        Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-feeDtls-tab" data-toggle="pill"
                                        href="#custom-tabs-one-feeDtls" role="tab"
                                        aria-controls="custom-tabs-one-feeDtls" aria-selected="false">
                                        Fee Details</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-profileDtls" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-profileDtls-tab">
                                    <div class="card shadow-none">
                                        <div class="row card-header p-4">
                                            <div class="col-md-3 text-center ">
                                                <img class="img-circle"
                                                    src="{{ asset('assets/img/profile-placeholder.png') }}"
                                                    alt="User Avatar" style="width: 100px; height: 100px;">
                                            </div>
                                            <div class="col-md-8 text-left border-left pl-4">
                                                <h3 class="font-weight-bold"><i
                                                        class="fas mr-2 mb-2 fa-user"></i>{{ $member->member->name }}
                                                </h3>
                                                <a href="tel:{{ $member->member->phone }}" class="text-black">
                                                    <h5 class=""><i class="fas mr-2 mb-2 fa-phone"></i>+91
                                                        {{ $member->member->phone }}
                                                    </h5>
                                                </a>
                                                <h5 class="">{{ $member->member->dob }}</h5>
                                                <h5 class=""><i
                                                        class="fas mr-2 mb-2 fa-envelope-square"></i>{{ $member->member->email }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="card-footer p-5">
                                            <div class="row">
                                                <div class="col-sm-2 border-right">
                                                    <div class="description-block">
                                                        <h3 class="description-header text-lg">
                                                            {{ $member->member->unit_name }}</h3>
                                                        <span class="description-text">UNIT NAME</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 border-right">
                                                    <div class="description-block">
                                                        <h3 class="description-header text-lg">
                                                            {{ $member->member->zone_name }}</h3>
                                                        <span class="description-text">ZONE NAME</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 border-right">
                                                    <div class="description-block">
                                                        <h3 class="description-header text-lg">
                                                            {{ $member->member->division_name }}</h3>
                                                        <span class="description-text">DIVISION NAME</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 border-right">
                                                    <div class="description-block">
                                                        <h3 class="description-header text-lg">
                                                            {{ $member->member->user_number }}</h3>
                                                        <span class="description-text">USER NUMBEr</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 border-right">
                                                    <div class="description-block">
                                                        <h3 class="description-header text-lg">
                                                            {{ $member->member->gender }}</h3>
                                                        <span class="description-text">GENDER</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 border-right">
                                                    <div class="description-block">
                                                        <h3
                                                            class="description-header text-lg {{ $member->member->status === 'Active' ? 'text-success' : 'text-danger' }}">
                                                            {{ $member->member->status }}</h3>
                                                        <span class="description-text">STATUS</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-regDtls" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-regDtls-tab">
                                    <div class="row border-top mt-3 pt-3">
                                        <div class="col-lg-12 mb-4">
                                            <h5 class=" text-md font-weight-bold"><span class="text-purple">Registered
                                                    On:</span>
                                                {{ $member->created_at->format('l jS \o\f F Y h:i:s A') }}</h5>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <h5 class="font-weight-bold"><span class="text-purple">
                                                    Details:</span>
                                        </div>
                                        <div class="col-sm-3 border-right">
                                            <div class="description-block ">
                                                <div>
                                                    <h3
                                                        class="description-header badge text-lg {{ $member->confirm_arrival ? 'badge-success' : 'badge-danger' }}">
                                                        {{ $member->confirm_arrival ? 'YES' : 'NO' }}</h3>
                                                </div>
                                                <span class="description-text">CONFIRM ARRIVAL</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 border-right">
                                            <div class="description-block ">
                                                <div>
                                                    <h3 class="description-header badge text-lg">
                                                        {{ !empty($member->reason_for_not_coming) ? $member->reason_for_not_coming : '-' }}
                                                    </h3>
                                                </div>
                                                <span class="description-text">Reason For Not Coming</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 border-right">
                                            <div class="description-block ">
                                                <div>
                                                    @php $ammer_permission_taken = !empty($member->ameer_permission_taken) ? $member->ameer_permission_taken : '-'; @endphp
                                                    <h3
                                                        class="description-header badge text-lg {{ ($member->ameer_permission_taken ? 'badge-success' : $ammer_permission_taken == 0) ? 'badge-danger' : '' }}">
                                                        {{ ($member->ameer_permission_taken ? 'YES' : $ammer_permission_taken == 0) ? 'NO' : '-' }}
                                                    </h3>
                                                </div>
                                                <span class="description-text">Ameer Permission Taken</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 border-right">
                                            <div class="description-block ">
                                                <div>
                                                    <h3 class="description-header badge text-lg">
                                                        {{ !empty($member->emergency_contact) ? '+91 ' . $member->emergency_contact : '-' }}
                                                    </h3>
                                                </div>
                                                <span class="description-text">Emergency Contact</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-famlyDtls" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-famlyDtls-tab">
                                    <div class="col-lg-12 mb-3">
                                        <h5 class="font-weight-bold"><span class="text-purple">
                                                Mehram Details:</span>
                                    </div>
                                    @if ($mehramDetails->isNotEmpty())
                                        <div class="row px-3">
                                            <div class="table-responsive">
                                                <table
                                                    class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed">
                                                    <thead>
                                                        <tr>
                                                            <th>SL.No</th>
                                                            <th>NAME</th>
                                                            <th>AGE</th>
                                                            <th>FEES</th>
                                                            <th>INTERESTED IN VOLUNTEERING</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($mehramDetails as $index => $data)
                                                            <tr>
                                                                <th scope="row">{{ $loop->iteration }}</th>
                                                                <td>{{ $data->name }}</td>
                                                                <td> {{ $data->age }}</td>
                                                                <td>{{ $data->fees }}</td>
                                                                <th><span
                                                                        class="badge {{ $data->interested_in_volunteering === 'yes' ? 'badge-success' : 'bg-danger' }}">{{ $data->interested_in_volunteering }}</span>
                                                                </th>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row border p-3">
                                            No Details Found
                                        </div>
                                    @endif
                                    <div class="col-lg-12 my-3">
                                        <h5 class="font-weight-bold"><span class="text-purple">
                                                Children Details:</span>
                                    </div>
                                    @if ($childrenDetails->isNotEmpty())
                                        <div class="row px-3">
                                            <div class="table-responsive">
                                                <table
                                                    class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed">
                                                    <thead>
                                                        <tr>
                                                            <th>SL.Nos</th>
                                                            <th>NAME</th>
                                                            <th>AGE</th>
                                                            <th>FEES</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($childrenDetails as $index => $data)
                                                            <tr>
                                                                <th>{{ $loop->iteration }}</th>
                                                                <td>{{ $data->name }}</td>
                                                                <td> {{ $data->age }}</td>
                                                                <td>{{ $data->fees }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row border p-3">
                                            No Details Found
                                        </div>
                                    @endif

                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-purchsDtls" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-purchsDtls-tab">
                                    <div class="col-lg-12 mb-4">
                                        <h5 class="font-weight-bold"><span class="text-purple">
                                                Purchase Details:</span>
                                    </div>
                                    @if ($purchaseDetails->isNotEmpty())
                                        <div class="row px-3">
                                            <table
                                                class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">SL.No</th>
                                                        <th scope="col">TYPE</th>
                                                        <th scope="col">QUANTITY</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($purchaseDetails as $index => $data)
                                                        <tr>
                                                            <th scope="row">{{ $loop->iteration }}</th>
                                                            <td>{{ $data->type }}</td>
                                                            <td> {{ $data->qty }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="row border p-3">
                                            No Details Found
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-moreDtls" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-moreDtls-tab">
                                    <div class="row">
                                        <div class="col-lg-6 mb-4  px-4 ">
                                            <h5 class="font-weight-bold"><span class="text-purple">
                                                    Arrival Details:</span>
                                            </h5>
                                            @isset($member->arrival_details)
                                                @php
                                                    $arrival_dtls = $member->arrival_details;
                                                @endphp
                                                <table
                                                    class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                Date & Time
                                                            </td>
                                                            <td>
                                                                {{ $arrival_dtls['datetime'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Mode of Transport
                                                            </td>
                                                            <td>
                                                                {{ $arrival_dtls['mode'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Bus/Flight Number
                                                            </td>
                                                            <td>
                                                                {{ $arrival_dtls['mode_identifier'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Station Start Point
                                                            </td>
                                                            <td>
                                                                {{ $arrival_dtls['start_point'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Station End Point
                                                            </td>
                                                            <td>
                                                                {{ $arrival_dtls['end_point'] }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @endisset
                                        </div>
                                        <div class="col-lg-6 mb-4  px-4 ">
                                            <h5 class="font-weight-bold"><span class="text-purple">
                                                    departure Details:</span>
                                            </h5>
                                            @isset($member->departure_details)
                                                @php
                                                    $departure_dtls = $member->departure_details;
                                                @endphp
                                                <table
                                                    class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                Date & Time
                                                            </td>
                                                            <td>
                                                                {{ $departure_dtls['datetime'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Mode of Transport
                                                            </td>
                                                            <td>
                                                                {{ $departure_dtls['mode'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Bus/Flight Number
                                                            </td>
                                                            <td>
                                                                {{ $departure_dtls['mode_identifier'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Station Start Point
                                                            </td>
                                                            <td>
                                                                {{ $departure_dtls['start_point'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Station End Point
                                                            </td>
                                                            <td>
                                                                {{ $departure_dtls['end_point'] }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @endisset
                                        </div>
                                        <div class="col-lg-6 mb-4 px-4">
                                            <h5 class="font-weight-bold"><span class="text-purple">
                                                    Special Considerations</span>
                                            </h5>
                                            @if (@isset($member->special_considerations))
                                                @php
                                                    $special_considerations = $member->special_considerations;
                                                @endphp
                                                <table
                                                    class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                Do you require a hotel?
                                                            </td>
                                                            <td>
                                                                {{ $member->hotel_required }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Special Considerations
                                                            </td>
                                                            <td>
                                                                {{ $special_considerations['food_preferences'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Do you need an attendant?
                                                            </td>
                                                            <td>
                                                                {{ $special_considerations['need_attendant'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Bed or Cot?
                                                            </td>
                                                            <td>
                                                                {{ $special_considerations['cot_or_bed'] }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                        <div class="col-lg-6 mb-4  px-4 ">
                                            <h5 class="font-weight-bold"><span class="text-purple">
                                                    Sight Seeing </span> </h5>
                                            @if ($member->sight_seeing)
                                                <table
                                                    class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                Sight Seeing Required
                                                            </td>
                                                            <td>
                                                                {{ $member->sight_seeing['required'] }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Health Concern
                                                            </td>
                                                            <td>
                                                                {{ isset($member->sight_seeing['members_count']) ?? '' }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                        <div class="col-lg-6 mb-4  px-4 ">
                                            <h5 class="font-weight-bold"><span class="text-purple">
                                                    More Details</span> </h5>
                                            <table
                                                class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            Health Concern
                                                        </td>
                                                        <td>
                                                            {{ $member->health_concern }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Management Experience
                                                        </td>
                                                        <td>
                                                            {{ $member->management_experience }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Comments
                                                        </td>
                                                        <td>
                                                            {{ $member->comments }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-feeDtls" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-feeDtls-tab">
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <h5 class="font-weight-bold"><span class="text-purple">
                                                    Fee Details</span> </h5>
                                            @php
                                                $totalFees = $member->member_fees ?? 0;
                                            @endphp
                                            <table
                                                class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed">
                                                <tbody>
                                                    <tr>
                                                        <th colspan="2">Member Fees</th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $member->member->name }}</td>
                                                        <td>{{ $member->member_fees }}</td>
                                                    </tr>
                                                    @isset($mehramDetails)
                                                        <tr>
                                                            <th colspan="2">Mehram Details</th>
                                                        </tr>
                                                        @foreach ($mehramDetails as $mehram)
                                                            @php
                                                                $totalFees += $mehram->fees ?? 0;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $mehram->name }}</td>
                                                                <td>{{ $mehram->fees }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endisset
                                                    @isset($childrenDetails)
                                                        <tr>
                                                            <th colspan="2">Children Details</th>
                                                        </tr>
                                                        @foreach ($childrenDetails as $chidlren)
                                                            @php
                                                                $totalFees += $chidlren->fees ?? 0;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $chidlren->name }}</td>
                                                                <td>{{ $chidlren->fees }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endisset
                                                    <tr>
                                                        <th>Total</th>
                                                        <th>{{ $totalFees }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Fees Paid To Ameer</th>
                                                        <th>{{ $member->fees_paid_to_ameer }}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
