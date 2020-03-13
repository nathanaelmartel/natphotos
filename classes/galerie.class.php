<?php
/**************************************************************
	*	Nathanaël Martel
	*	--
	*	Classe permétant la gestion d'une galerie de photo en fonction d'un repertoire.
	*	Ce répertoire doit comprendre un repertoire nommé "originale" contenant les photos.
	*
	*	Pour un certain nombre de fonction, s'il elle sont appelé sans paramêtre, le résultat porte sur la galerie,
	*	Sinon sur le numéro de la photo donné en paramêtre. Si le numéro est incohérent on retombe dans le cas de la galerie.
	*
**************************************************************/

	class galerie	{
		
		var $Photos;	// Tableau contenant les photos de la galerie.
		var $galerie;	// Nom de la galerie.
		var $Domaine;	// Domaine d'appartenance (par défaut "public")
		
		/*
		 *	Constructeur. Il scan le répertoire donné est en récupère toutes les photos.
		 */
		function galerie( $repertoire, $typeDomaine = "public")	
		{
			$this->galerie = $repertoire;
			$this->Domaine = $typeDomaine;
			
			$chemin = "./".$this->Domaine."/".$this->galerie."/originale/";
			$rep = opendir( $chemin );
			rewinddir( $rep );
			while (false !== ($nomDeFichier = readdir($rep)))
			{
				if ($nomDeFichier != ".." && $nomDeFichier != ".")
				{
					$this->Photos[] = new photo( $nomDeFichier, $this->galerie, $this->Domaine );
				}
			}
		}
		
		// Retourne le nombre de photos de la galerie.
		function getNombreDePhotos()
		{
			return count( $this->Photos );
		}
		
		/*
		 *	Retourne la chaine HTML correspondant à un petite photo.
		 *	Sert pour réaliser une aperçue de la galerie.
		 *	La photo est celle précisé en argument, par défaut elle est aléatoire.
		 */
		function getHTMLPhoto( $numero = -1, $taille = "petite" )
		{
			if (( $numero > $this->getNombreDePhotos()-1 ) || ( $numero < 0 ))
				$numero = rand( 0, $this->getNombreDePhotos()-1 );
			return $this->Photos[$numero]->getHTML( $taille );
		}
		
		//	Renvoi le nom de la i-ème photo, ou de la galerie si aucune photo n'est précisé.
		function getNom( $numero = -1 )
		{
			if (( $numero > $this->getNombreDePhotos()-1 ) || ( $numero < 0 ))
				return $this->galerie;
			else
				return $this->Photos[$numero]->getNom();
		}
		
		//	Renvoi l'URL d'accé à la galerie ou à la photo spécifié en paramètre.
		function getURL( $numero = -1 )
		{
			if (( $numero > $this->getNombreDePhotos()-1 ) || ( $numero < 0 ))
				return "galerie.php?galerie=\"".$this->galerie."\"&domaine=\"".$this->Domaine."\"";
			else
				return $this->Photos[$numero]->getURL();
		}
		
		// Retourne l'index d'une photo connaissant son nom.
		function getIndexOf( $nom )
		{
			for($i=0;$i<$this->getNombreDePhotos();$i++)
			{
				if ( $this->getNom($i) == $nom )
					return $i;
			}
			return 0;
		}
		
		function getExif( $numero = -1 )
		{
			if (( $numero > $this->getNombreDePhotos()-1 ) || ( $numero < 0 ))
				return "";
			else
				return $this->Photos[$numero]->getExif();
		}
	}
	

?>
