@extends('layouts.login')
@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <p>Are you sure you want to proceed with deleting your account? Please be aware that this action is permanent
                and cannot be undone. All of your data, including your profile information, settings, and any content
                associated with your account, will be permanently erased</p>
            <p>If you are certain that you want to delete your account, please confirm by clicking the button below.

                [Delete My Account]

                If you have any questions or need assistance, please contact our support team before proceeding.

                Thank you for using our services.</p>
            <form id="delete_account_form" action="{{ route('delete-account') }}" method="POST">
                @csrf
                <button class="btn btn-purple">Delete My Account</button>
            </form>
        </div>
    </div>
@endsection
