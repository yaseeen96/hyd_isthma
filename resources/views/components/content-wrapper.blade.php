 <!-- Main content -->
 <section class="content">
     <div class="container-fluid  mt-3 px-3  rounded-2 ">
         <div class="card shadow-sm">
             @isset($title)
                 <div class="card-header d-flex justify-content-start ">
                     <h3 class="card-title font-weight-bold">
                         {{ $title }}
                     </h3>
                 </div>
             @endisset
             {{ $slot }}
         </div>
     </div>
 </section>
 <!-- /.content -->

 @push('scripts')
     <script type="text/javascript">
         // fill division and unit names dynamically 
         function getLocations(actionType, dataName) {
             const val = $(`#${actionType}`).val();
             $.ajax({
                 url: dataName === "division_name" ? "{{ route('getDivisions') }}" : "{{ route('getUnits') }}",
                 type: 'GET',
                 data: {
                     [actionType]: val
                 },
                 success: function(data) {
                     let el = document.createElement('option');
                     el.value = '';
                     el.text = `-- Select ${dataName.replace('_', ' ')} --`;
                     if (data[dataName].length > 0) {
                         $(`#${dataName}`).empty().append(el);
                         data[dataName].forEach(function(item) {
                             let el = document.createElement('option');
                             el.value = item[dataName];
                             el.text = item[dataName];
                             $(`#${dataName}`).append(el);
                         });
                         if (actionType === "zone_name") {
                             $('#division_name').val('').trigger('change');
                             $('#unit_name').val('').trigger('change');
                         }
                     }
                 }
             });

         }
     </script>
 @endpush
