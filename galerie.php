<?
	include("./classes/includes.inc.php");
	$TempsDeGeneration = new temps(); 
	$myLang = new lang("fr");
	
	// Pas de galerie définie : Renvoie à l'index.
	if ( ! array_key_exists( "galerie", $_GET ) )	
		echo "<SCRIPT>document.location = 'index.php';</SCRIPT>";
	else
		$galerie = substr( stripslashes($_GET["galerie"]), 1, -1);
	
	// Pas de domaine définie : choix par défaut "public".
	if ( ! array_key_exists( "domaine", $_GET ) )	
		$domaine = "public";
	else
		$domaine = substr( stripslashes($_GET["domaine"]), 1, -1);
	
	// Vérification que la galerie exists. Sinon Renvoie à l'index.
	if ( ! file_exists( "./".$domaine."/".$galerie ) )
		echo "<SCRIPT>document.location = 'index.php';</SCRIPT>";
	
	$galerieCourante = new galerie( $galerie, $domaine );
	
	// La première photo à afficher est-elle donné ? Si non on prend la toute première
	if ( ! array_key_exists( "n", $_GET ) )	
		$premier = 0;
	else
		$premier = $_GET["n"];
		
	// Si la première photo à afficher est plus loin que la dernière on revient suffisement en arrière pour voir la dernière série
	if ( $premier >= $galerieCourante->getNombreDePhotos() )
		$premier = $galerieCourante->getNombreDePhotos() - $GLOBALS["nombreMaximumDeColonnes"]*$GLOBALS["nombreMaximumDeLignes"];
	
	// Si la première photo est avant la première de la galerie, on prend la toute première
	if ( $premier < 0 )
		$premier = 0;
	
	$nbDImagettesAAfficher = min( $galerieCourante->getNombreDePhotos() - $premier , $GLOBALS["nombreMaximumDeColonnes"]*$GLOBALS["nombreMaximumDeLignes"] );
	
	// Navigation :
	$navigationPrecedentURL = "";
	$navigationSuivantURL = "";
	$navigationDebutURL = "";
	$navigationFinURL = "";
	$navigationRetourURL = "./";
	// Navigation "précédent" et "début" :
	if ( $premier != 0 )	
	{
		$navigationPrecedentValue = $premier - $GLOBALS["nombreMaximumDeColonnes"]*$GLOBALS["nombreMaximumDeLignes"];
		$navigationPrecedentURL = $galerieCourante->getURL()."&n=".$navigationPrecedentValue;
		$navigationDebutURL = $galerieCourante->getURL()."&n=0";
	}
	// Navigation "suivant" et "fin":
	if ( ($premier + $nbDImagettesAAfficher) < $galerieCourante->getNombreDePhotos() )	
	{
		$navigationSuivantValue = $premier + $nbDImagettesAAfficher;
		$navigationSuivantURL = $galerieCourante->getURL()."&n=".$navigationSuivantValue;
		$navigationFinValue = $galerieCourante->getNombreDePhotos() - $GLOBALS["nombreMaximumDeColonnes"]*$GLOBALS["nombreMaximumDeLignes"];
		$navigationFinURL = $galerieCourante->getURL()."&n=".$navigationFinValue;
	}
	
	
?>	
<html>
	<head>
		<link href="ressources/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
		<title>
			<? 
				echo $galerieCourante->getNom();
				echo $myLang->getString("path_separator");
				echo $myLang->getString("sys_title");
			?>
		</title>
		<link rel="stylesheet" type="text/css" href="ressources/base.css">
	</head>
	<body>
	
		<P class=bandeau>
			<A href='<? echo $galerieCourante->getURL(); ?>'><? echo $galerieCourante->getNom();?></A>
			<?echo $myLang->getString("path_separator");?>
			<A href="./"><?echo $myLang->getString("sys_title");?></A>
		</P>

<?	
	//	Affichage des imagettes
	$imagettes = new tableauhtml( $nbDImagettesAAfficher );
	echo $imagettes->ouverture("apercus");
	for($i=$premier;$i<$nbDImagettesAAfficher+$premier;$i++)
	{
		$contenu = "<A href='".$galerieCourante->getURL($i)."&n=".$premier."'>";
		$contenu = $contenu.$galerieCourante->getHTMLPhoto($i);
		$contenu = $contenu."<P class='legende'>".$galerieCourante->getNom($i)."</P>";
		$contenu = $contenu."</A>";
		echo $imagettes->remplissage( $contenu );
	}
	echo $imagettes->terminerLigneEnCours();
	echo $imagettes->navigation( $navigationDebutURL, $navigationPrecedentURL, $navigationRetourURL, $navigationSuivantURL, $navigationFinURL);
	echo $imagettes->fermeture();
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
