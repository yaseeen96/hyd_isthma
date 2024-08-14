/**
 * Datatable Helper
 * -------------------
 * use this file to add all datatable global functions
*/

$("#example_wrapper > .dt-buttons").appendTo("div.panel-heading");
$.extend($.fn.dataTable.defaults, {
    "responsive": false,
    "lengthChange": false,
    "autoWidth": true,
    pageLength: 100,
    processing: true,
    serverSide: true,
    buttons: [
        {
            extend: 'pageLength'
        },
        {
            extend: 'csv',
            filename: 'Members List',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'excel',
            filename: 'Members List',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'pdf',
            filename: 'Members List',
            exportOptions: {
                columns: ':visible'
            }
        },
    'colvis',
    ],
    "lengthMenu": [100, 500, 1000, 2000, 5000, 10000, 20000],
    dom: 'Bfrtip',
});

function dtIndexCol() {
    return {
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false
    };
}

