<?php
require_once("functions.php");
require("config/config.php");
?>
<html>
<head>
<title><?=$title?></title>
<link href='https://fonts.googleapis.com/css?family=Nunito:400,300,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="styles/style.css">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
</head>
<body>
<div class="container-fluid">
<center><h1><b><?=$title?></b></h1></center>
<?php

// Rolls Json
$rolljson = file_get_contents($rollsurl);
$rolls = json_decode($rolljson,true);

?>

<!-- Dropdown menu -->
<form action="" method="post">
<p>
<select class="js-example-basic-single" name="gun" id="gun">
<option></option>
<?php
foreach ($rolls as $item) {
	// Get rid of D.F.A since it breaks the website, try to fix later
	if($item["name"] == "d.f.a."){} else {
?>
<option data-tokens="<?php echo strtolower($item["name"])?>" value="<?php echo ucwords($item["name"])?>|<?php echo ucwords($item["sheet"])?>"><?php echo ucwords($item["name"]). " ("  . ucwords($item["sheet"]) . ")"?></option>
<?php
}}
?>
</select>
</p>
<p align="center"><input type="submit" value="Pick"></p>
</form>

<?php

if(isset($_POST["gun"])){
if($_POST["gun"] !== ""){
$result = $_POST['gun'];

// Since were passing the sheeturl and name together within "value" in option we need to break those 2 apart
$result_explode = explode('|', $result);
$gun = strtolower($result_explode[0]);
$sheet = strtolower($result_explode[1]);

} else {
	$gun = "";
	$sheet = "";
	echo "<center><b>Please make a choice</b></center>";
}

foreach ($rolls as $item) {
    if ((strcasecmp($item["name"],$gun) == 0) && (strcasecmp($item["sheet"],$sheet) == 0)) {
		// Check if selected option is console or PC in source Json for right tagging.
		if ($item["mnk"] == "true" && $item["controller"] == "true" ) {$controls = "Console & PC";} else {
					if ($item["mnk"] == "true"){$controls = "PC";}
					if ($item["controller"] == "true"){$controls = "Console";}
		}
		
// Convert weaponname to itemhash, replace % with empty placeholder, otherwise search breaks, this is the case for 21% delirium
$name = str_replace("%","",ucwords($item["name"]));
$gethash =& apiRequest('/Destiny2/Armory/Search/DestinyInventoryItemDefinition/' . $name . '/');
$results = $gethash["Response"]["results"]["results"];

// Since some weapons have multiple versions, the name must be an exact match, for example drang / drang baroque
foreach($results as $key => $value){
	if(strcasecmp($value["displayProperties"]["name"],ucwords($item["name"])) == 0){
		$icon = "https://www.bungie.net" . $value["displayProperties"]["icon"];
		$manifest =& apiRequest('/Destiny2/Manifest/DestinyInventoryItemDefinition/' . $value["hash"] . '/');
	}
}

$rarity = $manifest["Response"]["inventory"]["tierTypeName"];
$flavor = $manifest["Response"]["flavorText"];
$itemdef =& apiRequest('/Destiny2/Manifest/DestinyInventoryItemDefinition/' . $value["hash"] . '/');
$weptype = $itemdef["Response"]["itemTypeDisplayName"];
$colhash = $itemdef["Response"]["collectibleHash"];
$coldef =& apiRequest('/Destiny2/Manifest/DestinyCollectibleDefinition/' . $colhash . '/');
$getelem = $itemdef["Response"]["defaultDamageType"];

// Specify element/damage names for weapons
$element = "";

switch ($getelem) {
    case 0:
	$element = "Kinetic";
	break;
    case 1:
	$element = "Kinetic";
	break;
    case 2:
	$element = "Arc";
	break;
    case 3:
	$element = "Solar";
	break;
    case 4:
	$element = "Void";
	break;
    case 6:
	$element = "Stasis";
	break;
}

// Echo the weapons info
echo "<hr style=\"height:2px;border-width:0;color:gray;background-color:gray\">";
echo "<h4><u>" . ucwords($item["name"]) . "</u></h4>";
echo "<p><img src=\"" . $icon . "\"></p><i><footer class=\"blockquote-footer\">$flavor</footer></i>";
echo "<hr style=\"height:2px;border-width:0;color:gray;background-color:gray\">";
echo "<h4><u>Info</u></h4>";
echo "<small>Type: </small>";
echo "<span class=\"badge badge-primary\">$element $weptype</span>";
echo "<br>";
echo "<small>Rarity: $rarity</small><br>";
echo "<small>System: $controls</small><br>";
echo "<small>Sheet/season: <a href=\"" . $spreadsheet . sheetUrl(ucwords($sheet)) . "\">" . ucwords($sheet) . "</a></small><br>";
echo "<small>" . $coldef["Response"]["sourceString"] . "</small><br>";

// PVP ROLL
echo "<p><h4><u>PvP:</u></h4></p>";
?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Barrels/Sights</th>
      <th scope="col">Magazine</th>
      <th scope="col">Perks 1</th>
	  <th scope="col">Perks 2</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
	  <?php 

$great = array();
$good = array();

foreach($item["pvp"]["greatPerks"] as $key => $value) {
$great[] = $value;
}

foreach($item["pvp"]["goodPerks"] as $key => $value) {
$good[] = $value;
}

$getfirst = $itemdef["Response"]["sockets"]["socketEntries"][1];
echo getPerks($getfirst,$great,$good);
	  ?>
	  </td>
      <td>
	  <?php 

$great = array();
$good = array();

foreach($item["pvp"]["greatPerks"] as $key => $value) {
$great[] = $value;
}

foreach($item["pvp"]["goodPerks"] as $key => $value) {
$good[] = $value;
}

$getsecond = $itemdef["Response"]["sockets"]["socketEntries"][2];
echo getPerks($getsecond,$great,$good);
	  ?>
	  </td>
      <td>
	  <?php 

$great = array();
$good = array();

foreach($item["pvp"]["greatPerks"] as $key => $value) {
$great[] = $value;
}

foreach($item["pvp"]["goodPerks"] as $key => $value) {
$good[] = $value;
}

$getthird = $itemdef["Response"]["sockets"]["socketEntries"][3];
echo getPerks($getthird,$great,$good);
	  ?>
	  </td>
      <td>
	  <?php 

$great = array();
$good = array();

foreach($item["pvp"]["greatPerks"] as $key => $value) {
$great[] = $value;
}

foreach($item["pvp"]["goodPerks"] as $key => $value) {
$good[] = $value;
}

$getthird = $itemdef["Response"]["sockets"]["socketEntries"][4];
echo getPerks($getthird,$great,$good);
	  ?>
	  </td>
    </tr>
  </tbody>
</table>

<?php

// PVE ROLL
echo "<h5><u>PvE:</u></h5>";
?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Barrels/Sights</th>
      <th scope="col">Magazine</th>
      <th scope="col">Perks 1</th>
	  <th scope="col">Perks 2</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
	  <?php 

$great = array();
$good = array();

foreach($item["pve"]["greatPerks"] as $key => $value) {
$great[] = $value;
}

foreach($item["pve"]["goodPerks"] as $key => $value) {
$good[] = $value;
}

$getfirst = $itemdef["Response"]["sockets"]["socketEntries"][1];
echo getPerks($getfirst,$great,$good);
	  ?>
	  </td>
      <td>
	  <?php 

$great = array();
$good = array();

foreach($item["pve"]["greatPerks"] as $key => $value) {
$great[] = $value;
}

foreach($item["pve"]["goodPerks"] as $key => $value) {
$good[] = $value;
}

$getsecond = $itemdef["Response"]["sockets"]["socketEntries"][2];
echo getPerks($getsecond,$great,$good);
	  ?>
	  </td>
      <td>
	  <?php 

$great = array();
$good = array();

foreach($item["pve"]["greatPerks"] as $key => $value) {
$great[] = $value;
}

foreach($item["pve"]["goodPerks"] as $key => $value) {
$good[] = $value;
}

$getthird = $itemdef["Response"]["sockets"]["socketEntries"][3];
echo getPerks($getthird,$great,$good);
	  ?>
	  </td>
      <td>
	  <?php 

$great = array();
$good = array();

foreach($item["pve"]["greatPerks"] as $key => $value) {
$great[] = $value;
}

foreach($item["pve"]["goodPerks"] as $key => $value) {
$good[] = $value;
}

$getthird = $itemdef["Response"]["sockets"]["socketEntries"][4];
echo getPerks($getthird,$great,$good);
	  ?>
	  </td>
    </tr>
  </tbody>
</table>
<?php

    }
}

}
echo "<hr style=\"height:2px;border-width:0;color:gray;background-color:gray\">";
echo "<center><small>By <a href=\"https://www.reddit.com/user/darkelement1987\">/u/darkelement1987</a></small>";
echo " / <img src=\"https://icons.iconarchive.com/icons/martz90/circle-addon2/16/playstation-icon.png\"><small><b> DrGodroll</b></small></center>";
?>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Sort dropdown alphabetically -->
<script>
$(document).ready(function() {
	$('.js-example-basic-single').select2({
    placeholder: "Pick a weapon to find rolls",
    allowClear: true
	});
	var options = $("#gun option");       
options.detach().sort(function(a,b) {
    var at = $(a).text();
    var bt = $(b).text();         
    return (at > bt)?1:((at < bt)?-1:0);
});
options.appendTo("#gun");
$('#gun option:eq(0)').prop('selected',true);
	});
</script>
</body></html>