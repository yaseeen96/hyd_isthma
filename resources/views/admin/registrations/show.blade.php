@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $member->id ?? '',
'ptitle' => 'Registration', 'ctitle' => $member->id ? $member->member->name : ''])
@section('pagetitle', 'Dashboard')
@section('content')
<div class="container-fluid mt-3 px-3 rounded-2">
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
                                    href="#custom-tabs-one-regDtls" role="tab" aria-controls="custom-tabs-one-regDtls"
                                    aria-selected="false">Registration
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
                                    href="#custom-tabs-one-moreDtls" role="tab" aria-controls="custom-tabs-one-moreDtls"
                                    aria-selected="false">More
                                    Details</a>
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
                                                @php $ammer_permission_taken = !empty($member->ameer_permission_taken) ?
                                                $member->ameer_permission_taken : '-' @endphp
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
                                <div class="col-lg-12 mb-4">
                                    <h5 class="font-weight-bold"><span class="text-purple">
                                            Mehram Details:</span>
                                </div>
                                @if($mehramDetails->isNotEmpty())
                                <div class="row mt-3 pt-3" style="padding:48px;">
                                    <table class="table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Age</th>
                                                <th scope="col">Fees</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mehramDetails as $index => $data)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $data->name }}</td>
                                                <td> {{ $data->age }}</td>
                                                <td>{{ $data->fees }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="row border-top mt-3 pt-3 ">
                                    Records Not Found
                                </div>
                                @endif
                                <div class="col-lg-12 mb-4 mt-5">
                                    <h5 class="font-weight-bold"><span class="text-purple">
                                            Children Details:</span>
                                </div>
                                @if($childrenDetails->isNotEmpty())
                                <div class="row mt-3" style="padding:48px;">
                                    <table class="table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">No.</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Age</th>
                                                <th scope="col">Fees</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($childrenDetails as $index => $data)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $data->name }}</td>
                                                <td> {{ $data->age }}</td>
                                                <td>{{ $data->fees }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="row border-top mt-3 pt-3 ">
                                    Records Not Found
                                </div>
                                @endif

                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-purchsDtls" role="tabpanel"
                                aria-labelledby="custom-tabs-one-purchsDtls-tab">
                                <div class="col-lg-12 mb-4">
                                    <h5 class="font-weight-bold"><span class="text-purple">
                                            Purchase Details:</span>
                                </div>
                                @if($purchaseDetails->isNotEmpty())
                                @foreach($purchaseDetails as $index => $data)
                                <div class="row border-top mt-3 pt-3 ">
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block ">
                                            <div>
                                                <h3 class="description-header badge text-lg">
                                                    {{ $data->type }}
                                                </h3>
                                            </div>
                                            <span class="description-text">Type</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block ">
                                            <div>
                                                <h3 class="description-header badge text-lg">
                                                    {{ $data->qty }}
                                                </h3>
                                            </div>
                                            <span class="description-text">Qty</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="row border-top mt-3 pt-3 ">
                                    Records Not Found
                                </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-moreDtls" role="tabpanel"
                                aria-labelledby="custom-tabs-one-moreDtls-tab">
                                <div class="col-lg-12 mb-4">
                                    <h5 class="font-weight-bold"><span class="text-purple">
                                            More Details:</span>
                                </div>
                                @if(!empty($member))
                                <div class="row border-top mt-3 pt-3 ">
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block ">
                                            @if(!empty($member->member_fees))
                                            <div>
                                                <h3 class="description-header badge text-lg">
                                                    {{ $member->member_fees ?? 'N/A'}}
                                                </h3>
                                            </div>
                                            @else
                                            <li class="list-group-item">No Member Fees details available.</li>
                                            @endif
                                            <span class="description-text">Member Fees</span>
                                        </div>


                                    </div>
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block ">
                                            @if(!empty($member->health_concern))
                                            <div>
                                                <h3 class="description-header badge text-lg">
                                                    {{ $member->health_concern }}
                                                </h3>
                                            </div>
                                            @else
                                            <li class="list-group-item">No Health Concern details available.</li>
                                            @endif
                                            <span class="description-text">Health Concern</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block ">
                                            @if(!empty($member->management_experience))
                                            <div>
                                                <h3 class="description-header badge text-lg">
                                                    {{ $member->management_experience }}
                                                </h3>
                                            </div>
                                            @else
                                            <li class="list-group-item">No Management Experience details available.</li>
                                            @endif
                                            <span class="description-text">Management Experience</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block ">
                                            @if(!empty($member->hotel_required))
                                            <div>
                                                <h3 class="description-header badge text-lg">
                                                    {{ $member->hotel_required }}
                                                </h3>
                                            </div>
                                            @else
                                            <li class="list-group-item">No Hotel Required details available.</li>
                                            @endif
                                            <span class="description-text">Hotel Required</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row border-top mt-3 pt-3">
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block">
                                            <div class="mb-2">
                                                <ul class="list-group" style="text-align: left;padding: 20px;">
                                                    @if(!empty($member->arrival_details))
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Mode :-
                                                        </strong>
                                                        {{ $member->arrival_details['mode'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Date/Time :-
                                                        </strong>
                                                        {{ $member->arrival_details['datetime'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Start Point :-
                                                        </strong>
                                                        {{ $member->arrival_details['start_point'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>End Point :-
                                                        </strong>
                                                        {{ $member->arrival_details['end_point'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Mode
                                                            Identifier :- </strong>
                                                        {{ $member->arrival_details['mode_identifier'] ?? 'N/A' }}
                                                    </li>
                                                    @else
                                                    <li class="list-group-item">No arrival details available.</li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <span class="description-text mt-2">Arrival Details</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block">
                                            <div class="mb-2">
                                                <ul class="list-group" style="text-align: left;padding: 20px;">
                                                    @if(!empty($member->departure_details))
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Mode :-
                                                        </strong>
                                                        {{ $member->departure_details['mode'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Date/Time :-
                                                        </strong>
                                                        {{ $member->departure_details['datetime'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Start Point :-
                                                        </strong>
                                                        {{ $member->departure_details['start_point'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>End Point :-
                                                        </strong>
                                                        {{ $member->departure_details['end_point'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Mode
                                                            Identifier :- </strong>
                                                        {{ $member->departure_details['mode_identifier'] ?? 'N/A' }}
                                                    </li>
                                                    @else
                                                    <li class="list-group-item">No departure details available.</li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <span class="description-text">Departure Details</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block">
                                            <div class="mb-2">
                                                <ul class="list-group" style="text-align: left;padding: 20px;">
                                                    @if(!empty($member->special_considerations))
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Food
                                                            Preferences :- </strong>
                                                        {{ $member->special_considerations['food_preferences'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Need Attendant
                                                            :- </strong>
                                                        {{ $member->special_considerations['need_attendant'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Cot/Bed :-
                                                        </strong>
                                                        {{ $member->special_considerations['cot_or_bed'] ?? 'N/A' }}
                                                    </li>
                                                    @else
                                                    <li class="list-group-item">No special considerations available.
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <span class="description-text">Special Considerations</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 border-right">
                                        <div class="description-block">
                                            <div class="mb-2">
                                                <ul class="list-group" style="text-align: left;padding: 20px;">
                                                    @if(!empty($member->sight_seeing))
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Required :-
                                                        </strong>
                                                        {{ $member->sight_seeing['required'] ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        <i class="fas fa-star text-info mx-3"></i><strong>Members Count
                                                            :- </strong>
                                                        {{ $member->sight_seeing['members_count'] ?? 'N/A' }}
                                                    </li>
                                                    @else
                                                    <li class="list-group-item">No sight seeing details available.</li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <span class="description-text">Sight Seeing</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row border-top mt-3 pt-3">
                                    <div class="col-sm-12 border-right">
                                        <div class="description-block ">
                                            @if(!empty($member->comments))
                                            <div>
                                                <h3 class="description-header badge text-lg">
                                                    {{ $member->comments ?? 'N/A' }}
                                                </h3>
                                            </div>
                                            @else
                                            <li class="list-group-item">No Comments details available.</li>
                                            @endif
                                            <span class="description-text">Comments</span>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row border-top mt-3 pt-3">
                                    Records Not found
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection