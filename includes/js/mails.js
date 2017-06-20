//Load mail on Select change.
jQuery(document).delegate('#selectMail select', 'change', function () {

    var idMail = jQuery(this).val();

    jQuery.ajax({
        url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
        type: "post",
        dataType: 'json',
        data: {type: "getMailContent", nom:idMail},
        success:function(data){
            console.log(data);
            jQuery('#mailTextH').val(data[0].contenu);

        }
    })


    // // console.log(idMail);
    // var a = 'undefined';
    // // console.log(a);
    // jQuery.each(b[0], function (index, value) {
    //     console.log(b[0]);
    //     // console.log('index : '+index + 'valueNom : '+value.nom);
    //     if(idMail == 'default'){
    //         jQuery('#mailTextH').html('');
    //         return;
    //     }
    //     console.log('idmail ' +idMail+' value.nom '+value.nom);
    //     if(value.nom == idMail){
    //         console.log('success');
    //         a = index;
    //         return;
    //     }
    // });
    // if(a == 'undefined'){
    //     jQuery('#headerH').html('');
    //     jQuery('#mailTextH').html('');
    // }else if(typeof(b[0][a].contenu !== 'undefined') && typeof(b[0][a].contenu !== 'null')){
    //
    //     var mailText = b[0][a].contenu;
    //     var header = b[0][a].header;
    //
    //     jQuery('#headerH').html(header);
    //     jQuery('#mailTextH').html(mailText);
    //     jQuery('#hiddenId').html(a);
    // }

});


// Update Mail on button Sauvegarder.
jQuery(document).delegate('#submit', 'click', function () {

    var mailText = jQuery('#mailTextH').val();

    var index = jQuery('#hiddenId').text();
    var nom = jQuery('#selectMail select').val();
    jQuery.ajax({
        url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
        type: "post",
        dataType: 'json',
        data: {type: "UpdateMail",  mailText: mailText,  nom:nom },
        success:function(data){
            console.log(data);
            jQuery('#success').show('fast').delay(3500).hide("fast");
            jQuery('#mailTextH').val('');
            jQuery('#selectMail select').prop('selectedIndex', 0);
        },
        error:function(data){
            console.log(data);
        }
    })
});

//Create Mail - Show Creation Block
jQuery(document).delegate('#create', 'click', function(){
    jQuery('#creer').css("display", "block");
});

//Create Mail and Show success/fail Message.
jQuery(document).delegate('#creerNew', 'click', function(event){
    //Form is used to allow enter key as submission
    event.preventDefault();
    var nomMail = jQuery('#new').val();
    console.log(nomMail);
    jQuery.ajax({
        url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
        type: "post",
        dataType: 'json',
        data: {type: "CreateMail", nom:nomMail },
        success:function(data){
            if(data.result == 'success') {
                jQuery('#creer').css('display', 'none');
                jQuery('#success').show('fast').delay(3500).hide("slow");
                jQuery.ajax({
                    url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
                    type: "post",
                    dataType: 'json',
                    data: {type: "updateMailList", nom:nomMail },
                    success:function(data){
                        console.log(data);
                        var newMail = data[data.length-1];
                        jQuery('#selectMail select').append('<option value=' + newMail.nom + '>' + newMail.nom + '</option>');
                        jQuery('#selectMail select').prop('selectedIndex', 0);
                        $('#mailTextH').val('');
                    }
                })
                // jQuery('#selectMail select').append('<option value=' + nomMail + '>' + nomMail + '</option>');
            }

        },
        error:function(data){
            console.log(data);
        }
    })

});
//Delete list and values
jQuery(document).delegate('#supprimer', 'click', function (){
    var nomMail = jQuery('#selectMail select').val();
    jQuery('#headerH').val('');
    jQuery('#mailTextH').val('');
    jQuery('#signatureH').val('');
    jQuery("#selectMail select option:selected").remove();


    console.log(nomMail);
    jQuery.ajax({
        url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
        type: "post",
        dataType: 'json',
        data: {type: "DeleteMail", nom:nomMail },
        success:function(data){
            console.log(data);
            if(data.result == "success"){
                jQuery('#successDel').show("fast").delay(500).hide("slow");
            }
        },
        error:function(data){
            console.log(data);
        }
    })

})


jQuery(document).delegate('#updateReferent', 'click', function(){
    var ref = jQuery("#referent").val();
    var contact = jQuery("#contact option:selected").text();
    var refs = jQuery("#referent option:selected").text();
    jQuery.ajax({
        url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
        type: "post",
        data: {type: "updateReferent", ref: ref, contact: contact},
        success:function(){
            var tr = jQuery('#tableauRefs td:contains('+contact+')').parent('tr');


            // tr.find('td:eq(0)').fadeOut(500, function() {
            //     tr.find('td:eq(0)').text(contact).fadeIn(500);
            // });
            tr.find('td:eq(1)').fadeOut(500, function() {
                tr.find('td:eq(1)').text(refs).fadeIn(500);
            });

//                tr.find('td:eq(0)').text(contact).;
//                tr.find('td:eq(1)').text(refs);
        }
    })
});
