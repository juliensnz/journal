#!/usr/bin/python

from Adafruit_Thermal import *
# import Image
import sys
printer = Adafruit_Thermal()

# printer.wake()       # Call wake() before printing again, even if reset
# printer.printImage(Image.open(sys.argv[1]), True)
printer.printImage("/Users/juliensanchez/Desktop/test_python.png", True)
# printer.feed(3)
# printer.sleep()      # Tell printer to sleep
# printer.setDefault() # Restore printer to defaults
exit()
