<html>
	<body>
		<form method="post" enctype="multipart/form-data">
			<input type="file" name="img">
			<input type="text" name="copies" value="1">
			<input type="submit" value="Print">
		</form>
		<code>
<?php
if ( !empty($_FILES) && $_FILES['img']['error'] == 0 )
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
