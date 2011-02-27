#!/bin/sh
#
# This file is part of the html5wiki package
# 
# Update public webserver
#
# @copyright 2011 HTML5Wiki Team
# @author Michael Weibel <mweibel@hsr.ch>

if [ -z "$1" ]; then
	echo "Please supply working directory for the webserver to update"
	exit 1
fi

cd $1
git pull

exit 0
