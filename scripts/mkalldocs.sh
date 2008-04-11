#!/bin/bash

cd `dirname $0`/../
dirname="$PWD"
cd inc
phpdoc \
	--filename '*.php' \
	--ignore '*/.svn/' \
	--target "$dirname/docs/user" \
	--output HTML:frames:default \
	--parseprivate off \
	--sourcecode on \
	--defaultpackagename People \
	--title "People User Documentation"

phpdoc \
	--filename '*.php' \
	--ignore '*/.svn' \
	--target "$dirname/docs/devel" \
	--output HTML:frames:default \
	--parseprivate on \
	--sourcecode on \
	--defaultpackagename People \
	--title "People Developer Documentation"
