@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Check In Out Places'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Check In Out Places
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    @if (auth()->user()->id == 1 || auth()->user()->can('Create CheckInOutPlaces'))
                        <a href="{{ route('checkInOutPlaces.create') }}" class="btn btn-purple float-right"><i
                                class="fas fa-plus mr-2"></i>Add
                            Place</a>
                    @endif
                </div>
            </div>
            <x-table id="checkInOutPlaces-table">
                <th>SL.No</th>
                <th>Place Name</th>
                <th>Member Types</th>
                <th>Gender</th>
                <th>Min Age</th>
                <th>Max Age</th>
                <th>Zone Names</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            checkInOutPlacesTable = $('#checkInOutPlaces-table').DataTable({
                ajax: {
                    url: "{{ route('checkInOutPlaces.index') }}",
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'place_name',
                    },
                    {
                        data: "member_types",
                    },
                    {
                        data: "gender",
                    },
                    {
                        data: 'min_age',
                    },
                    {
                        data: 'max_age'
                    },
                    {
                        data: 'zone_names'
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                ],
            });
        });
        $('table').on('click', '.place-delete', function(e) {
            $.ajax({
                url: $(this).data('href'),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 200) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        })
                        checkInOutPlacesTable.draw();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Encountered an error while processing the Program.'
                        })
                    }
                }
            })
        });
    </script>
@endpush
