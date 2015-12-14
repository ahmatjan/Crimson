<li><a href="../forum/ucp.php?i=173"><i class="icon-fixed-width icon-pencil"></i> Preferences</a></li>
<li><a href="../forum/viewforum.php?f=27"><i class="icon-fixed-width icon-envelope"></i> Contact Support</a></li>
<li><a href="./forum/ucp.php?i=pm&folder=inbox"><i class="icon-fixed-width icon-comments"></i> 
<?php
if($user->data['user_new_privmsg'] == 1) {
	echo $user->data['user_new_privmsg'].' new message';
} else if ($user->data['user_new_privmsg'] > 1) {
	echo $user->data['user_new_privmsg'].' new messages';
} else {
	echo '0 new messages';
};
?>
</a></li>
<li class="divider"></li>
<li><a href="../forum/ucp.php?mode=logout&sid=<?php echo $_COOKIE['phpbb3_gazoo_sid']; ?>"><i class="icon-fixed-width icon-signout"></i> Logout</a></li>
<?php 
if( (in_array('8',$userGroupIDs)) || ($userName == 'Neekasa') || (in_array('9',$userGroupIDs)) || (in_array('13',$userGroupIDs))){
	?>
<li class="divider"></li>
<li><a href="../admin.php"><i class="icon-fixed-width: icon-gears"></i> Admin CP</a></li>
	<?php
};
?>