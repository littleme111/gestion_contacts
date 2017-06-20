<div id="myModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
<div id="repondre" class="repondre">
    <button class="close-button button alert right" aria-label="Close alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <p>Selectionnez l'utilisateur à qui transférer la demande : </p>
    <form>
	<select id="transfert">
        <?php

        foreach($data[0] as $dat){

            echo "<option value='".$dat[0]."'>".$dat[0]."</option>";
};?>
    </select>
        <?php echo '<p id="id" class="hidden">'.$data[1].'</p>'?>

    </form>

    <input type="submit" id="btnenvoi" class="button success">
</div>
</div>

<?php echo '<input id="demId" type="hidden" value="'.$data[1].'">'; ?>