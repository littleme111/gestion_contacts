

/**
 * Created by John on 09/05/2017.
 */

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
        {data: 'id', sDefaultContent: '', 'class': 'id'},
        {data: 'channel', sDefaultContent: ''},
        {data: 'from_email', sDefaultContent: ''},
        {data: 'fields.your-company', sDefaultContent: ''},
        {data: 'fields.your-message', sDefaultContent: '', "width": "100"},
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
        {"width": "200", "targets": 4},
        { className: "Dyour-message", "targets": 4 },
        {"width": "50", "targets": 5},
        { className: "Dreferent", "targets": 5 },
        {"width": "50", "targets": 6},
        { className: "Daction", "targets": 6 },
        {"width": "50", "targets": 7},
        { className: "Detat", "targets": 7 },
        {"width": "50", "targets": 8},
        { className: "Ddelais", "targets": 8 },
        // {"targets": 4, }
        {"targets": 4, render: function (data, type, row) {
            if (typeof row.fields['your-message'] !== 'undefined') {
                var a = row.fields['your-message'].substr(100);

                return row.fields['your-message'].substr(0, 100);

            }
        }, 'width': 500,
        }

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


///////////////////// COLOR
// compare current time & deadline > change color to red if deadline less or egal than 5 days
// $(document).ready(function() {
//   $(".Ddelais").each(function() {
//     var s = new Date($(this).text());
//     var d = new Date();
//     var difference = Math.round((d - s) / (1000 * 60 * 60 * 24));
//     $(this).css("background-color", difference >= 0 ? "#cecece" : difference <= -3 ? "#ff6363" : "white")
//   });
//
// //Change color dataTable
//
// $("#myTable tr:contains('Mail Envoyé')").css("background-color", "lightsteelblue");
// $("#myTable tr:contains('Fermé')").css({"background-color": "#cecece", "font-style": "italic", "text-decoration": "line-through"});
// $("#myTable #selects").css("background-color", "white");
//
// });
//
// //au changement de page du tableau
// $(document).delegate('.paginate_button','click', function(){
//   $(".Ddelais").each(function() {
//     var s = new Date($(this).text());
//     var d = new Date();
//     var difference = Math.round((d - s) / (1000 * 60 * 60 * 24));
//     $(this).css("background-color", difference >= 0 ? "#cecece" : difference <= -3 ? "#ff6363" : "white")
//   });
//
// $("#myTable #selects").css("background-color", "white");
// $("#myTable tr:contains('Mail Envoyé')").css("background-color", "lightsteelblue");
// $("#myTable tr:contains('Fermé')").css({"background-color": "#cecece", "font-style": "italic", "text-decoration": "line-through"});
//
// });
//
