
jQuery('#myTable').DataTable({
    order: [[0, 'desc']],
    data: b,
    columns: [
        {data: 'id', sDefaultContent: ''},
        {data: 'channel', sDefaultContent: ''},
        {data: 'from_email', sDefaultContent: ''},
        {data: 'fields.your-company', sDefaultContent: ''},
        {data: 'fields.your-message', sDefaultContent: '', "width": "100"},
        {data: 'referent', sDefaultContent: ''},
        {data: 'actionController', sDefaultContent: ''},
        {data: 'etat', sDefaultContent: ''},
        {data: 'delai', sDefaultContent: ''}



    ],
    "columnDefs": [
    { "width": "50", "targets": 0 },
    { "width": "50", "targets": 1 },
    { "width": "10", "targets": 2 },
    { "width": "100", "targets": 3 },
    { "width": "200", "targets": 4 },
    { "width": "50", "targets": 5 },
    { "width": "50", "targets": 6 },
    { "width": "50", "targets": 7 },
    { "width": "50", "targets": 8 },

    {"targets": 4,  render: function ( data, type, row ) {
        if(typeof row.fields['your-message'] !== 'undefined'){
            return row.fields['your-message'].substr(0, 100);

        }
    }, 'width': 500
    }
],
    initComplete: function () {
        var i = 0;
        this.api().columns([0, 1, 2, 3, 4, 5, 6, 7, 8]).every( function () {
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
            column.data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>' )
            } );
        })
    }
});

//On Action select change call controller corresponding to action.
jQuery(document).ready(function(){
        jQuery(document).delegate('td select', "change", function () {
        var action = jQuery(this).val();
        var id = $(this).closest('tr').find('td:eq(0)').text();
        var email = $(this).closest('tr').find('td:eq(2)').text();

        jQuery.ajax({
            url: "/choosit/wp-content/plugins/gestion_contacts/ajaxHandler.php",
            type: "post",
            datatype: "html",
            data: {registration: "success", action: action, id: id, email:email},
            success:function(result){
                jQuery("html").append(result);
            }
        })
    })//On click TD
}); //Document ready.

$(document).delegate('#btnenvoi', "click", function (e) {
    e.preventDefault();
    var salarie = $('#transfert').val();
    alert(salarie);
    var id = $("#id").text();
    alert(id);
    $.ajax({
        url: "/choosit/wp-content/plugins/gestion_contacts/ajaxHandler.php",
        type: "post",
        datatype: "html",
        data: {type: "transfert", action: salarie, id: id},

    })
    $("#repondre").remove();
    location.reload();

});


$(document).delegate('#selMail', "change", function (e) {
    var nomMail = $(this).val();
    // console.log(b[2]);
    $.each( b[0], function( index, value ){
        console.log(value);

        if(nomMail == value.nom){
            $("#contenu").val(value.contenu);
        }

    });
});

$(document).delegate('#mail_submit', 'click', function(){
    var mail = $("#selMail").val();
    var contenu = $("#contenu").val();
    var expediteur = $("#selExpediteur").val();
    var destinataire = b[2];
    var id = $('#demId').val();


    $.ajax({
        url: "/choosit/wp-content/plugins/gestion_contacts/ajaxHandler.php",
        type: "post",
        datatype: "html",
        data: {type: "envoi_mail", mail: mail, contenu: contenu, expediteur: expediteur, destinataire: destinataire, id:id},
    })
    $('#reponse_mail').remove();
    location.reload();
});


//Change color dataTable

//$("#myTable tr:contains('En cours')").css("background-color", "#06750E");
//$("#myTable tr:contains('Mail Envoyé')").css("background-color", "lightsteelblue");
//$("#myTable tr:contains('Fermé')").css({"background-color": "#666666", "font-style": "italic", "text-decoration": "line-through"});
//$("#myTable #selects").css("background-color", "white");

// compare current time & deadline > change color to red if deadline less or egal than 5 days
$(document).ready(function() {
$(".Ddelais").each(function() {
    var s = new Date($(this).text());
    var d = new Date();
    var difference = Math.round((d - s) / (1000 * 60 * 60 * 24));
    $(this).css("background-color", difference >= 0 ? "grey" : difference <= -7 ? "green" : "red")
});
});