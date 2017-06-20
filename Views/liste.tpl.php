<html>
<head>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


</head>
<body>

<h1 class="title">Liste</h1>
  <!-- englobe toutes les div comprenant les fieldsets -->
  <div id="bloc_fieldset">
    <!-- 1° bloc -->
      <p>Chaque liste correspond a un type de formulaire.</p>
    <div class="column_field">
      <fieldset class="fieldset_col" id="field_liste">
        <legend>Choix de la liste</legend>
          <ul class="liste_theme">
            <?php
              for($i = 0; $i < count($this->data['liste']); $i++){
  	           echo "<li class='listes' value='".$this->data['liste'][$i]->id."'>".$this->data['liste'][$i]->nom_liste."</li>";
             }
            ?>
          </ul>
      </fieldset>
    </div>
    <!-- end 1° bloc -->

    <!-- 2° bloc -->
    <div class="column_field">
      <fieldset class="fieldset_col" id="field_noms">
        <legend>Noms de la liste</legend>
          <ul class="noms_liste">
            <li></li>    
          </ul>
      </fieldset>
    </div>
    <!-- end 2° bloc -->

    <!-- 3° bloc -->
    <div class="column_field">
      <fieldset class="fieldset_col" id="field_salaries">
        <legend>Salariés à rajouter</legend>
          <ul class="noms_NL">
            <li></li>
          </ul>
        </fieldset>
    </div>
    <!-- end 3° bloc -->

  </div>
  <!-- end bloc_fieldset generale -->

  <!-- bloc boutons-->
<!--  <div class="boutons">-->
<!--    <button type="submit" id="btn_create_list" class="hollow button success">Créer liste</button>-->
<!--    <button type="reset" id="btn_delete_list" class="hollow button alert">Supprimer liste</button>-->
<!--  </div>-->
  <!-- end bloc boutons -->

  <div class="btn_list"><input id="input_listName" style=display:none;/><button id="btn_newList" class="hollow button success" style=display:none;>Ajouter</button></div>

</body>
<script>
<?php echo 'var data = ' . json_encode($data) . ';';?>
</script>
</html>
