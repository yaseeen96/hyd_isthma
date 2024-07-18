@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Permissions'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Permissions
        </x-slot>
        <div class="card-body">
            <x-table id="members-table">
                <th style="width: 80%" class="text-center">Role</th>
                <th class="text-center">Action</th>
                <x-slot:body>
                    @isset($roles)
                        @foreach ($roles as $role)
                            <tr class="text-center">
                                <td>{{ $role->name }}</td>
                                <td><a href="{{ route("permissions.edit", $role->id) }}"><i class="fas fa-edit text-secondary"></a></td>
                            </tr>
                        @endforeach
                    @endisset
                </x-slot>
            </x-table>
        </div>
        </x-content-wrapper>
@endsection