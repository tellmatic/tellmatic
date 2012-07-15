######!/bin/bash
clear
LANGS="de en es fr it nl pt"
#LANGS="de"
domain="tellmatic"
for lang in $LANGS; do
    echo $lang
#mkdir -p ./$lang/LC_MESSAGES

#	--no-location \
#	--join-existing \
#	--sort-by-file \
#	-o ./$lang/LC_MESSAGES/$domain.po \
    xgettext \
	-o ./$domain-$lang.po \
	--keyword=___ \
	-C \
	--from-code="UTF-8" \
	--width=1024 \
	--join-existing \
	--no-location \
	../*.php \
	../include/install/*.php \
	../include/*.php

#-i
#	../include/*.inc.php
#	--no-wrap \

##    msgfmt ./$lang/LC_MESSAGES/$domain.po --output-file=./$lang/LC_MESSAGES/$domain.mo
#    msgfmt ./$domain-$lang.po --output-file=./$lang/LC_MESSAGES/$domain.mo

done;
