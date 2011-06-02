#!/bin/sh
#
# This file is part of the HTML5Wiki Project.
# 
# LICENSE
#
# This source file is subject to the new BSD license that is bundled
# with this package in the file LICENSE.txt.
# It is also available through the world-wide-web at this URL:
# http://www.github.com/HTML5Wiki/HTML5Wiki/blob/master/LICENSE
# If you did not receive a copy of the license and are unable to
# obtain it through the world-wide-web, please send an email
# to mweibel@hsr.ch so we can send you a copy immediately.
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
