<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
</head>
<body>
<h1><u>DrGodroll's Godroll Finder</u></h1>
<?php
$json = file_get_contents("https://raw.githubusercontent.com/dcaslin/d2-checklist/f02374a0ce6b50576cc7936b326b419d8bb76889/src/assets/panda-godrolls.min.json");
$data = json_decode($json,true);
?>
<form action="" method="post">
<select class="js-example-basic-single" name="gun" id="gun">
<?php
foreach ($data as $item) {
?>
<option value="<?php echo ucwords($item["name"])?>" id="<?php echo $item["name"]?>"><?php echo ucwords($item["name"])?></option>

<?php
}
?>

</select>
<input type="submit">
</form>

<?php
if(isset($_POST["gun"])){

$gun = strtolower($_POST["gun"]);

foreach ($data as $item) {
    if ($item["name"] == $gun) {
		if ($item["mnk"] == "true" && $item["controller"] == "true" ) {$controls = "Console & PC";} else {
					if ($item["mnk"] == "true"){$controls = "PC";}
					if ($item["controller"] == "true"){$controls = "Console";}
		}
echo "<h1><u>" . ucwords($item["name"]) . "</u> (" . $controls . ")</h1>";
echo "<b>Comments:</b> " . ucwords($item["sheet"]);

// PVP ROLL
echo "<h3><u>PvP:</u></h3>";

echo "<b>Best perks:</b><br>";
foreach($item["pvp"]["greatPerks"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
echo "<br>";

echo "<b>Good perks:</b><br>";
foreach($item["pvp"]["goodPerks"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
echo "<br>";

echo "<b>Masterwork:</b><br>";
foreach($item["pvp"]["masterwork"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
echo "<br>";

// PVE ROLL
echo "<h3><u>PvE:</u></h3>";

echo "<b>Best perks:</b><br>";
foreach($item["pve"]["greatPerks"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
echo "<br>";

echo "<b>Good perks:</b><br>";
foreach($item["pve"]["goodPerks"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}
echo "<br>";

echo "<b>Masterwork:</b><br>";
foreach($item["pve"]["masterwork"] as $key => $value) {
$num = $key + 1;
  echo $num . ". " . ucwords($value) . "<br>";
}

echo "<br>";

    }
}

} else { echo "Pick a gun from the list...";}
?>

</body></html>
