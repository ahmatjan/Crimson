<?php
	$con = mysqli_connect('localhost','crimson_alliance','Acisherrig1@','crimson_alliance');
	$sql = "SELECT COUNT(pos) FROM suicide";
	$que = mysqli_query($con,$sql);
	$row = mysqli_fetch_row($que);
	echo $row[0];
?>