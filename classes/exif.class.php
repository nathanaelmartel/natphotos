<?
/**************************************************************
	*	Nathanaël Martel
	*	--
	*	Classe permettant de récupérer les informations exif d'une photo.
	*
**************************************************************/

	class exif	{
		
		var $All;	// Talbeau contenant l'ensemble des données
		/*
		 *	Constructeur.
		 *	Récupère les données EXIF.
		 */
		function exif( $chemin )
		{
			if ( $this->isEnable() )
				$this->All = exif_read_data ( $chemin, 0, true);
			else
				$this->All["état"] = "exif disable";
		}
		
		/*
		 * Indique si l'extension exif de php est chargé.
		 */
		function isEnable()
		{
			$modules = get_loaded_extensions();
			foreach($modules as $module)
			{
				if ( $module == "exif")
					return true;
			}
			return false;
		}
		
		/*
		 *	Retourne toute les données trouvées sous forme d'une liste HTML.
		 */
		function getAll()
		{
			$liste = "<UL>\n";
			foreach($this->All as $key=>$section) 
			{
	  			foreach($section as $name=>$val) 
	  			{
	        		$liste = $liste."<LI><span>[\"".$key."\"][\"".$name."\"] : </span>".$val."</LI>\n";
		    	}
			}
			$liste = $liste."</UL>\n";
			return $liste;
		}
		
		function getDateEtHeure()
		{
			if ( $this->All["EXIF"]["DateTimeOriginal"] != "" )
				$date = $this->All["EXIF"]["DateTimeOriginal"];
			else if ( $this->All["IFD0"]["DateTime"] != "" )
				$date = $this->All["IFD0"]["DateTime"];
			else
				return "";
			
			// Formate la date en français.
			$formatedDate = formatedate( $date );
			$formatedTime = formateheure( $date );
				
			return "<LI><SPAN>Date : </SPAN>".$formatedDate."</LI><LI><SPAN>Heure : </SPAN>".$formatedTime."</LI>";
			
		}
		
		function getModel()
		{
			if ( $this->All["IFD0"]["Model"] != "" )
				return "<LI><SPAN>Mod&egrave;le : </SPAN>".$this->All["IFD0"]["Model"]."</LI>";
			else
				return "";
		}
	}
	
	function mois($num = 0)
	{
		if ($num == 0)	$num = date("n");
		$num = $num%12;
		switch	($num)
		{
			case 1:		return "janvier";
			case 2:		return "février";
			case 3:		return "mars";
			case 4:		return "avril";
			case 5:		return "mai";
			case 6:		return "juin";
			case 7:		return "juillet";
			case 8:		return "août";
			case 9:		return "septembre";
			case 10:	return "octobre";
			case 11:	return "novembre";
			case 0:		return "décembre";
			default:	return "érreur";
		}
	}

	function jours($num = 7)
	{
		if ($num == 7)	$num = date("w");
		$num = $num%7;
		switch	($num)
		{
			case 0:		return "dimanche";
			case 1:		return "lundi";
			case 2:		return "mardi";
			case 3:		return "mercredi";
			case 4:		return "jeudi";
			case 5:		return "vendredi";
			case 6:		return "samedi";
			default:	return "érreur";
		}
	}

	
	function formatedate($date = "")
	{
		if ($date != "")
			$time = mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));
		else
			$time = time();
		return jours(date("w", $time))." ".date("j", $time)." ".mois(date("n", $time))." ".date("Y", $time);
	}

	function formateheure($date = "")
	{
		if ($date != "")
			$time = mktime(substr($date, 11, 2), substr($date, 14, 2), substr($date, 17, 2), substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));
		else
			$time = time();
		return date("G", $time).":".date("i", $time);
	}
?>
