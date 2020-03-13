<?
	include("./classes/includes.inc.php");
	$TempsDeGeneration = new temps();
	$myLang = new lang("fr");
	
	// Pas de photo définie : Renvoie à l'index.
	if ( ! array_key_exists( "photo", $_GET ) )	
		echo "<SCRIPT>document.location = 'index.php';</SCRIPT>";
	else
		$photo = substr( stripslashes($_GET["photo"]), 1, -1);
	
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
	
	// Vérification que la photo exists. Sinon Renvoie à l'index.
	if ( ! file_exists( "./".$domaine."/".$galerie."/originale/".$photo ) )
		echo "<SCRIPT>document.location = 'index.php';</SCRIPT>";
	
	// Crétion de l'objet galerie
	$galerieCourante = new galerie( $galerie, $domaine );
	$IndexPhotoCourante = $galerieCourante->getIndexOf( $photo );
	
	// La première photo à afficher est-elle donné ? Si non on prend la toute première.
	// Ceci est utile pour retourner à la galerie
	if ( ! array_key_exists( "n", $_GET ) )	
		$premier = 0;
	else
		$premier = $_GET["n"];
		
	// Navigation :
	$navigationPrecedentURL = "";
	$navigationSuivantURL = "";
	$navigationDebutURL = "";
	$navigationFinURL = "";
	$navigationRetourURL = $galerieCourante->getURL()."&n=".$premier;
	if ( $IndexPhotoCourante != 0 )	// Navigation "précédent":
	{
		$navigationPrecedentURL = $galerieCourante->getURL($IndexPhotoCourante-1)."&n=".($premier-1);
		$navigationDebutURL = $galerieCourante->getURL(0);
	}
	if ( $IndexPhotoCourante != $galerieCourante->getNombreDePhotos()-1 )	// Navigation "suivant":
	{
		$navigationSuivantURL = $galerieCourante->getURL($IndexPhotoCourante+1)."&n=".($premier+1);
		$navigationFinURL = $galerieCourante->getURL($galerieCourante->getNombreDePhotos()-1)."&n=".($galerieCourante->getNombreDePhotos() - $GLOBALS["nombreMaximumDeColonnes"]*$GLOBALS["nombreMaximumDeLignes"]);
	}
		
	
?>	
<html>
	<head>
		<link href="ressources/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<meta content="text/html; charset=utf-8" http-equiv="content-type">
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
			<A href='<? echo $galerieCourante->getURL($IndexPhotoCourante); ?>'><? echo $galerieCourante->getNom($IndexPhotoCourante);?></A>
			<?echo $myLang->getString("path_separator");?>
			<A href='<? echo $galerieCourante->getURL(); ?>'><? echo $galerieCourante->getNom();?></A>
			<?echo $myLang->getString("path_separator");?>
			<A href="./"><?echo $myLang->getString("sys_title");?></A>
		</P>

<?	
	
	//	Affichage de la photo
	$cadre = new tableauhtml( 3 );
	echo $cadre->ouverture("photo");
	
	$contenu = $galerieCourante->getHTMLPhoto($IndexPhotoCourante, "grande");
	//$contenu = $contenu."<P class='legende'>".$galerieCourante->getNom($IndexPhotoCourante)."</P>";
	
	echo $cadre->remplissage( $contenu, 3 );
	echo $cadre->terminerLigneEnCours();
	echo $cadre->navigation( $navigationDebutURL, $navigationPrecedentURL, $navigationRetourURL, $navigationSuivantURL, $navigationFinURL);
	echo $cadre->exif( $galerieCourante->getNom($IndexPhotoCourante), $galerieCourante->getExif($IndexPhotoCourante) );
	echo $cadre->fermeture();
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
