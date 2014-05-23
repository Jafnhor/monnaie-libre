#!/usr/bin/python

import re

sortie = open('documentation.tex','w')
refman = open('latex/refman.tex','r')
lines = refman.readlines()

inputline = re.compile(".*input{(.*)")
indexchapterline = re.compile(".*chapter{(Index .*)")
docchapterline = re.compile(".*chapter{(Documentation .*)")

accepted_chapter = False

#sortie.write("\\chapter{Manuel de référence}\n\n")

for line in lines:
	# Ne pas accepter les chapitres d'index (doublon avec la table des matières)
	result = indexchapterline.match(line)
	if (result):
		accepted_chapter = False
	
	# Accepter les chapitres de documentation
	result = docchapterline.match(line)
	if (result):
		accepted_chapter = True
	
	# Transformer les input en import (définit le dossier pour les inclusions récursives)
	result = inputline.match(line)
	if (result):
		line = "\\import{latex/}{" + result.group(1) + "\n"
	
	if (accepted_chapter):
		sortie.write(line)

refman.close()
sortie.close()

