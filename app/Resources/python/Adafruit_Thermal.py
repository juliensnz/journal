#*************************************************************************
# This is a Python library for the Adafruit Thermal Printer.
# Pick one up at --> http://www.adafruit.com/products/597
# These printers use TTL serial to communicate, 2 pins are required.
# IMPORTANT: On 3.3V systems (e.g. Raspberry Pi), use a 10K resistor on
# the RX pin (TX on the printer, green wire), or simply leave unconnected.
#
# Adafruit invests time and resources providing this open source code.
# Please support Adafruit and open-source hardware by purchasing products
# from Adafruit!
#
# Written by Limor Fried/Ladyada for Adafruit Industries.
# Python port by Phil Burgess for Adafruit Industries.
# MIT license, all text above must be included in any redistribution.
#*************************************************************************

# This is pretty much a 1:1 direct Python port of the Adafruit_Thermal
# library for Arduino.  All methods use the same naming conventions as the
# Arduino library, with only slight changes in parameter behavior where
# needed.  This should simplify porting existing Adafruit_Thermal-based
# printer projects to Raspberry Pi, BeagleBone, etc.  See printertest.py
# for an example.
#
# One significant change is the addition of the printImage() function,
# which ties this to the Python Imaging Library and opens the door to a
# lot of cool graphical stuff!
#
# TO DO:
# - Might use standard ConfigParser library to put thermal calibration
#   settings in a global configuration file (rather than in the library).
# - Make this use proper Python library installation procedure.
# - Trap errors properly.  Some stuff just falls through right now.
# - Add docstrings throughout!

# Python 2.X code using the library usu. needs to include the next line:
from __future__ import print_function
from serial import Serial
import time
import pprint
import Image

class Adafruit_Thermal(Serial):

	resumeTime      =  0.0
	byteTime        =  0.0
	dotPrintTime    =  0.033
	dotFeedTime     =  0.0025
	prevByte        = '\n'
	column          =  0
	maxColumn       = 32
	charHeight      = 24
	lineSpacing     =  8
	barcodeHeight   = 50
	printMode       =  0
	defaultHeatTime = 60



	# Print Image.  Requires Python Imaging Library.  This is
	# specific to the Python port and not present in the Arduino
	# library.  Image will be cropped to 384 pixels width if
	# necessary, and converted to 1-bit w/diffusion dithering.
	# For any other behavior (scale, B&W threshold, etc.), use
	# the Imaging Library to perform such operations before
	# passing the result to this function.
	def printImage(self, image, LaaT=False):

		if image.mode != '1':
			image = image.convert('1')

		width  = image.size[0]
		height = image.size[1]
		if width > 384:
			width = 384
		rowBytes = (width + 7) / 8
		bitmap   = bytearray(rowBytes * height)
		pixels   = image.load()

		for y in range(height):
			n = y * rowBytes
			x = 0
			for b in range(rowBytes):
				sum = 0
				bit = 128
				while bit > 0:
					if x >= width: break
					if pixels[x, y] == 0:
						sum |= bit
					x    += 1
					bit >>= 1
				bitmap[n + b] = sum

		pprint.pprint(bitmap)
		# self.printBitmap(width, height, bitmap, LaaT)

