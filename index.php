<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<style>

html {
	font-family: arial;
}

table td, table td * {
    vertical-align: top;
	font-size: medium;
}

.table {
    max-width: fit-content;
    margin-bottom: 1rem;
    background-color: transparent;
}

.container-fluid {
    width: fit-content;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

</style>
</head>
<body>
<div class="container-fluid">
<h1><u>DrGodroll's Godroll Finder</u></h1>
<?php

// Rolls Json
$rolljson = file_get_contents("https://raw.githubusercontent.com/dcaslin/d2-checklist/f02374a0ce6b50576cc7936b326b419d8bb76889/src/assets/panda-godrolls.min.json");
$rolls = json_decode($rolljson,true);

?>

<!-- Dropdown menu -->
<form action="" method="post">
<select class="js-example-basic-single" name="gun" id="gun">
<?php
foreach ($rolls as $item) {
?>
<option data-tokens="<?php echo strtolower($item["name"])?>" value="<?php echo strtolower($item["name"])?>" id="<?php echo strtolower($item["name"])?>"><?php echo ucwords($item["name"])?></option>

<?php
}
?>

</select>
<input type="submit" value="Pick">
</form>

<?php

// Check if selected option is console or PC in source Json for right tagging.
if(isset($_POST["gun"])){

$gun = strtolower($_POST["gun"]);

foreach ($rolls as $item) {
    if ($item["name"] == $gun) {
		if ($item["mnk"] == "true" && $item["controller"] == "true" ) {$controls = "Console & PC";} else {
					if ($item["mnk"] == "true"){$controls = "PC";}
					if ($item["controller"] == "true"){$controls = "Console";}
		}

// Name of weapon
echo "<hr style=\"height:2px;border-width:0;color:gray;background-color:gray\">";
echo "<h3><u>" . ucwords($item["name"]) . "</u> (" . $controls . ")</h3>";

// Sheet
echo "<p><small><b>Sheet: </b><a href=\"https://docs.google.com/spreadsheets/d/1uZ_3QrltU2YIV5FCnzJgufB0Uti7yiXj1kaFpaiuFwk/edit#gid=1523804770\">" . ucwords($item["sheet"]) . "</a></small></p>";

// PVP ROLL
echo "<h4><u>PvP:</u></h4>";
?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Great Perks</th>
      <th scope="col">Good Perks</th>
      <th scope="col">Masterwork</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
	  <?php 
foreach($item["pvp"]["greatPerks"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
	  ?>
	  </td>
            <td>
	  <?php 
foreach($item["pvp"]["goodPerks"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
	  ?>
	  </td>
      <td>
	  <?php 
foreach($item["pvp"]["masterwork"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
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
      <th scope="col">Great Perks</th>
      <th scope="col">Good Perks</th>
      <th scope="col">Masterwork</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
	  <?php 
foreach($item["pve"]["greatPerks"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
	  ?>
	  </td>
            <td>
	  <?php 
foreach($item["pve"]["goodPerks"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
	  ?>
	  </td>
      <td>
	  <?php 
foreach($item["pve"]["masterwork"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
	  ?>
	  </td>
    </tr>
  </tbody>
</table>
<?php

    }
}

} else { echo "Pick a gun from the list...";}

// End check if selected option is console or PC in source Json for right tagging.
?>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
	$('.js-example-basic-single').select2();
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
