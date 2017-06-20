
<head>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
</head>
<body>

    <a href="admin.php?page=Gestion%2Ftous" class="button warning right">Retour </a>
<h1 class="title">Details</h1>
    <pre>
<div>
    <h3>Demande  détaillée: </h3>

    <div id="normal-sortables" class="meta-box-sortables"><div id="inboundfieldsdiv" class="postbox " >
            <div class="inside">
                <table class="widefat message-fields striped">
                    <tbody>
                        <?php
                            foreach($data[2]->fields as $key => $fields){
                                if(gettype($fields) == 'array'){
	                                echo "<tr><td class='field-title'>".$key."</td><td class='field-value'><p>".$fields[0]."</p></td></tr>";
                                }else{
	                                echo "<tr><td class='field-title'>".$key."</td><td class='field-value'><p>".$fields."</p></td></tr>";
                                }
                            }


                        ?>
                    </tbody>
                </table>
            </div>
        </div>



    <table id="myTable" class="hover">
            <thead>
                <tr>
                    <td >Date</td>
                    <td >Utilisateur</td>
                    <td >Action</td>
                    <td >Etat</td>
                    <td >Mail</strong></td>
                </tr>
            </thead>
        <tbody>

        </tbody>
    </table>
<?php
$mail = $data[2]->fields['your-email'];

if(isset($data[3][0][0][0])){
	if($data[2]->channel == "rejoindre-la-team" && is_file(ABSPATH . 'wp-content/uploads/ContactFormUploads/rh/'.$mail.'/0/'.$data[3][0][0][0])){
		echo "<h5>Fichiers Mis en Ligne : </h5>";

		echo "<button class='button  success'><a href= '/wp-content/uploads/ContactFormUploads/rh/".$mail.'/0/'.$data[3][0][0][0]."' target='new'>CV</a></button>";

		echo "<button class='button  success'><a href='/wp-content/uploads/ContactFormUploads/rh/".$mail.'/1/'.$data[3][1][0][0]."' target='new'>LM</a></button>";
	}
	if($data[2]->channel == "lancez-votre-projet" && is_file(ABSPATH . '/wp-content/uploads/ContactFormUploads/devis/'.$mail.'/0/'.$data[3][0][0][0]) ){
		echo "<h5>Fichiers Mis en Ligne : </h5>";

		echo "<button class='button success'><a href='/wp-content/uploads/ContactFormUploads/devis/".$mail.'/0/'.$data[3][0][0][0]."' target='new'>Document</a></button>";

	}
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>


        <!---->

</body>
<script>
    var $_POST = <?php echo json_encode($_POST); ?>;
    var jss = <?php echo json_encode($data[1]); ?>;
</script>


