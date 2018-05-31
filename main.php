#!/usr/bin/env php
<?php

# ESim V7 Language

# Baud rate 9600
# Data bits 8
# Parity None
# Stop bits 1
# Flow control XON/XOFF


# ===== Basic
function send($msg) {
	echo "\r\n$msg\r\n";
}

# ===== Controll Commands
#
function serialPortSetup() {
	send("Y");
}
function density($d=10) {
	if ($d>15 || $d<0) {
		error_log("ERROR: invalid density [$d] should be 0..15");
	}
	send("D$d");
}
function mediaFeed($len, $ctrl=0, $delay=0) {
	send("PF$len,$ctrl,$delay");
}
function setFormLength($len, $gap=24) {
	send("Q$len,$gap");
}
function speedSelect($s=2) {
	send("S$s");
}
function mediaFeedAdj($f=136) {
	send("j$f");
}
function setLabelWidth($w=832) {
	send("q$w");
}
function topOfFormBacup($enable=true) {
	if ($enable) {
		send("JF");
	} else {
		send("JB");
	}
}
function options($options='N') {
	send("O$options");
}
function printDirectionTopBottom($topBottom=true) {
	if ($topBottom) {
		send("ZT");
	} else {
		send("ZB");
	}
}

function resetToDefault() {
	send("^default");
}
function resetPrinter() {
	send("^@");
}
function printTestPage() {
	send("U");
}
function clearImageBuffer() {
	send('N');
}
function printLabel() {
	send('P1');
}


function drawText($x, $y, $rotation, $size, $xmult, $ymult, $invert, $text) {
	send("A$x,$y,$rotation,$size,$xmult,$ymult,$invert,\"$text\"");
}
function drawBarcode($x, $y, $rotation, $t1, $t2, $w, $h, $humanReadable=true, $data) {
	send("B$x,$y,$rotation,$t1,$t2,$w,$h,B,\"$data\"");
}
function drawBox($x1, $y1, $x2, $y2, $th=1) {
	send("X$x1,$y1,$th,$x2,$y2");
}

# ===== Main
#
function init() {
	sleep(1);
	shell_exec('stty -F /dev/ttyUSB0 9600 raw cs8 -cstopb ixon -parenb');
//	printTestPage();

//*

	density(15);
	speedSelect(4);
	topOfFormBacup(true);
	mediaFeedAdj(110);
	printDirectionTopBottom(false);
	setLabelWidth(450);
	setFormLength(320,16);
	options('DN');


	clearImageBuffer();
	drawBox(0,0,444,315);

	drawText(10,10,0,5,1,1,'N',strToUpper("   Angle    "));
	drawText(10,60,0,5,1,1,'N',strToUpper("  Grinder   "));
	drawBox(0,110,444,111);
	drawBarcode(10,112,0,1,2,2,50,true, '1609-DHS-4321-171');
	drawBox(0,190,444,191);

	$line=180;

	drawText(10,$line+=20,0,2,1,1,'N',"Brand: SuperCraft");
	drawText(220,$line,0,2,1,1,'N',"Model: GAAG0146-1");

	drawText(10,$line+=20,0,2,1,1,'N',"Power(W): 910");
	drawText(10,$line+=20,0,2,1,1,'N',"Shaft: M14x2");
	drawText(10,$line+=20,0,2,1,1,'N',"Disc(mm): 125");
	drawText(10,$line+=20,0,2,1,1,'N',"Color: Green");

	printLabel();

//	send("JF");

//*/
}


init();


