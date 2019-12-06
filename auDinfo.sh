#!/bin/sh
# This script will:
# record 10 seconds of audio from the turntable's icecast listening endpoint and 
# then send the file to audD for indentification.
# Then returns the results in nice json format

# Ultimate goal is to get index page to read from this output and display what is currently playing when someone is on the page
# Any help is appreciated, you can reach me at JogiPie#8888 on discord

token="`cat /var/www/token`"
ttmp3="sudo fIcy -s .mp3 -o /var/www/html/files/tt.mp3 -M 10 -d totripto.com 8000 /turntable.mp3"
audinfo="curl https://api.audd.io -F file=@/var/www/html/files/tt.mp3 -F api_token=$token -s | json_pp"
eval $ttmp3
eval $audinfo
