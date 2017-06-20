/*============================================================================================*/
/*============================= AFFICHER noms au clic d'une liste ============================*/
/*============================================================================================*/

$(document).delegate('.listes','click', function(){
  /*je vide les listes avant d'afficher les nouvelles*/
  $('.noms_liste').remove();
  $('.noms_NL').remove();

  $("#field_noms").html("<legend>Noms composant la liste</legend><ul class='noms_liste'><li></li> </ul>");
  $("#field_salaries").html("<legend>Liste de salariés à rajouter</legend><ul class='noms_NL'><li></li> </ul>");
  /*modif css sur liste*/
  $('.selectedList').attr( "class", "listes" );
  $(this).attr( "class", "selectedList" );

  var id_liste = $(".selectedList").val();
  /*Afficher noms compris dans la liste sélectionnée */
  jQuery.ajax({
    url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
    type: "post",
    dataType: "json",
    data: {show: "showList", id_liste: id_liste},
    success:function(data){
      nomsList(data);
    },error:function(data){
      console.log(data);
    }//end error
  }) //end ajax
}); //Document ready.

function nomsList(data){
  var list = jQuery(".noms_liste li");
  for(var i = 0; i < data.userList.length; i++){
    list.append("<li class='field_nom' value='"+ data.userList[i][1]+ "'>"+data.userList[i][0]+"</li>");
  };
  /*Afficher noms non listés dans la liste  */
  var liste = jQuery(".noms_NL li");
  for(var i = 0; i < data.userNotList.length; i++){
    liste.append("<li class='field_salaries' value='"+ data.userNotList[i].id+ "'>"+data.userNotList[i].user_nicename+"</li>");
  };
};//end function



/*============================================================================================*/
/*===============================CREATION d'une nouvelle liste ===============================*/
/*============================================================================================*/

/*au clic de créer une nouvelle liste faire apparaître input+button*/
$(document).delegate('#btn_create_list', 'click', function(){
  $('#input_listName, #btn_newList').css('display','block');
  /*modif css sur liste, dé-selectedList*/
  $('.selectedList').removeClass("selectedList").addClass('listes');
  // supprimer sans rechargement de la page : les noms de la liste et de tous les salariés non compris
  $('.noms_liste li').empty();
  $('.noms_NL li').empty();
  }); //Document ready.

/* au clic du btn ajouter : add <li> et cache input+btn*/
$(document).delegate('#btn_newList','click',function() {
  var nom_liste="";
  nom_liste = jQuery('#input_listName').val();
  $('.liste_theme').append('<li style="cursor:pointer;" class="selectedList">' + nom_liste);
  $('#input_listName, #btn_newList').css('display','none');

  jQuery.ajax({
    url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
    type: "post",
    dataType: "json",
    data: {add: "add", nom_liste: nom_liste},
    success:function(data){
        showAll(data);
    },error:function(data){
      console.log(data);
    } //End error
  }) //End Ajax

  function showAll(data){
    console.log(data.length);
    var list = jQuery(".noms_NL li");
    for(var i = 0; i < data.users.length; i++){
      list.append("<li style='cursor:pointer;' class='field_salaries' value='"+ data.users[i][0]+ "'>"+data.users[i][1]+"</li>");
    }
    $(".selectedList").val(data.id);
  };//END function

});//Document ready.



/*============================================================================================*/
/*=====================================EFFACER une liste =====================================*/
/*============================================================================================*/
$(document).delegate('#btn_delete_list', 'click', function(){
    //supprimer dans la BDD et front MAJ
  var id_liste = "";
  id_liste = $(".selectedList").val();

  jQuery.ajax({
    url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
    type: "post",
    datatype: "html",
    data: {delete: "delete", id_liste: id_liste},
  }) //END ajax

  //supprimer sans rechargement de la page : la liste, et les 2 listes de noms
  $('.selectedList').remove();
  $('.noms_liste li').remove();
  $('.noms_NL li').remove();

}); //Document ready.



/*============================================================================================*/
/*=========================MODIFICATIONS : Ajout/suppression des noms ========================*/
/*============================================================================================*/

//AJOUT d'un nom dans la liste au clic sur celui ci
$(document).delegate('.noms_NL li', 'click', function(e){
  e.stopImmediatePropagation();
  var idUser =  $(this).val();
  var user = $(this).text();
  var html = "<li class ='usersSelect' value='"+idUser+"'>"+user+"</li>";
  var list = jQuery(".noms_liste");
  list.append(html);
  $(this, 'li').remove();
  var idListe = $(".selectedList").val();

  jQuery.ajax({
    url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
    type: "post",
    data: {listeUser: "ajout", idUser:idUser, idListe:idListe },
  })//END Ajax

}); //Document ready.


//SUPPRESSION au clic d'un nom dans une liste
$(document).delegate('.noms_liste li', 'click', function(e){
  e.stopImmediatePropagation();
  var idUser =  $(this).val();
  var user = $(this).text();
  var html = "<li class ='usersSelect' style='cursor:pointer;' value='"+idUser+"'>"+user+"</li>";
  var list = jQuery(".noms_NL");
  list.append(html);
  $(this, 'li').remove();
  var idListe = $(".selectedList").val();

  jQuery.ajax({
    url: "/wp-content/plugins/gestion_contacts/ajaxHandler.php",
    type: "post",
    data: {listeUser: "suppression", idUser:idUser, idListe:idListe },
  })//END Ajax
  return;

}); //Document ready.
