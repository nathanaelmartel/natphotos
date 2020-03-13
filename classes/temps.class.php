<?php
/**************************************************************
	*	Nathanaël Martel
	*	--
	*	Classe permétant la gestion du temps de création d'un page :
	*		- créer un objet temps au tout début de la création de la page
	*		- récupéré la duré en appelant la function getTemps
	*
**************************************************************/
	
	function getMicrotime()
	{ 
    	list($usec, $sec) = explode(" ",microtime()); 
    	return ((float)$usec + (float)$sec); 
    }
    	
	class temps	{
		
		var $Debut;
		
		function temps()	
		{
			$this->Debut = getmicrotime();
		}
		
		
    	
    	function getTemps($nbdecimal = 4)
    	{
	    	$fin = getmicrotime();
			$temp = $fin - $this->Debut;
			return substr($temp, 0, $nbdecimal + 2);
    	}
	}

?>
