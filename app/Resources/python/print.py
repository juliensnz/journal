#!/usr/bin/python

from Adafruit_Thermal import *
import Image
printer = Adafruit_Thermal("/dev/ttyAMA0", 19200, timeout=5)

printer.wake()       # Call wake() before printing again, even if reset
printer.printImage(Image.open('/home/pi/journal/web/testbw.png'), True)
printer.feed(3)
printer.sleep()      # Tell printer to sleep
printer.setDefault() # Restore printer to defaults
exit()
