<?php
require_once("functions.php");
require("config/config.php");
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('memory_limit', '-1');
set_time_limit(0);
error_reporting(E_ALL);
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

// Check manifest and update stuff if needed
$d2manifest = apiRequest('/Destiny2/Manifest/');
$remoteversion = $d2manifest["Response"]["version"];

$bungie = "https://www.bungie.net";
$getInvItemDef = file_get_contents($bungie . $d2manifest["Response"]["jsonWorldComponentContentPaths"]["en"]["DestinyInventoryItemDefinition"]);
$getColDef = file_get_contents($bungie . $d2manifest["Response"]["jsonWorldComponentContentPaths"]["en"]["DestinyCollectibleDefinition"]);
$getPlugDef = file_get_contents($bungie . $d2manifest["Response"]["jsonWorldComponentContentPaths"]["en"]["DestinyPlugSetDefinition"]);
$getDmgDef = file_get_contents($bungie . $d2manifest["Response"]["jsonWorldComponentContentPaths"]["en"]["DestinyDamageTypeDefinition"]);
$getStatDef = file_get_contents($bungie . $d2manifest["Response"]["jsonWorldComponentContentPaths"]["en"]["DestinyStatDefinition"]);

// Creation of files and update procedure
if(!file_exists('version.txt')){
	file_put_contents('version.txt',$remoteversion);
}

$localversion = file_get_contents('version.txt');

if($localversion !== $remoteversion){
	file_put_contents('version.txt',$remoteversion);
	file_put_contents("invitemdef.json",$getInvItemDef);
	file_put_contents("coldef.json",$getColDef);
	file_put_contents("plugdef.json",$getPlugDef);
	file_put_contents("dmgdef.json",$getDmgDef);
	file_put_contents("statdef.json",$getStatDef);
	}

if(!file_exists('invitemdef.json')){
		file_put_contents("invitemdef.json",$getInvItemDef);
}

if(!file_exists('coldef.json')){
		file_put_contents("coldef.json",$getColDef);
}

if(!file_exists('plugdef.json')){
		file_put_contents("plugdef.json",$getPlugDef);
}

if(!file_exists('dmgdef.json')){
		file_put_contents("dmgdef.json",$getDmgDef);
}

if(!file_exists('statdef.json')){
		file_put_contents("statdef.json",$getStatDef);
}

$invItemDef = json_decode(file_get_contents("invitemdef.json"),true);
$colDef = json_decode(file_get_contents("coldef.json"),true);
$plugDef = json_decode(file_get_contents("plugdef.json"),true);
$dmgDef = json_decode(file_get_contents("dmgdef.json"),true);
$statDef = json_decode(file_get_contents("statdef.json"),true);

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
?>
<option data-tokens="<?php echo strtolower($item["name"])?>" value="<?php echo ucwords($item["name"])?>|<?php echo ucwords($item["sheet"])?>"><?php echo ucwords($item["name"]). " ("  . ucwords($item["sheet"]) . ")"?></option>
<?php
}
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
$name = ucwords($item["name"]);

foreach($invItemDef as $key => $value){
	// Note: When errors on, bug occured on Heretic, no defaultDamageTypeHash in invitemdef
	// edit: solved with isset($value["defaultDamageTypeHash"]), there were 2 Heretic entries in invitemdef.json apparently
	// 1 with defaultDamageTypeHash and 1 without
	if(strcasecmp($value["displayProperties"]["name"], $name) == 0 && isset($value["collectibleHash"]) && isset($value["defaultDamageTypeHash"])){
		$itemdef = $key;
		}
		}

// Read json/itemdef/ files
$read = json_decode(file_get_contents("json/itemdef/" . $itemdef . ".json"),true);
$colhash = $read["collectibleHash"];
$icon = $bungie . $read["displayProperties"]["icon"];
$rarity = $read["inventory"]["tierTypeName"];
$flavor = $read["flavorText"];
$weptype = $read["itemTypeDisplayName"];
$getelem = $read["defaultDamageType"];
$getelemhash = $read["defaultDamageTypeHash"];
$intrhash = $read["sockets"]["socketEntries"][0]["singleInitialItemHash"];
$intrfile = json_decode(file_get_contents("json/itemdef/" . $intrhash . ".json"),true);
$intrname = $intrfile["displayProperties"]["name"];

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

$mwpvp = "";
$mwpve = "";

// Specify MW
foreach($item["pvp"]["masterwork"] as $key => $value) {
$mwpvp = "<p><h5>Masterwork: <small><b>" . ucwords($value) . "</b></small></h5></p>";
}

foreach($item["pve"]["masterwork"] as $key => $value) {
$mwpve = "<p><h5>Masterwork: <small><b>" . ucwords($value) . "</b></small></h5></p>";
}

// Weapon name, thumbnail and flavortext
echo "<hr style=\"height:2px;border-width:0;color:gray;background-color:gray\">";
echo "<div class=\"container\">";
echo "<h4><u>" . ucwords($item["name"]) . "</u></h4>";
echo "<p><img src=\"" . $icon . "\"></p><i><footer class=\"blockquote-footer\">$flavor</footer></i>";
echo "</div>";
echo "<hr style=\"height:2px;border-width:0;color:gray;background-color:gray\">";

// Weapon Info
echo "<h4><u>Info</u></h4>";
?>
<small>
<div class="container">
<div class="row">
  <div class="col-auto">
  Type:<br>
  Intrinsic:<br>
  Rarity:<br>
  System:<br>
  Sheet:<br>
  Source:<br>
  </div>
  <div class="col"><span class="badge badge-primary"><img src="<?=$bungie . $dmgDef[$getelemhash]["displayProperties"]["icon"]?>" height="16">
  <?=$element?> <?=$weptype?></span><br>
  <?=$intrname?><br>
  <?=$rarity?><br>
  <?=$controls?><br>
  <a href="<?=$spreadsheet?><?=sheetUrl(ucwords($sheet))?>"><?=ucwords($sheet)?></a><br>
  <?=str_replace('Source: ', '', $colDef[$colhash]["sourceString"])?>
  </div>
</div>
</div>
</small>
<hr style="height:2px;border-width:0;color:gray;background-color:gray">
<!-- Weapon Stats -->
<p><h4><u>Stats:</u></h4></p>
<small>
<?php
$statnames = array();
$statvalues = array();
foreach($read["stats"]["stats"] as $key => $value){
	foreach($read["stats"]["stats"][$key] as $key => $value){
		if($key == "statHash"){
			if(!empty($statDef[$value]["displayProperties"]["name"] && $statDef[$value]["displayProperties"]["name"] !== "Power" && $statDef[$value]["displayProperties"]["name"] !== "Attack")){
			$statnames[] = $statDef[$value]["displayProperties"]["name"];
			$statvalues[] = $read["stats"]["stats"][$value]["value"];
			}
		}
	}
}
?>
<div class="container">
<div class="row">
  <div class="col-auto">
  <?php
  foreach($statnames as $names){echo "» " . $names . '<br>';}
  ?>
  </div>
  <div class="col">
  <?php
  foreach($statvalues as $values){echo $values . '<br>';}
  ?>
  </div>
</div>
</div>
</small>

<?php
echo "<hr style=\"height:2px;border-width:0;color:gray;background-color:gray\">";

// PVP ROLL
echo "<p><h4><u>Good Perks PvP:</u></h4></p>";
echo $mwpvp;

?>
<table class="table table-sm">
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

$getfirst = $read["sockets"]["socketEntries"][1];
echo getPerks($getfirst,$great,$good,$plugDef);
	  ?>
	  </td>
      <td>
	  <?php 

$getsecond = $read["sockets"]["socketEntries"][2];
echo getPerks($getsecond,$great,$good,$plugDef);
	  ?>
	  </td>
      <td>
	  <?php 

$getthird = $read["sockets"]["socketEntries"][3];
echo getPerks($getthird,$great,$good,$plugDef);
	  ?>
	  </td>
      <td>
	  <?php 

$getthird = $read["sockets"]["socketEntries"][4];
echo getPerks($getthird,$great,$good,$plugDef);
	  ?>
	  </td>
    </tr>
  </tbody>
</table>

<?php

// PVE ROLL
echo "<p><small>* = Best perks / <b>Bold</b> = Good perks</small></p>";
echo "<h4><u>Good Perks PvE:</u></h4>";
echo $mwpve;

?>
<table class="table table-sm">
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

foreach($item["pve"]["greatPerks"] as $key => $value) {
$great[] = $value;
}

foreach($item["pve"]["goodPerks"] as $key => $value) {
$good[] = $value;
}

$getfirst = $read["sockets"]["socketEntries"][1];
echo getPerks($getfirst,$great,$good,$plugDef);
	  ?>
	  </td>
      <td>
	  <?php 

$getsecond = $read["sockets"]["socketEntries"][2];
echo getPerks($getsecond,$great,$good,$plugDef);
	  ?>
	  </td>
      <td>
	  <?php 

$getthird = $read["sockets"]["socketEntries"][3];
echo getPerks($getthird,$great,$good,$plugDef);
	  ?>
	  </td>
      <td>
	  <?php 

$getthird = $read["sockets"]["socketEntries"][4];
echo getPerks($getthird,$great,$good,$plugDef);
	  ?>
	  </td>
    </tr>
  </tbody>
</table>
<?php
echo "<p><small>* = Best perks / <b>Bold</b> = Good perks</small></p>";
    }
}

}

// Footer text & links
echo "<hr style=\"height:2px;border-width:0;color:gray;background-color:gray\">";
echo "<center><small>By <a href=\"https://www.reddit.com/user/darkelement1987\">/u/darkelement1987</a></small>";
echo " / <img src=\"https://icons.iconarchive.com/icons/martz90/circle-addon2/16/playstation-icon.png\"><small><b> DrGodroll</b> / <img src=\"https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png\" height=\"24\"> <a href=\"https://github.com/darkelement1987/rollfinder\">Github</a><br>Rolls by /u/Pandapaxxy & /u/Rykerboy</small></center>";
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
