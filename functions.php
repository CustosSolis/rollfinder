<?php

function sheetUrl($name){
	switch ($name) {
        case "Crucible":
            return "118580353";
            break;
        case "Factions":
            return "314004979";
            break;
        case "Gunsmith":
            return "1805549613";
            break;
        case "Iron-banner":
            return "1886264796";
            break;
        case "Leviathan":
            return "548389956";
            break;
        case "Trials-nine":
            return "1719954219";
            break;
        case "Vanguard":
            return "1180559530";
            break;
        case "World":
            return "2054777441";
            break;
        case "CoO":
            return "1581709148";
            break;
        case "Warmind":
            return "1887133722";
            break;
        case "Forsaken":
            return "454784100";
            break;
        case "Outlaw":
            return "1048378745";
            break;
        case "Forge":
            return "1040045404";
            break;
        case "Drifter":
            return "1699940197";
            break;
        case "Opulence":
            return "186668623";
            break;
        case "Undying":
            return "2008495226";
            break;
        case "Dawn - Mnk ":
            return "366622822";
            break;
        case "Dawn - Controller":
            return "1051377705";
            break;
        case "Worthy - Mnk":
            return "868628670";
            break;
        case "Worthy - Controller":
            return "964017795";
            break;
        case "Arrivals - Mnk":
            return "50657812";
            break;
        case "Arrivals - Controller":
            return "997388828";
            break;
        case "Hunt - Mnk":
            return "222338306";
            break;
        case "Hunt - Controller":
            return "1725410347";
            break;
        case "Chosen - Mnk":
            return "1114929996";
            break;
        case "Chosen - Controller":
            return "622473825";
            break;
        case "Random Exotic - Mnk":
            return "1374253866";
            break;
        case "Random Exotic - Controller":
            return "963951103";
            break;
        case "Splicer - Controller":
            return "1162973001";
            break;
        case "Splicer - Mnk":
            return "1935730704";
            break;
	    default:
			return "1523804770";
			break;
    }
}

function apiRequest($args){
require("config/config.php");

$url = 'https://www.bungie.net/Platform' . $args;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));

return json_decode(curl_exec($ch),true);
curl_close;
}

function getPerks($args,$great,$good,$plugDef,$invItemDef){
	if (!array_key_exists('randomizedPlugSetHash', $args) && !array_key_exists('reusablePlugSetHash', $args)) {} else {
	
	if(isset($args["randomizedPlugSetHash"])){
		$gethash = $args["randomizedPlugSetHash"];
	} else {
		$gethash = $args["reusablePlugSetHash"];
	}
		$getdef = $plugDef[$gethash];
		$get = $getdef["reusablePlugItems"];
		$perks = array();
		foreach($get as $key => $value){
			$perks[] = $invItemDef[$value["plugItemHash"]]["displayProperties"]["name"];
			$perkflip = array_keys(array_flip($perks));
				}
				foreach($perkflip as $value){
					if (in_array(strtolower($value), array_map('strtolower', $great))) {
						echo "<b><u>" . $value . "</u>*</b><br>";
					} elseif(in_array(strtolower($value), array_map('strtolower', $good))) {
						echo "<b>" . $value . "</b><br>";
					} else {
						echo $value . "<br>";
					}
								}
					}
}

?>