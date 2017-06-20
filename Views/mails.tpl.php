<h1 class="title">Gestion des mails</h1>

<h4>Mails : </h4><br>
<div id="selectMail">
    <select>
        <option value="default">Selectionnez un Mail</option>
        <?php

        foreach($data[0] as  $mail){
            echo "<option value='".$mail->nom."'>".$mail->nom."</option>";
        }
        ?>

    </select>

</div>
<div id="email">
    <fieldset>

        <input type="hidden" id="hiddenId">

        <p>Contenu</p>
        <textarea id="mailTextH" style="height: 250px;"></textarea>
        
    </fieldset>
</div >
<input type="button" id="submit" value="Sauver" class="hollow button success">
<input type="button" id="create" value="Creer Mail" class="hollow button success" >
<input type="button" id="supprimer" value="Supprimer Mail" class="hollow button alert">

<div id="creer" style="display: none">
    <p>Nom du Mail</p>
    <form>
    <input type="text" id="new" >
    <input type="submit" id="creerNew" value="Creer" class="hollow button success">
    </form>
</div>
<div id="success" style="display: none" >
    <p>Votre Mail a bien été enregistré</p>
</div>
<div id="successDel" style="display: none" >
    <p>Votre Mail a bien été supprimé</p>
</div>
<br><br>
<div id="referents">
    <h4>Référents : </h4><br>
    <table id="tableauRefs">
        <thead>
            <tr>
                <th>Type de Contact</th>
                <th>Référent</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach($data[2] as $contacts){
                for($i = 0; $i < count($data[1]); $i++){
                   
                    if($contacts[0] == $data[1][$i][1]){
                        echo "<tr>";
                        echo "<td id='c".$i."'>".$contacts[1]."</td>";
                        echo "<td id='u".$i."'>".$data[1][$i][0]."</td>";
                        echo "</tr>";
                    }
                }
            }
            ?>

        </tbody>
    </table>





    <p>Vous pouvez sélectionner le référent pour chaque type de contact.</p>
    <h5>Contacts</h5>
    <select id="contact">
        <option>Sélectionnez un type de contact</option>
		<?php
		foreach($data[2] as $contact){
			echo "<option value=".$contact[1].">".$contact[1]."</option>";
		}
		?>
    </select>

    <h5>Référent </h5>
    <select id="referent">
        <option>Sélectionnez un référent</option>
        <?php
            foreach($data[1] as $user){
                echo "<option value=".$user[1].">".$user[0]."</option>";
            }
        ?>
    </select>
    <input type="submit" id="updateReferent" value="Sauvegarder" class="hollow button success">
</div>


<script>
    var b = <?php echo json_encode($data); ?>;
</script>
<!--<script src="js/jquery.js"></script>-->


