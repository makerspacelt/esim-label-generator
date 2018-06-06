#!/usr/bin/env php
<?php

include "esimPrint.php";

$ep = new esimPrint();

array_shift($argv);
foreach ($argv as $file) {
	echo $ep->printPng($file);
}

