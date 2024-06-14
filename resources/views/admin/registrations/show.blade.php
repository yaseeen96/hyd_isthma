@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $member->id ?? '', 'ptitle' => 'Registration', 'ctitle' => $member->id ? $member->member->name : ''])
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
                                        href="#custom-tabs-one-regDtls" role="tab"
                                        aria-controls="custom-tabs-one-regDtls" aria-selected="false">Registration
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
                                                    <h3 class="description-header badge text-lg">
                                                        {{ !empty($member->ameer_permission_taken) ? $member->ameer_permission_taken : '-' }}
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
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
