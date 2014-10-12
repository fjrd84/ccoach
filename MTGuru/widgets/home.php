<?php
function printHome(){
?>

<!DOCTYPE head PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/general.css">
</head>
<body>
<div class="testInfos">
<?php echo 'hello '.$_SESSION['currentUser']['userName'];?>
<?php echo 'Points: '.$_SESSION['currentUser']['points'];?>
<?php echo 'Level: '.$_SESSION['currentUser']['level'];?>
<a href="game.php">GO PLAY!</a>
</div>
</body>
</html>
<?php 
}
?>