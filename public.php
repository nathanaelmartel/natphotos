<?
	include("./classes/includes.inc.php");
	$TempsDeGeneration = new temps(); 
	$Domaine = "public";
	$myLang = new lang("fr");
?>	
<html>
	<head>
		<link href="ressources/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<meta content="text/html; charset=UTF-8" http-equiv="content-type">
		<title><? echo $myLang->getString("sys_title")?></title>
		<link rel="stylesheet" type="text/css" href="ressources/base.css">
	</head>
	<body>
	
		<P class="bandeau"><a href="./">Nat'photos</a></p>
		<p class="attention" id="attention">Attenion : De nouvelles photos ont été détéctées, veuillez patienter pendant leur installation (cration des vignettes) <a href='public.php'> actualiser </a></p>
		
<?	
	flush();
	//	Recherche des galerie et cration des objets associé.	
	$chemin = "./".$Domaine."/";
	$rep = opendir( $chemin );
	rewinddir( $rep );
	while (false !== ($nomDeFichier = readdir($rep)))
	{
		if ( ($nomDeFichier != "..") && ($nomDeFichier != ".") && (!is_dir($nomDeFichier)) )
		{
			$galeries[] = new galerie( $nomDeFichier, $Domaine );
		}
	}
	
	// Suppression du bandeau d'avertissement.
	echo "<SCRIPT>document.getElementById('attention').style.display = 'none';</SCRIPT>\n";
	
	//	Affichage du sommaire.
	$sommaire = new tableauhtml( count($galeries) );
	echo $sommaire->ouverture("galerie");
	for($i=0;$i<count($galeries);$i++)
	{
		$contenu = "<A href='".$galeries[$i]->getURL()."'>";
		$contenu = $contenu.$galeries[$i]->getHTMLPhoto();
		$contenu = $contenu."<P class='legende'>".$galeries[$i]->getNom()."</P>";
		$contenu = $contenu."<P class='legende'> (".$galeries[$i]->getNombreDePhotos()." ".$myLang->getString("photos").")</P>";
		$contenu = $contenu."</A>";
		echo $sommaire->remplissage( $contenu );
	}
	echo $sommaire->terminerLigneEnCours();
	echo $sommaire->fermeture();
?>	
	
		<P class="bandeau">
			<? 
				echo $myLang->getString("page_genered_in");
				echo $TempsDeGeneration->getTemps();
				echo $myLang->getString("secondes_short");
				echo $myLang->getString("get_firefox");
				echo $myLang->getString("copyright");
			?>
		</p>

	</body>
</html>
