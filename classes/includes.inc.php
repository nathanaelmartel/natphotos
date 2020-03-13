<?
	include("./classes/temps.class.php");
	include("./classes/exif.class.php");
	include("./classes/photo.class.php");
	include("./classes/galerie.class.php");
	include("./classes/tableauhtml.class.php");
	include("./classes/lang.class.php");
?>

<SCRIPT>
	function detail()	{
		if ( document.getElementById('exif').style.display == 'block' )
		{
			document.getElementById("exif").style.display = "none";
			document.getElementById("detailsp").style.display = "inline";
			document.getElementById("detailsm").style.display = "none";
		}
		else
		{
			document.getElementById("exif").style.display = "block";
			document.getElementById("detailsp").style.display = "none";
			document.getElementById("detailsm").style.display = "inline";
		}
	}
</SCRIPT>
