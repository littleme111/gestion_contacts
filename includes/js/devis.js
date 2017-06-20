

/**
 * Created by John on 09/05/2017.
 */
console.log(b);
/**
 * Data Table function : loads php data into js function, with sort and search capabilities.
 */
jQuery('#myTable').DataTable({
    order: [[0, 'desc']],
    data: b,
    dom: "lifrtp",
    language: {
        info: "<h3>Statistiques : </h3><br>Nombre de résultats: <strong>_TOTAL_</strong>",
        infoFiltered: " - sur un total de <strong>_MAX_</strong>"
    },
    columns: [
        {data: 'id', sDefaultContent: ''},
        {data: 'channel', sDefaultContent: ''},
        {data: 'from_email', sDefaultContent: ''},
        {data: 'fields.your-company', sDefaultContent: ''},
        {data: 'fields.type-projet', sDefaultContent: ''},
        {data: 'fields.description-projet', sDefaultContent: ''},
        {data: 'referent', sDefaultContent: ''},
        {data: 'actionController', sDefaultContent: ''},
        {data: 'etat', sDefaultContent: ''},
        {data: 'delais', sDefaultContent: ''},




    ], "columnDefs": [
        {"width": "20", "targets": 0},
        { className: "Did", "targets": 0 },
        {"width": "50", "targets": 1},
        { className: "Dchannel", "targets": 1 },
        {"width": "10", "targets": 2},
        { className: "Dfrom_email", "targets": 2 },
        {"width": "100", "targets": 3},
        { className: "Dyour-company", "targets": 3 },
        {"width": "100", "targets": 4},
        { className: "Dtype-projet", "targets": 4 },
        {"width": "50", "targets": 5},
        { className: "Dyour-message", "targets": 5 },
        {"width": "50", "targets": 6},
        { className: "Dreferent", "targets": 6 },
        {"width": "50", "targets": 7},
        { className: "Daction", "targets": 7 },
        {"width": "50", "targets": 8},
        { className: "Detat", "targets": 8 },
        {"width": "50", "targets": 9},
        { className: "Ddelais", "targets": 9 },
        // {"targets": 4, }
        // {"targets": 4, render: function (data, type, row) {
        //     if (typeof row.fields['your-message'] !== 'undefined') {
        //         var a = row.fields['your-message'].substr(100);
        //
        //         return row.fields['your-message'].substr(0, 100);
        //
        //     }
        // }, 'width': 500,
        //}

    ],

    initComplete: function () {
        var i = 0;
        this.api().columns([0, 1, 2, 3, 4, 5, 6, 7, 8]).every(function () {
//                this.api().columns().every(function () {
            var column = this;
            var select = $('<select><option value=""></option></select>')
                .appendTo($('#selects #t'+i+'').empty())
                .on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });
            i++;
            column.data().unique().sort().each(function (d, j) {
                select.append('<option value="' + d + '">' + d + '</option>')
            });
        })
    }
});



