# HomeMade-Focuser

HomeMade.Focuser is a PHP+Python based focuser driver for Raspberry Pi+stepper motor focuser.

## Hardware

The hardware is based on a Raspberry pi (any version) running a webserver. The webserver rune a PHP script that handles communication and controls the stepper motor through a python script and GPIO.

![project box](http://www.raguenaud-online.org/cedric/photos/astro/focuser/IMG_20170721_163546-small.jpg)

![project box](http://www.raguenaud-online.org/cedric/photos/astro/focuser/IMG_20170720_131956-small.jpg)

![project box](http://www.raguenaud-online.org/cedric/photos/astro/focuser/IMG_20170811_170256-small.jpg)

## Software

The driver supports both absolute and relative positioning. Communication is done through the URL parameters and the reply is a standard json packet (needed for the ip-focuser INDI driver).

The last position of the focuser is persistent so no need to do anything at the start of every session. Just continue where you left off.

## Overall architecture

![project box](http://www.raguenaud-online.org/cedric/photos/astro/focuser/focuser.png)

Related projects:

[HomeMade.Focuser](https://github.com/cedric-r/HomeMade.Focuser)

[ipfocuser](https://github.com/cedric-r/ipfocuser)

[ip-focuser](https://github.com/cedric-r/ip-focuser)

[ASCOM.HomeMade.Focuser](https://github.com/cedric-r/ASCOM.HomeMade.Focuser)

