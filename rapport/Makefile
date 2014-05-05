LATEXC=pdflatex
DOC=rapport.pdf

all: $(DOC)

.PHONY: all clean

%.pdf: %.tex
	$(LATEXC) $< && $(LATEXC) $<

clean:
	rm *.aux *.log *.toc *.out *.pdf *.fdb_latexmk

#Les règles suivantes représentent l'arbre de dépendances du document :
#si une partie d'une partie droite d'une règle est mise à jour, la partie
#gauche devra être mise à jour aussi (@touch)

rapport.tex: contenu.tex
	@touch $@

