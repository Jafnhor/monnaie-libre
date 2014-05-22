#!/usr/bin/python

import re

sortie = open('documentation.tex','w')
refman = open('latex/refman.tex','r')
lines = refman.readlines()

inputline = re.compile(".*input{(.*)")
chapterline = re.compile(".*chapter{(.*)")

found = False

sortie.write("\\chapter{Manuel de référence}\n\n")

for line in lines:
	result = inputline.match(line)
	if (result):
		line = "\\import{latex/}{" + result.group(1) + "\n"
		sortie.write(line)
	result = chapterline.match(line)
	if (result):
		line = "\\section{" + result.group(1) + "\n"
		sortie.write(line)

refman.close()
sortie.close()

