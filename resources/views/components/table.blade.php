<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed"
                {{ $attributes }}>
                <thead>
                    <tr>
                        {{ $slot }}
                    </tr>
                </thead>
                @isset($body)
                   {{ $body }}
                @endisset
            </table>
        </div>
    </div>
</div>
