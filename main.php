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
function printDirectionTopBottom($topBottom=true) {
	if ($topBottom) {
		send("ZT");
	} else {
		send("ZB");
	}
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


function drawBox($x1, $y1, $x2, $y2, $th=1) {
	send("X$x1,$y1,$th,$x2,$y2");
}

# ===== Main
#
function init() {
	sleep(1);
	shell_exec('stty -F /dev/ttyUSB0 9600 raw cs8 -cstopb ixon -parenb');
	printTestPage();
//*

	
	density(15);
	speedSelect(4);
	topOfFormBacup(false);
	printDirectionTopBottom(false);
	mediaFeedAdj(110);
	setLabelWidth(444);

	clearImageBuffer();
	drawBox(0,0,444,310);

	printLabel();
//*/
}


init();


