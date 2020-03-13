<?

class lang {

	var $Localization;

    function lang($localization) 
    {
    	$this->Localization = $localization;
    }
    
    function getString($string)
    {
    	switch ($this->Localization)
    	{
    		case "fr":					return $this->getStringFr($string);
    		default:					return $string;	
    	}
    }
    
    function getStringFr($string)
    {
    	switch ($string)
    	{
    		case "sys_title":			return "Nat'photos";
    		case "page_genered_in":		return "Page g&eacute;n&eacute;r&eacute; en ";
    		case "secondes_short":		return " s ";
    		case "get_firefox":			return "<a href=\"http://www.spreadfirefox.com/?q=affiliates&amp;id=0&amp;t=1\">get firefox</a>";
    		case "copyright":			return "<a href=\"http://zvezdafolk.blogspot.com\">&copy; zvezda folk</a>";
    		case "photos":				return "photos";
    		case "path_separator":		return "@";
    		default: 					return $string;
    	}
    }
}
?>