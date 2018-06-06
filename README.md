

run like this `./print-png.php path/to/file.png > /dev/usb/lp0`

or this `./print-png.php path/to/file.png | sudo tee /dev/usb/lp0 | hd`

Tested on Intermec PF8d label printer with ESim version 7, some others might work.

Protocol documentation https://www.mediaform.de/fileadmin/support/handbuecher/etikettendrucker/intermec/Int_ESim.pdf

It allso works from openwrt with `kmod-usb-print` installed

