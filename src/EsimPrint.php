<?php

namespace Makerspacelt\EsimLabelGernerator;

class EsimPrint {

	private $esim;

	public function __construct()
	{
		$this->esim = new Esim();
	}

	public function setupPrinter($w, $h)
	{
		$this->esim->density(15);
		$this->esim->speedSelect(4);
		$this->esim->topOfFormBacup(true);
		$this->esim->mediaFeedAdj(100);
		$this->esim->printDirectionTopBottom(false);
		$this->esim->options('DN');
		$this->esim->setupPrintCopy(1);
		$this->esim->setLabelWidth($w);
		$this->esim->setFormLength($h,16);
	}

	public function printGd($img)
	{
		$w=imagesx($img);
		$h=imagesy($img);

		$this->setupPrinter($w, $h);

		$this->esim->clearImageBuffer();

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
					$byte=0xff;
				}
				if ($c>128) {
					$byte |= 1<<(7-$data_bit);
				} else {
					$byte &= ~(1<<(7-$data_bit));
				}	
			}
		}

		$this->esim->drawGraphics(10,10,$w,$h,$data);
		$this->esim->printLabel();
		return $this->esim->getData();
	}

	public function printFile($file)
	{
		$mime = mime_content_type($file);
		switch ($mime) {
			case 'image/gif':
				$img = imageCreateFromGif($file);
				break;
			case 'image/jpeg':
				$img = imageCreateFromJpeg($file);
				break;
			case 'image/png':
				$img = imageCreateFromPng($file);
				break;
			default:
				$img = false;

		}
		if ($img) {
			return $this->printGd($img);
		} else {
			return;
		}
	}

	public function printPng($file)
	{
		$img=imageCreateFromPng($file);
		return $this->printGd($img);
	}

	public function printJpeg($file)
	{
		$img=imageCreateFromJpeg($file);
		return $this->printGd($img);
	}

	public function printGif($file)
	{
		$img=imageCreateFromGif($file);
		return $this->printGd($img);
	}

}


