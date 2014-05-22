NULL=

LATEXC=pdflatex
DOC=rapport.pdf

FICHIERS= \
	contenu.tex \
	code.tex \
	../Symfony/src/Ml/TransactionBundle/Entity/Transaction.php \
	../Symfony/src/Ml/TransactionBundle/Entity/Account.php \
	../Symfony/src/Ml/TransactionBundle/Entity/Evaluation.php \
	../Symfony/src/Ml/TransactionBundle/Entity/BasicEval.php \
	../Symfony/src/Ml/UserBundle/Form/UserType.php \
	../Symfony/src/Ml/TransactionBundle/Exception/TransactionException.php \
	../Symfony/src/Ml/TransactionBundle/Exception/RefusedTransactionException.php \
	../Symfony/src/Ml/TransactionBundle/Controller/TransactionController.php \
	../Symfony/src/Ml/TransactionBundle/Resources/config/routing.yml \
	../Symfony/src/Ml/TransactionBundle/Resources/views/Transaction/index.html.twig \
	../Symfony/src/Ml/UserBundle/Resources/views/User/edit.html.twig \
	../Symfony/app/Resources/views/layout.html.twig \
	../Symfony/app/Resources/views/headerbar.html.twig \
	$(NULL)

all: $(DOC)

.PHONY: all clean

%.pdf: %.tex
	$(LATEXC) $< && $(LATEXC) $<

clean:
	rm -f *.aux *.log *.toc *.out *.pdf *.fdb_latexmk

#Les règles suivantes représentent l'arbre de dépendances du document :
#si une partie d'une partie droite d'une règle est mise à jour, la partie
#gauche devra être mise à jour aussi (@touch)

rapport.tex: $(FICHIERS)
	@touch $@

