

In CUPS setup use __RAW__ driver group instread of _Generic/Text Only_ for a USB connected printer

run like this `./main.php > /dev/usb/lp0`

or this `./main.php | sudo tee /dev/usb/lp0 | hd`

Tested on Intermec PF8d label printer with ESim version 7, some others might work.

Protocol documentation https://www.mediaform.de/fileadmin/support/handbuecher/etikettendrucker/intermec/Int_ESim.pdf

