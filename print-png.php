#!/usr/bin/env php
<?php

# ESim V7 Language

# Baud rate 9600
# Data bits 8
# Parity None
# Stop bits 1
# Flow control XON/XOFF

class Esim {

	# ===== Basic
	#
	static function send($msg) {
		echo "\r\n$msg\r\n";
	}

	# ===== Controll Commands
	#
	static function serialPortSetup() {
		//TODO
		self::send("Y");
	}
	static function density($d=10) {
		if ($d>15 || $d<0) {
			error_log("ERROR: invalid density [$d] should be 0..15");
		}
		self::send("D$d");
	}
	static function mediaFeed($len, $ctrl=0, $delay=0) {
		self::send("PF$len,$ctrl,$delay");
	}
	static function setFormLength($len, $gap=24) {
		self::send("Q$len,$gap");
	}
	static function speedSelect($s=2) {
		self::send("S$s");
	}
	static function mediaFeedAdj($f=136) {
		self::send("j$f");
	}
	static function setLabelWidth($w=832) {
		self::send("q$w");
	}
	static function topOfFormBacup($enable=true) {
		if ($enable) {
			self::send("JF");
		} else {
			self::send("JB");
		}
	}
	static function options($options='N') {
		self::send("O$options");
	}
	static function printDirectionTopBottom($topBottom=true) {
		if ($topBottom) {
			self::send("ZT");
		} else {
			self::send("ZB");
		}
	}

	static function resetToDefault() {
		self::send("^default");
	}
	static function resetPrinter() {
		self::send("^@");
	}
	static function printTestPage() {
		self::send("U");
	}
	static function clearImageBuffer() {
		self::send('N');
	}
	static function printLabel() {
		self::send('P1');
	}


	static function drawText($x, $y, $rotation, $size, $xmult, $ymult, $invert, $text) {
		self::send("A$x,$y,$rotation,$size,$xmult,$ymult,$invert,\"$text\"");
	}
	static function drawBarcode($x, $y, $rotation, $t1, $t2, $w, $h, $humanReadable=true, $data) {
		self::send("B$x,$y,$rotation,$t1,$t2,$w,$h,B,\"$data\"");
	}
	static function drawLineBlack($x, $y, $w=0, $h=0) {
		self::send("LO$x,$y,$w,$h");
	}
	static function drawBox($x1, $y1, $x2, $y2, $th=1) {
		self::send("X$x1,$y1,$th,$x2,$y2");
	}
	static function drawGraphics($x,$y, $w, $h, $data) {
		$w=ceil($w/8);
		self::send("GW$x,$y,$w,$h,$data");
	}

}

# ===== Main
#
function printPng($png_file) {

	$img=imageCreateFromPng($png_file);
	$w=imagesx($img);
	$h=imagesy($img);

	Esim::density(15);
	Esim::speedSelect(4);
	Esim::topOfFormBacup(true);
	Esim::mediaFeedAdj(90);
	Esim::printDirectionTopBottom(false);
	Esim::options('DN');
	Esim::setLabelWidth($w);
	Esim::setFormLength($h,16);


	Esim::clearImageBuffer();


	$data="";
	$byte=0;
	for ($y=0; $y<$h; $y++) {
		for ($x=0; $x<$w; $x++) {
			$i=imageColorAt($img, $x, $y);
			$c=imageColorsForIndex($img, $i);
			$c=($c['red']+$c['green']+$c['blue'])/3;
			$data_bit=$x%8;
			if ($data_bit == 0 && !($x==0 && $y==0) ) {
				$data.=chr($byte&0xFF);
				$byte=0x00;
			}
			if ($c>128) {
				// white
				$byte |= 1<<(7-$data_bit);
			}	
		}
	}

	Esim::drawGraphics(10,10,$w,$h-1,$data);

	Esim::printLabel();
}

foreach ($argv as $file) {
	printPng($file);
}

