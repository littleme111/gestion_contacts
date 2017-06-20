<div id="myModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
<div id="repondre">

    <button class="close-button button alert right" aria-label="Close alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <form id="reponse_mail" class="panel clearfix">

        <p>Selectionnez le mail Type : </p>
		<select id="selMail">
            <option value="default">Selectionnez un mail type</option>
        <?php

        foreach($data[0] as $datas){
            echo $datas->nom;
	        echo '<option value="'.$datas->nom.'">'.$datas->nom.'</option>';
        };
          ?>
		</select>


        <textarea id="contenu" ></textarea>
        <p>Selectionnez l'expéditeur.</p>
        <select id="selExpediteur">
            <?php
                foreach ($data[1] as $datas){
                    echo '<option value="'.$datas[0].'">'.$datas[0].'</option>';
                }

            ?>
        </select>
    <p><a href="<?php ABSPATH ?> admin.php?page=Gestion%2Fmails">Aller à la page de création des mail</a></p>
	</form>
    <?php echo '<input id="demId" type="hidden" value="'.$data[3].'">'; ?>
    <input type="button" value="Envoyer" id="mail_submit" class="button success right">
</div>
</div>

<script>
    var b = <?php echo json_encode( $data );?>;
</script>


