<?php
$url_extension = '/';
switch ($_SERVER['PHP_SELF']){
	case ''.$url_extension.'':
		$home = 'class="active"';
	break;
	case ''.$url_extension.'index.php':
		$home = 'class="active"';
	break;
	case ''.$url_extension.'members.php':
		$memb = 'class="active"';
	break;
	case ''.$url_extension.'calendar.php':
		$cale = 'active';
	break;
	case ''.$url_extension.'calendar_signup.php':
		$sign = true;
	break;
	case ''.$url_extension.'raiders.php':
		$raid = 'class="active"';
	break;
	case ''.$url_extension.'suicide.php':
		$suic = 'class="active"';
	break;
}
?>
<li <?php echo $home; ?>>
	<a href="./">Home</a>
</li>
<li>
	<a href="./forum">Forum</a>
</li>
<li class="hidden-phone <?php echo $cale; ?>">
	<a href="./calendar.php">Calendar</a>
</li>
<li <?php echo $suic; ?>>
	<a href="./suicide.php">Suicide List</a>
</li>
<li <?php echo $raid; ?>>
	<a href="./raiders.php">Raiders List</a>
</li>
<li <?php echo $memb; ?>>
	<a href="./members.php">Members</a>
</li>