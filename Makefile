#  $Id$
#######################################################################################
##  Project    : digikey-cool-stuff
##  Designer   : Dmitry Murzinov (kakstattakim@gmail.com)
##  Link       : https://github.com/iDoka/digikey-cool-stuff
##  Module     : A PHP parser/watcher/notifier of DigoKey stock status for P/N list
##  Description:
##  Revision   : $Rev
##  Version    : $GlobalRev$
##  Date       : $LastChangedDate$
##  License    : MIT
#######################################################################################

.PHONY: all

LIST="partnumber.list"

all:
	@if [ ! -d "./simplehtmldom" ]; then svn checkout --quiet https://svn.code.sf.net/p/simplehtmldom/code/trunk simplehtmldom; fi
	@if [ -f $(LIST) ]; then php digikey-stock-watcher.php; fi
