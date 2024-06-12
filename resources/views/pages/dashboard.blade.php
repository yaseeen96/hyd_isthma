@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Dashboard'])
@section('pagetitle', 'Dashboard')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <a class="dropdown-item text-lg mt-5 bg-success" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            {{-- <h1>Hurry!! Admin Dashboard first mile stone completed</h1> --}}
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
