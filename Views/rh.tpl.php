<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!--	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">-->

</head>
<body>

<h1 class="title"><?php echo $data[1]; ?> </h1>




<table id="myTable" class="hover DataTables">

	<thead>
    <tr id="selects">
        <td id="t0"></td>
        <td id="t1"></td>
        <td id="t2"></td>
        <td id="t3"></td>
        <td id="t4"></td>
        <td id="t5"></td>
        <td id="t6"></td>
        <td id="t7"></td>
        <td id="t8"></td>
    </tr>
    <tr>
        <th>Id</th>
        <th>Type de Demande</th>
        <th>Adresse Mail</th>
        <th>Type de Poste</th>
        <th>Demande</th>
        <th>Référent</th>
        <th>Actions</th>
        <th>Etat</th>
        <th>Deadline</th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>

	<tbody>

	</tbody>

	<div id="closeConf" style="display:none">
		<p>Fermer ce ticket : <br>
			<button type="button" class="alert button" id="non" value="non">Non</button>
			<button type="button"  class="success button right" id="oui" value="oui">Oui</button>
	</div>
	<div id="demandeFermee" style="display:none">
		<p>Cette demade est fermée, voulez vous la rouvrir ? </p>
		<button type="button" class="alert button" id="Rnon" value="Rnon">Non</button>
		<button type="button"  class="success button right" id="Roui" value="Roui">Oui</button>
	</div>

</table>
<script>
    var b = <?php echo json_encode( $data[0] );?>;

</script>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>



</body>
</html>
