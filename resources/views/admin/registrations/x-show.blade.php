@extends('layouts.app', ['ptype' => 'child', 'purl' => request()->route()->getName(), 'id' => $member->id ?? '', 'ptitle' => 'Registration', 'ctitle' => $member->id ? $member->member->name : ''])
@section('pagetitle', 'Dashboard')
@section('content')
    <div class="container-fluid mt-3 px-3 rounded-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header btn-purple">
                        <h3 class="widget-user-username font-weight-bold"><i
                                class="fas mr-2 mb-2 fa-user"></i>{{ $member->member->name }}</h3>
                        <a href="tel:{{ $member->member->phone }}" class="text-white">
                            <h5 class="widget-user-desc"><i class="fas mr-2 mb-2 fa-phone"></i>+91
                                {{ $member->member->phone }}
                            </h5>
                        </a>
                        <h5 class="widget-user-desc">{{ $member->member->dob }}</h5>
                        <h5 class="widget-user-desc"><i
                                class="fas mr-2 mb-2 fa-envelope-square"></i>{{ $member->member->email }}
                        </h5>
                    </div>
                    <div class="card-footer pt-4">
                        <div class="row">
                            <div class="col-sm-2 border-right">
                                <div class="description-block">
                                    <h3 class="description-header text-lg">{{ $member->member->unit_name }}</h3>
                                    <span class="description-text">UNIT NAME</span>
                                </div>
                            </div>
                            <div class="col-sm-2 border-right">
                                <div class="description-block">
                                    <h3 class="description-header text-lg">{{ $member->member->zone_name }}</h3>
                                    <span class="description-text">ZONE NAME</span>
                                </div>
                            </div>
                            <div class="col-sm-2 border-right">
                                <div class="description-block">
                                    <h3 class="description-header text-lg">{{ $member->member->division_name }}</h3>
                                    <span class="description-text">DIVISION NAME</span>
                                </div>
                            </div>
                            <div class="col-sm-2 border-right">
                                <div class="description-block">
                                    <h3 class="description-header text-lg">{{ $member->member->user_number }}</h3>
                                    <span class="description-text">USER NUMBEr</span>
                                </div>
                            </div>
                            <div class="col-sm-2 border-right">
                                <div class="description-block">
                                    <h3 class="description-header text-lg">{{ $member->member->gender }}</h3>
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
                        <div class="row border-top mt-3 pt-3">
                            <div class="col-lg-12 mb-4">
                                <h5 class="font-weight-bold"><span class="text-purple">Registration Details:</span>
                            </div>
                            <div class="col-sm-2 border-right">
                                <div class="description-block ">
                                    <div>
                                        <h3
                                            class="description-header badge text-lg {{ $member->confirm_arrival ? 'badge-success' : 'badge-danger' }}">
                                            {{ $member->confirm_arrival ? 'YES' : 'NO' }}</h3>
                                    </div>
                                    <span class="description-text">CONFIRM ARRIVAL</span>
                                </div>
                            </div>
                            <div class="col-sm-2 border-right">
                                <div class="description-block ">
                                    <div>
                                        <h3 class="description-header badge text-lg">
                                            {{ !empty($member->reason_for_not_coming) ? $member->reason_for_not_coming : '-' }}
                                        </h3>
                                    </div>
                                    <span class="description-text">Reason For Not Coming</span>
                                </div>
                            </div>
                            <div class="col-sm-2 border-right">
                                <div class="description-block ">
                                    <div>
                                        <h3 class="description-header badge text-lg">
                                            {{ !empty($member->ameer_permission_taken) ? $member->ameer_permission_taken : '-' }}
                                        </h3>
                                    </div>
                                    <span class="description-text">Ameer Permission Taken</span>
                                </div>
                            </div>
                            <div class="col-sm-2 border-right">
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
@endsection
