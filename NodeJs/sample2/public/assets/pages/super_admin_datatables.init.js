/*
 Template Name: Lexa - Responsive Bootstrap 4 Admin Dashboard
 Author: Themesbrand
 File: Datatable js
 */

$(document).ready(function() {
    $('#datatable').DataTable();
    
    //Buttons examples
  
    $('#datatable-buttons thead tr')
    .clone(true)
    .addClass('filters')
    .appendTo('#datatable-buttons thead');
    var table1 =  $('#datatable-buttons').DataTable({});
    var rowCount = table1.columns().header().length; 
    table1.destroy();
    var columns = [];
    for(let i=0; i<rowCount-1; i++)
    {
        columns.push(i);
    }
    var table = $('#datatable-buttons').DataTable({
        orderCellsTop: true,
        lengthChange: false,
        scrollX: true,
        "oSearch": {"bSmart": false}, // disable smart search
        columnDefs: [ { orderable: false, targets: [] } ],
        //sScrollXInner: "110%",
        //buttons: ['copy', 'excel', 'pdf', 'colvis']
        
        buttons:  [{
            extend: "excel",
            exportOptions: {
                columns: columns
            }
        }],  
        fixedHeader: true,
         initComplete: function () {
            var api = this.api();
 
            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    if(title != "Action")
                    {
                    $(cell).html('<input type="text" placeholder="' + title + '" style="width:70%;" />'); }
                    $(
                        'input',
                        $('.filters th').eq($(api.column(colIdx).header()).index())
                    )
                        .off('keyup change')
                        .on('keyup change', function (e) {
                            e.stopPropagation();
                    
                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; //$(this).parents('th').find('select').val();
 
                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search( "^" + this.value, true, false, true )
                                /* .search(
                                    this.value != ''
                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                        : '',
                                    this.value != '',
                                    this.value == ''
                                ) */
                                .draw();
 
                            $(this)
                                .focus()[0]
                                .setSelectionRange(cursorPosition, cursorPosition);
                        });
                });
        }, 
    });
    
    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
} );