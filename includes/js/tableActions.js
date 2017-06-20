

//On Action select change call controller corresponding to action.
jQuery(document).ready(function(){
    $(document).delegate('.Daction select', 'change', function(){
        var a = $(this).parent().parent();
        var bool = false;
        jQuery(this).attr('id', 'selected');
        var action = jQuery(this).val();

        var id = $(this).closest('tr').find('td:eq(0)').text();
        var email = $(this).closest('tr').find('td:eq(2)').text();
        var etat = $(this).closest('tr').find('td:eq(7)').text();
        //Reset dropdown to default value.
        $('#selected').prop('selectedIndex',0);

        //If the ticket is already closed open dialog to open ticket and call selected action.
        if(etat == 'Fermé'){
            $('#demandeFermee').css('display', 'block');
            $(document).delegate("#Roui, #Rnon", 'click', function(){
                if($(this).val() == 'Roui'){
					$( "#myTable tr .id:contains("+id+")" ).closest('tbody tr').find('td:eq(7)').text('En Cours');
                    $('#demandeFermee').css('display', 'none');
					  $( "#myTable tr .id:contains("+id+")" ).closest('tr').removeClass("Ferme");
					  conditionalColor();
                    jQuery.ajax({
                        url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
                        type: "post",
                        data: {ouverture: "true", id: id}
                    })

                    if(action == "Fermer"){
                        bool = false;
                        $('#closeConf').css('display', 'block');
                        $(document).delegate('#oui, #non', 'click', function(){
                            if($(this).val() == 'oui'){
                                jQuery.ajax({
                                    url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
                                    type: "post",
                                    dataType: "html",
                                    data: {registration: "success", action: action, id: id, email: email},
                                    success:function(){
                                        $( "#myTable tr .id:contains("+id+")" ).closest('tbody tr').find('td:eq(7)').text('Fermé');
										  $( "#myTable tr .id:contains("+id+")" ).closest('tbody tr').addClass('Ferme');
                                        $('#selected').removeAttr('id');
                                    }
                                })
                                $('#closeConf').css('display', 'none');
                            }else if($(this).val() == 'non'){
                                $('#closeConf').css('display', 'none');
                            }
                        });
                    }else if(action == 'Reponse'){
                        jQuery.ajax({
                            url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
                            type: "html",
                            dataType: "html",
                            data: {registration: "success", action: action, id: id, email: email},
                            success:function (data) {

                                $(a).append(data);
                            }
                        })
                    }else if(action == "Transfert"){
                        jQuery.ajax({
                            url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
                            type: "post",
                            dataType: "html",
                            data: {registration: "success", action: action, id: id, email: email},
                            success:function (data) {

                                $(a).append(data);
                            }
                        })

                    }

                }else if($(this).val() == 'Rnon'){
                    $('#demandeFermee').css('display', 'none');
                }
            })
            //Same actions as above, without confirmation dialog.
        }else{
            if(action == "Fermer"){
                bool = false;
                $('#closeConf').css('display', 'block');
                $(document).delegate('#oui, #non', 'click', function(){
                    if($(this).val() == 'oui'){
                        jQuery.ajax({
                            url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
                            type: "post",
                            dataType: "html",
                            data: {registration: "success", action: action, id: id, email: email},
                            success:function(){

                                $( "#myTable tr .id:contains("+id+")" ).closest('tbody tr').find('td:eq(7)').text('Fermé');
								$( "#myTable tr .id:contains("+id+")" ).closest('tbody tr').addClass('Ferme');
                                $('#selected').removeAttr('id');

                            }
                        })
                        $('#closeConf').css('display', 'none');
                    }else if($(this).val() == 'non'){
                        $('#closeConf').css('display', 'none');
                    }
                });
            }else if(action == 'Reponse'){

                jQuery.ajax({
                    url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
                    type: "post",
                    dataType: "html",
                    data: {registration: "success", action: action, id: id, email: email},
                    success:function (data) {

                        // $( "#myTable tr .id:contains("+id+")" ).closest('tbody tr').find('td:eq(7)').text('Répondu');
                        // $( "#myTable tr .id:contains("+id+")" ).closest('tr').find('td:eq(7)').text('Répondu');
                        $(a).append(data);
                    }
                })
            }else if(action == "Transfert"){
                jQuery.ajax({
                    url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
                    type: "post",
                    dataType: "html",
                    data: {registration: "success", action: action, id: id, email: email},
                    success:function (data) {
                        bool = true;
                        $(a).append(data);

                    }
                })

            }

        }

        $(document).delegate('#repondre button', 'click', function(){
            $('#myModal').remove();
            $('#demId').remove();
            $('#selected').prop('selectedIndex',0);
        })




    });//On click TD



    $(document).delegate('#btnenvoi', "click", function (e) {
        e.preventDefault();
        var id = $('#demId').val();
        var salarie = $('#transfert').val();
        $( "#myTable tr .id:contains("+id+")" ).closest('tbody tr').find('td:eq(7)').text('Transfert');
        $( "#myTable tr .id:contains("+id+")" ).closest('tbody tr').find('td:eq(5)').text(salarie);
        $('#selected').removeAttr('id');
        $.ajax({
            url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
            type: "post",
            dataType: "json",
            data: {type: "transfert", action: salarie, id: id},

        })
        $("#repondre").remove();
        $('#demId').remove();


    });




    $(document).delegate('#selMail', "change", function (e) {
        var nomMail = $(this).val();
        $.each(b[0], function (index, value) {
            if (nomMail == value.nom) {
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
        $( "#myTable tr .id:contains("+id+")" ).closest('tbody tr').find('td:eq(7)').text('Répondu');

        if(contenu.length == 0){
            alert('vous devez selectionner un mail.');
            $('#selMail').prop('selectedIndex',0);
        }else{
            $('#selected').removeAttr('id');

            $.ajax({
                url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
                type: "post",
                dataType: "json",
                data: {type: "envoi_mail", mail: mail, contenu: contenu, expediteur: expediteur, destinataire: destinataire, id:id},
            })
            $('#repondre').remove();
            $('#demId').remove();
        }
    });

    $('#myTable').on('hover', function(event){

        $(this).css( 'background-color', 'red' );

    });
    $(document).delegate('#myTable tbody td:nth-child(5)', 'mouseenter', function () {

        $(this).css( 'cursor', 'pointer' );

    });
    $(document).delegate('#myTable tbody td:nth-child(5)', 'mouseleave', function () {

        $(this).css( 'cursor', 'default' );

    });
    $(document).delegate('#myTable tbody td:nth-child(5)', 'click', function(event){

        var message = ($(this).text());
        var id = $(this).siblings().filter(":first").text();
        var type = $(this).siblings().filter(':nth-child(2)').text();
        var mail = $(this).siblings().filter(':nth-child(3)').text();
        var date = '';

        for(var i = 0; i < b.length; i++){
            if(b[i].id == id){
                date = b[i].date;
            }
        }


        var url = "admin.php?page=Gestion%2Fdetail&id="+id;
        var form = $('<form action="' + url + '" method="post">' +
            '<input type="text" name="id" value="' +  id + '" />' +
            '</form>');
        $('body').append(form);

        form.submit();

    });
}); //Document ready.
conditionalColor();

$('#myTable').on( 'draw.dt', function () {
    conditionalColor();
});



function conditionalColor(){
    var date = new Date();
    var month = date.getMonth() +1;
    if(month <= 9){
        month = "0"+month;
    }
    var day = date.getDay();
    if(day <= 9){
        day = "0"+day;
    }
    var hours = date.getHours();
    if(hours <= 9){
        hours = "0" + hours;
    }
    var minutes = date.getMinutes();
    if(minutes <= 9){
        minutes = "0"+minutes;
    }
    var seconds = date.getSeconds();
    if(seconds <=9){
        seconds = "0"+seconds;
    }
    var dates = month +"-"+ day+"-"+ date.getFullYear() +" "+ hours +":"+ minutes +":"+ seconds;


    $('#myTable .Ddelais').not('.sorting, tfoot td, thead td').each(function () {

        if($(this).siblings('.Detat').text() == 'Fermé'){
            $(this).not('#selects').parent("tr").addClass('Ferme');
        }else {
            $(this).not('#selects').parent("tr").removeClass('Ferme');
            var tempTime = $(this).text();
            var jour = tempTime.charAt(0) + tempTime.charAt(1);
            var mois = tempTime.charAt(3) + tempTime.charAt(4) + tempTime.charAt(2);
            tempTime = mois + jour + tempTime.slice(5);

            var otime = new Date(tempTime);
            dates = new Date(dates);
            var a = otime - dates;

            a = Math.floor(a / (1000 * 24 * 60 * 60));

            if (a > 10) {
                $(this).parent('tr').addClass('ten');
            } else if (a > 5) {
                $(this).parent('tr').addClass('five');
            } else if (a > 1) {
                $(this).parent('tr').addClass('one');
            } else if (a > -5) {
                $(this).parent('tr').addClass('mfive');
            } else {
                $(this).parent('tr').addClass('else');
            }

        }
    });
    $('thead td, tfoot tr th').css('background-color', 'white');
    $('.sorting_asc, .sorting_desc').parent('tr').css('background-color', 'white');

}