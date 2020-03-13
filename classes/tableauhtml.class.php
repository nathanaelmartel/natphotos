<?
/**************************************************************
	*	Nathanaël Martel
	*	--
	*	Classe permétant la gestion de tableau html.
	* 		- Créer en précisant le nombre d'élément à mettre dedans.
	*		- Ouvrir [ouverture()] en précisant le style à appliquer.
	*		- Remplir [remplissage()] en précisant le contenu.
	* 		- Terminer [terminerLaLigneEnCours()].
	*		- Créer une bare de navigation.
	*		- Fermer [fermeture()].
	*
**************************************************************/
	$GLOBALS["nombreMaximumDeColonnes"] = 6;
	$GLOBALS["nombreMinimumDeColonnes"] = 1;
	$GLOBALS["nombreMaximumDeLignes"] = 4;

	class tableauhtml	{
		
		var $NombreDeColonnes;	// Nombre de colonne du tableau.
		var $ColonneCourante;	// Permet de stocker l'indice de la dernière colonne remplis dans le tableau.
		var $LigneCourante;		// Permet de stocker l'indice de la dernière ligne remplis dans le tableau.
		
		// Constructeur. Définie le nombre de colonnes en fonction du nombre d'éléments à afficher.
		function tableauhtml( $nombreDElement )
		{
			$this->setNombreDeColonnes( $nombreDElement );
			$this->ColonneCourante = 0;
			$this->LigneCourante = 0;
		}
		
		/*
		 *	Définie le nombre de colonnes du tableau en fonction du nombre d'éléments que l'on veut y afficher.
		 *	Renvoi ce nombre.
		 */
		function setNombreDeColonnes( $nbElement )
		{
			if ( ($nbElement <= $GLOBALS["nombreMaximumDeColonnes"]) && ($nbElement >= $GLOBALS["nombreMinimumDeColonnes"]) )	// Une seule ligne.
			{
				$this->NombreDeColonnes = $nbElement;
			}
			else
			{
				for($i=$GLOBALS["nombreMaximumDeColonnes"];$i>$GLOBALS["nombreMinimumDeColonnes"];$i=$i-1)
					$list[$i] = $nbElement % $i;
				$nbCol = array_search( max( $list ), $list ) + 1;
				$nbLine = ceil( $nbElement/$nbCol );
				if ( ($nbLine > $nbCol) && ($nbLine <= $GLOBALS["nombreMaximumDeColonnes"]) )
					$this->NombreDeColonnes = $nbLine;
				else
					$this->NombreDeColonnes = $nbCol;
				//$this->NombreDeColonnes = $GLOBALS["nombreMaximumDeColonnes"];
			}
			return $this->NombreDeColonnes;
		}
		
		/*
		 *	Renvoi la chaine permetant la création du tableau.
		 *	Y applique un style donné en paramètre.
		 */
		function ouverture( $style = "aucun" )
		{
			$id = "";
			if ( $style != "aucun" )
				$id = "id='".$style."'";
			$nbColonnesMoinsUn = $this->NombreDeColonnes - 1;
			$chaine = "<TABLE ".$id." align='center'>\n";
			if ( $nbColonnesMoinsUn > 0 )		// une seule colonne => pas de bord arrondie.
			{
				$largeurMoyenne = ceil( 100 / $this->NombreDeColonnes );
				$chaine = $chaine."<TR>";
				$chaine = $chaine."<TD class='topleft' width='".$largeurMoyenne."%'>&nbsp;</TD>";
				for($i=1;$i<$this->NombreDeColonnes-1;$i++)
					$chaine = $chaine."<TD width='".$largeurMoyenne."%'>&nbsp;</TD>";
				$chaine = $chaine."<TD class='topright' width='".$largeurMoyenne."%'>&nbsp;</TD>";
				$chaine = $chaine."</TR>\n";
			}
			return $chaine;
		}
		
		/*
		 *	Renvoi la chaine permetant l'affichage d'une cellule avec son contenu donné en paramètre.
		 */
		function remplissage( $contenu = "&nbsp;", $colspan = 1 )
		{
			$avant = "";
			$apres = "";
			if ( $this->LigneCourante >= $GLOBALS["nombreMaximumDeLignes"] )			// tableau trop plein.
				return "";
			else
			{
				if ( $this->ColonneCourante == 0 )
					$avant = "<TR>";
				if ( $colspan == 1 )
					$avant = $avant."<TD>";
				else
					$avant = $avant."<TD colspan='".$colspan."'>";
				$apres = "</TD>";
				if ( $this->ColonneCourante == $this->NombreDeColonnes - 1 )			// dernière celluce de la ligne.
				{
					$apres = $apres."</TR>\n";
					$this->LigneCourante = $this->LigneCourante + 1;
				}
				$this->ColonneCourante = ( $this->ColonneCourante + $colspan ) % $this->NombreDeColonnes;
				return $avant.$contenu.$apres;
			}
		}
		
		/*
		 *	Renvoi la chaine permetant de finir la ligne en cours.
		 */
		function terminerLigneEnCours()
		{
			$chaine = "";
			while ( $this->ColonneCourante != 0 )
				$chaine = $chaine.$this->remplissage();
			return $chaine;
		}
		
		/*
		 *	Renvoi la chaine de navigation adequat.
		 */
		function navigation( $navFirstURL, $navLeftURL, $navBackURL, $navRightURL, $navLastURL )
		{
			$chaine = "<TR><TD colspan='".$this->NombreDeColonnes."' class='navigation' id='droite'>";
			if ( $navFirstURL != "" )
				$chaine = $chaine."<A href='".$navFirstURL."'>&lt;&lt;</A>";
			if ( $navLeftURL != "" )
				$chaine = $chaine."<A href='".$navLeftURL."'>&lt;</A>";
			if ( $navBackURL != "" )
				$chaine = $chaine."<A href='".$navBackURL."'>^</A>";
			if ( $navRightURL != "" )
				$chaine = $chaine."<A href='".$navRightURL."'>&gt;</A>";
			if ( $navLastURL != "" )
				$chaine = $chaine."<A href='".$navLastURL."'>&gt;&gt;</A>";
			$chaine = $chaine."</TD></TR>\n";
			
			return $chaine;
		}
		
		function exif( $legende, $exif )
		{
			//$chaine = "<SCRIPT>function detail(){/*if (document.getElementById(\"details\").texte == \"+\" ){document.getElementById(\"details\").texte = \"-\";*/document.getElementById(\"exif\").style.display = 'block'} }</SCRIPT>\n";
			$chaine = "";
			$chaine = $chaine."<TR><TD colspan='".$this->NombreDeColonnes."' id='gauche'>";
			$chaine = $chaine."<P class='legende'>".$legende."</P>";
			$chaine = $chaine."<A href='#' onmouseup='detail()' id='detailsp'>+</A>";
			$chaine = $chaine."<A href='#' onmouseup='detail()' id='detailsm'>-</A>";
			$chaine = $chaine."<UL id='exif'>".$exif."</UL>";
			$chaine = $chaine."</TD></TR>\n";
			
			return $chaine;
		}
		
		/*
		 *	Renvoi la chaine permettant de terminer le tableau.
		 */
		function fermeture()
		{	
			$chaine = "";
			if ( $this->ColonneCourante == 0 )	// Vérifie que la ligne en cours est fini.
				$this->terminerLigneEnCours();
			$nbColonnesMoinsUn = $this->NombreDeColonnes - 1;
			if ( $nbColonnesMoinsUn > 0 )		// une seule colonne => pas de bord arrondie.
			{
				$chaine = "<TR>";
				$chaine = $chaine."<TD class='bottomleft'>&nbsp;</TD>";
				$chaine = $chaine."<TD colspan='".$nbColonnesMoinsUn."' class='bottomright'>&nbsp;</TD>";
				$chaine = $chaine."</TR>\n";
			}
			$chaine = $chaine."</TABLE>\n\n";
			return $chaine;
		}
		
	}
	
?>
