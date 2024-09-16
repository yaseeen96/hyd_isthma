@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Qr Operators'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Qr Operators
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    @if (auth()->user()->id == 1 || auth()->user()->can('Create QrOperators'))
                        <a href="{{ route('qrOperators.create') }}" class="btn btn-purple float-right"><i
                                class="fas fa-plus mr-2"></i>Add
                            Qr Operator</a>
                    @endif
                </div>
            </div>
            <x-table id="qroperators-table">
                <th>SL.No</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            qrOperatorsTable = $('#qroperators-table').DataTable({
                ajax: {
                    url: "{{ route('qrOperators.index') }}",
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'name',
                    },
                    {
                        data: "phone_number",
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                ],
            });
        });
        $('table').on('click', '.user-delete', function(e) {
            $.ajax({
                url: $(this).data('href'),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function(data) {
                    qrOperatorsTable.draw();
                }
            })
        });
    </script>
@endpush
