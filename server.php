<html>
	<body>
		<form method="post" enctype="multipart/form-data">
			<label for="img">Image:</label>
			<input type="file" name="img"><br>
			<label for="bin">Esim:</label>
			<input type="file" name="bin"><br>
			<label for="copies">Copies:</label>
			<input type="text" name="copies" value="1"><br>
			<input type="submit" value="Print">
		</form>
		<code>
<?php
if ( !empty($_FILES) && $_FILES['bin']['error'] == 0 )
{
	file_put_contents("/dev/usb/lp0", file_get_contents($_FILES['bin']['tmp_name']));
}
elseif ( !empty($_FILES) && $_FILES['img']['error'] == 0 )
{
	require_once "src/Esim.php";
	require_once "src/EsimPrint.php";
	$ep = new Makerspacelt\EsimLabelGernerator\EsimPrint();
	$ep->setCopies($_POST['copies']);
	$bin = $ep->printFile($_FILES['img']['tmp_name']);

	file_put_contents("/dev/usb/lp0", $bin);
}
?>
		</code>
	</body>
</html>
