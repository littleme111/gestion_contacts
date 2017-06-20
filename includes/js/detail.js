/**
 * Created by John on 17/05/2017.
 */
$('#myTable').DataTable({
    data: jss,
    columns: [
        {data: 'date', sDefaultContent: ''},
        {data: 'users', sDefaultContent: ''},
        {data: 'actions', sDefaultContent: ''},
        {data: 'etats', sDefaultContent: ''},
        {data: 'mails', sDefaultContent: ''},
    ],
    "columnDefs": [
        { "width": "50", "targets": 0 },
        { "width": "50", "targets": 1 },
        { "width": "10", "targets": 2 },
        { "width": "100", "targets": 3 },
        { "width": "200", "targets": 4 },

        {"targets": 4,  render: function ( data, type, row ) {
            if(typeof row['mails'] !== 'undefined'){
                return row['mails'].substr(0, 100);

            }
        }
        }
    ],
    initComplete: function () {

        this.api().columns([0, 1, 2, 3, 4]).every( function () {
//                this.api().columns().every(function () {
            var column = this;
            var select = $('<select><option value=""></option></select>')
                .appendTo($(column.footer()).empty())
                .on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });
            column.data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>' )
            } );
        })
    }
});
