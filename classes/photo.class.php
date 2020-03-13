<?php
/**************************************************************
	*	Nathanal Martel
	*	--
	*	Classe permtant la gestion d'une photo en fonction de son nom de fichier.
	*	Une photo appartient necessairement  une galerie.
	*
**************************************************************/

	class photo	{
		
		var $NomPhoto;	// Nom de la photo (= nom du fichier)
		var $Galerie;	// galerie d'appartenance.
		var $Domaine;	// Domaine d'appartenance.
		var $Exif;		// Donnes Exif
		
		/*
		 *	Constructeur.
		 *	Rcupre les donnes EXIF.
		 *	Cre les images "petite" et "grande" si ncessaire.
		 */
		function photo( $fichier , $repertoire, $typeDomaine = "public")	
		{
			$this->NomPhoto = $fichier;
			$this->Galerie = $repertoire;
			$this->Domaine = $typeDomaine;
			
			$this->Exif = new exif( $this->getChemin("originale") );
			
			if ( ! file_exists( $this->getChemin("grande") ) )
				$this->make("grande");
			if ( ! file_exists( $this->getChemin("petite") ) )
				$this->make("petite");
		}
				
		/*
		 *	Cre l'image "petite" ou "grande".
		 *	Celle-ci conserve le format de "originale" mais la hauteur en est fixe : 
		 *		- "petite" = 75px 	( 100x75 pour un 3/4 standard )
		 *		- "grande" = 480px 	( 640x480 pour un 3/4 standard )
		 */
		function make($taille = "petite")
		{
			set_time_limit(5);
			if ( $taille != "petite" && $taille != "grande" && $taille != "originale")
				$taille = "petite";
			if ( ! file_exists("./".$this->Domaine."/".$this->Galerie."/".$taille) )
			{
				mkdir( "./".$this->Domaine."/".$this->Galerie."/".$taille );
				chmod("./".$this->Domaine."/".$this->Galerie."/".$taille, 0777);
			}
			if ($taille == "petite")	$hauteur = 75;
			else						$hauteur = 480;
			$originale = @imagecreatefromjpeg( $this->getChemin("originale") );
			$largeur = ( imagesx($originale) * $hauteur ) / imagesy($originale);
			$copie = @imagecreatetruecolor($largeur, $hauteur);
			imagecopyresized($copie, $originale, 0, 0, 0, 0, $largeur, $hauteur, imagesx($originale), imagesy($originale));
	   		imagejpeg ($copie, $this->getChemin($taille) );
		}
		
		/*
		 *	Renvoi le chemin complet de la photo dont la taille est en paramtre.
		 *	par dfaut et si erreur : "petite"
		 */
		function getChemin($taille = "petite")
		{
			if ( $taille != "petite" && $taille != "grande" && $taille != "originale")
				$taille = "petite";
			return "./".$this->Domaine."/".$this->Galerie."/".$taille."/".$this->NomPhoto;
		}
		
		/*
		 *	Renvoi la chaine HTML de la photo dont la taille est en paramtre
		 *	par dfaut et si erreur : "petite"
		 */
		function getHTML($taille = "petite")
		{
			if ( $taille != "petite" && $taille != "grande" && $taille != "originale")
				$taille = "petite";
			list($width, $height, $type, $attr) = getimagesize( $this->getChemin($taille) );
			return "<img src='".$this->getChemin($taille)."' ".$attr." alt='".$this->NomPhoto."'>";
		}
		
		// Renvoi le nom de la photo.
		function getNom()
		{
			return $this->NomPhoto;
		}
		
		//	Renvoi l'URL permettant d'afficher la photo "grande"
		function getURL()
		{
			$URL = "photo.php?";
			$URL = $URL."photo=\"".$this->NomPhoto."\"&";
			$URL = $URL."galerie=\"".$this->Galerie."\"&";
			$URL = $URL."domaine=\"".$this->Domaine."\"";
			return $URL;
		}
		
		function getExif()
		{
			$chaine = "";
			//$chaine = $chaine.$this->Exif->getAll();
			$chaine = $chaine.$this->Exif->getDateEtHeure();
			$chaine = $chaine.$this->Exif->getModel();
			if ( $chaine == "" && $this->Exif->isEnable() )
				return "Pas de donnes Exif";
			else
				return $chaine;
		}
	}
	

?>
