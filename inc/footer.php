<div class="navbar-text" style="font-size: 12px;">
	Copyright &copy; 2013 Crimson Crusade Alliance - Site designed by <a href="http://www.neekasa.com/" target="_blank">Neekasa</a>
</div>
<div class="navbar-form pull-right">
	<?php if ($userName != "Anonymous"){ ?>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="float: right; margin-top: -50px; margin-bottom: -50px;">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="6Z3R3F4B24UZJ">
	<button class="btn btn-default" type="submit" border="0" alt="Like the site? Buy me a coffee! -- Via Paypal">
	<?php
		$randomNumber = rand(1,5);
		if($randomNumber == 1){
			echo 'Buy the site a new hamster...';
		} else if ($randomNumber == 2){
			echo 'Spare some change Guv\'?';
		} else if ($randomNumber == 3){
			echo 'Donate for Relics!';
		} else if ($randomNumber == 4){
			echo 'Click here for... Nevermind.';
		} else if ($randomNumber == 5){
			echo 'Click if Epidemic is a Newb!';
		}
	?>
	</button>
	<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
	</form>
	<?php } ?>
</div>