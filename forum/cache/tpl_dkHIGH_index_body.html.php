<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('overall_header.html'); $this->_tpl_include('forumlist_body.html'); if (! $this->_rootref['S_IS_BOT'] || $this->_rootref['U_TEAM']) {  ?>

    <span class="gensmall"><?php if (! $this->_rootref['S_IS_BOT']) {  ?><a href="<?php echo (isset($this->_rootref['U_DELETE_COOKIES'])) ? $this->_rootref['U_DELETE_COOKIES'] : ''; ?>"><?php echo ((isset($this->_rootref['L_DELETE_COOKIES'])) ? $this->_rootref['L_DELETE_COOKIES'] : ((isset($user->lang['DELETE_COOKIES'])) ? $user->lang['DELETE_COOKIES'] : '{ DELETE_COOKIES }')); ?></a><?php if ($this->_rootref['U_TEAM']) {  ?> | <?php } } if ($this->_rootref['U_TEAM']) {  ?><a href="<?php echo (isset($this->_rootref['U_TEAM'])) ? $this->_rootref['U_TEAM'] : ''; ?>"><?php echo ((isset($this->_rootref['L_THE_TEAM'])) ? $this->_rootref['L_THE_TEAM'] : ((isset($user->lang['THE_TEAM'])) ? $user->lang['THE_TEAM'] : '{ THE_TEAM }')); ?></a><?php } ?></span><br />
<?php } ?>


<br clear="all" />

<?php $this->_tpl_include('breadcrumbs.html'); if ($this->_rootref['S_DISPLAY_ONLINE_LIST']) {  ?>

	<br clear="all" />

    <?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_START'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_START'] : ''; ?>

	<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_START'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_START'] : ''; if ($this->_rootref['U_VIEWONLINE']) {  ?><a href="<?php echo (isset($this->_rootref['U_VIEWONLINE'])) ? $this->_rootref['U_VIEWONLINE'] : ''; ?>">Whi is online</a><?php } else { } echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_END'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_END'] : ''; ?>

	<table class="tablebg" width="100%" cellspacing="<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_SPACING'])) ? $this->_tpldata['DEFINE']['.']['CA_SPACING'] : ''; ?>">
	<tr>
	<?php if ($this->_rootref['LEGEND']) {  ?>

		<td class="row1" rowspan="2" align="center" valign="middle"><img src="<?php echo (isset($this->_rootref['T_THEME_PATH'])) ? $this->_rootref['T_THEME_PATH'] : ''; ?>/images/whosonline.png" alt="<?php echo ((isset($this->_rootref['L_WHO_IS_ONLINE'])) ? $this->_rootref['L_WHO_IS_ONLINE'] : ((isset($user->lang['WHO_IS_ONLINE'])) ? $user->lang['WHO_IS_ONLINE'] : '{ WHO_IS_ONLINE }')); ?>" /></td>
	<?php } else { ?>

		<td class="row1" align="center" valign="middle"><img src="<?php echo (isset($this->_rootref['T_THEME_PATH'])) ? $this->_rootref['T_THEME_PATH'] : ''; ?>/images/whosonline.png" alt="<?php echo ((isset($this->_rootref['L_WHO_IS_ONLINE'])) ? $this->_rootref['L_WHO_IS_ONLINE'] : ((isset($user->lang['WHO_IS_ONLINE'])) ? $user->lang['WHO_IS_ONLINE'] : '{ WHO_IS_ONLINE }')); ?>" /></td>
	<?php } ?>

		<td class="row1" width="100%"><span class="genmed"><?php echo (isset($this->_rootref['TOTAL_USERS_ONLINE'])) ? $this->_rootref['TOTAL_USERS_ONLINE'] : ''; ?> (based on users active over the past 15 minutes)<br />(based on users active over the past 15 minutes)<br /><br /><?php echo (isset($this->_rootref['LOGGED_IN_USER_LIST'])) ? $this->_rootref['LOGGED_IN_USER_LIST'] : ''; ?><br></span></td>
	</tr>
	<?php if ($this->_rootref['LEGEND']) {  ?>

		<tr>
			<td class="row1 nobold"><center><?php echo (isset($this->_rootref['LEGEND'])) ? $this->_rootref['LEGEND'] : ''; ?></center></td>
		</tr>
	<?php } ?>

	</table>
	<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_END'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_END'] : ''; ?>

<?php } if ($this->_rootref['S_DISPLAY_BIRTHDAY_LIST'] && $this->_rootref['BIRTHDAY_LIST']) {  ?>

	<br clear="all" />
    
    <?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_START'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_START'] : ''; ?>

	<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_START'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_START'] : ''; echo ((isset($this->_rootref['L_BIRTHDAYS'])) ? $this->_rootref['L_BIRTHDAYS'] : ((isset($user->lang['BIRTHDAYS'])) ? $user->lang['BIRTHDAYS'] : '{ BIRTHDAYS }')); echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_END'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_END'] : ''; ?>

	<table class="tablebg" width="100%" cellspacing="<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_SPACING'])) ? $this->_tpldata['DEFINE']['.']['CA_SPACING'] : ''; ?>">
	<tr>
		<td class="row1" align="center" valign="middle"><img src="<?php echo (isset($this->_rootref['T_THEME_PATH'])) ? $this->_rootref['T_THEME_PATH'] : ''; ?>/images/birthday.png alt="<?php echo ((isset($this->_rootref['L_BIRTHDAYS'])) ? $this->_rootref['L_BIRTHDAYS'] : ((isset($user->lang['BIRTHDAYS'])) ? $user->lang['BIRTHDAYS'] : '{ BIRTHDAYS }')); ?>" /></td>
		<td class="row1 nobold" width="100%"><p class="genmed"><?php if ($this->_rootref['BIRTHDAY_LIST']) {  echo ((isset($this->_rootref['L_CONGRATULATIONS'])) ? $this->_rootref['L_CONGRATULATIONS'] : ((isset($user->lang['CONGRATULATIONS'])) ? $user->lang['CONGRATULATIONS'] : '{ CONGRATULATIONS }')); ?>: <b><?php echo (isset($this->_rootref['BIRTHDAY_LIST'])) ? $this->_rootref['BIRTHDAY_LIST'] : ''; ?></b><?php } else { echo ((isset($this->_rootref['L_NO_BIRTHDAYS'])) ? $this->_rootref['L_NO_BIRTHDAYS'] : ((isset($user->lang['NO_BIRTHDAYS'])) ? $user->lang['NO_BIRTHDAYS'] : '{ NO_BIRTHDAYS }')); } ?></p></td>
	</tr>
	</table>
	<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_END'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_END'] : ''; ?>

<?php } ?>


<br clear="all" />

<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_START'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_START'] : ''; ?>

<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_START'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_START'] : ''; echo ((isset($this->_rootref['L_STATISTICS'])) ? $this->_rootref['L_STATISTICS'] : ((isset($user->lang['STATISTICS'])) ? $user->lang['STATISTICS'] : '{ STATISTICS }')); echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_END'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_END'] : ''; ?>

<table class="tablebg" width="100%" cellspacing="<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_SPACING'])) ? $this->_tpldata['DEFINE']['.']['CA_SPACING'] : ''; ?>">
<tr>
	<td class="row1"><img src="<?php echo (isset($this->_rootref['T_THEME_PATH'])) ? $this->_rootref['T_THEME_PATH'] : ''; ?>/images/stats.png" alt="<?php echo ((isset($this->_rootref['L_STATISTICS'])) ? $this->_rootref['L_STATISTICS'] : ((isset($user->lang['STATISTICS'])) ? $user->lang['STATISTICS'] : '{ STATISTICS }')); ?>" /></td>
	<td class="row1 nobold" width="100%" valign="middle"><p class="genmed"><?php echo (isset($this->_rootref['TOTAL_POSTS'])) ? $this->_rootref['TOTAL_POSTS'] : ''; ?> | <?php echo (isset($this->_rootref['TOTAL_TOPICS'])) ? $this->_rootref['TOTAL_TOPICS'] : ''; ?> | <?php echo (isset($this->_rootref['TOTAL_USERS'])) ? $this->_rootref['TOTAL_USERS'] : ''; ?> | <?php echo (isset($this->_rootref['NEWEST_USER'])) ? $this->_rootref['NEWEST_USER'] : ''; ?></p></td>
</tr>
</table>
<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_END'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_END'] : ''; ?>


<?php if (! $this->_rootref['S_USER_LOGGED_IN'] && ! $this->_rootref['S_IS_BOT']) {  ?>

	<br clear="all" />

	<form method="post" action="<?php echo (isset($this->_rootref['S_LOGIN_ACTION'])) ? $this->_rootref['S_LOGIN_ACTION'] : ''; ?>">
	
	<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_START'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_START'] : ''; ?>

	<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_START'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_START'] : ''; ?><a href="<?php echo (isset($this->_rootref['U_LOGIN_LOGOUT'])) ? $this->_rootref['U_LOGIN_LOGOUT'] : ''; ?>"><?php echo ((isset($this->_rootref['L_LOGIN_LOGOUT'])) ? $this->_rootref['L_LOGIN_LOGOUT'] : ((isset($user->lang['LOGIN_LOGOUT'])) ? $user->lang['LOGIN_LOGOUT'] : '{ LOGIN_LOGOUT }')); ?></a><?php echo (isset($this->_tpldata['DEFINE']['.']['CA_CAP2_END'])) ? $this->_tpldata['DEFINE']['.']['CA_CAP2_END'] : ''; ?>

	<table class="tablebg" width="100%" cellspacing="<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_SPACING'])) ? $this->_tpldata['DEFINE']['.']['CA_SPACING'] : ''; ?>">
	<tr>
		<td class="row1" align="center"><span class="genmed"><?php echo ((isset($this->_rootref['L_USERNAME'])) ? $this->_rootref['L_USERNAME'] : ((isset($user->lang['USERNAME'])) ? $user->lang['USERNAME'] : '{ USERNAME }')); ?>:</span> <input class="post" type="text" name="username" size="10" />&nbsp; <span class="genmed"><?php echo ((isset($this->_rootref['L_PASSWORD'])) ? $this->_rootref['L_PASSWORD'] : ((isset($user->lang['PASSWORD'])) ? $user->lang['PASSWORD'] : '{ PASSWORD }')); ?>:</span> <input class="post" type="password" name="password" size="10" />&nbsp; <?php if ($this->_rootref['S_AUTOLOGIN_ENABLED']) {  ?><span class="gensmall"><?php echo ((isset($this->_rootref['L_LOG_ME_IN'])) ? $this->_rootref['L_LOG_ME_IN'] : ((isset($user->lang['LOG_ME_IN'])) ? $user->lang['LOG_ME_IN'] : '{ LOG_ME_IN }')); ?></span> <input type="checkbox" class="radio" name="autologin" />&nbsp;<?php } ?> <input type="submit" class="btnmain" name="login" value="<?php echo ((isset($this->_rootref['L_LOGIN'])) ? $this->_rootref['L_LOGIN'] : ((isset($user->lang['LOGIN'])) ? $user->lang['LOGIN'] : '{ LOGIN }')); ?>" /></td>
	</tr>
	</table>
	<?php echo (isset($this->_tpldata['DEFINE']['.']['CA_BLOCK_END'])) ? $this->_tpldata['DEFINE']['.']['CA_BLOCK_END'] : ''; ?>

	<?php echo (isset($this->_rootref['S_LOGIN_REDIRECT'])) ? $this->_rootref['S_LOGIN_REDIRECT'] : ''; ?>

	<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</form>
<?php } ?>


<br clear="all" />

<table class="legend">
<tr>
	<td width="20" align="center"><?php echo (isset($this->_rootref['FORUM_UNREAD_IMG'])) ? $this->_rootref['FORUM_UNREAD_IMG'] : ''; ?></td>
	<td><span class="gensmall"><?php echo ((isset($this->_rootref['L_UNREAD_POSTS'])) ? $this->_rootref['L_UNREAD_POSTS'] : ((isset($user->lang['UNREAD_POSTS'])) ? $user->lang['UNREAD_POSTS'] : '{ UNREAD_POSTS }')); ?></span></td>
	<td>&nbsp;&nbsp;</td>
	<td width="20" align="center"><?php echo (isset($this->_rootref['FORUM_IMG'])) ? $this->_rootref['FORUM_IMG'] : ''; ?></td>
	<td><span class="gensmall"><?php echo ((isset($this->_rootref['L_NO_UNREAD_POSTS'])) ? $this->_rootref['L_NO_UNREAD_POSTS'] : ((isset($user->lang['NO_UNREAD_POSTS'])) ? $user->lang['NO_UNREAD_POSTS'] : '{ NO_UNREAD_POSTS }')); ?></span></td>
	<td>&nbsp;&nbsp;</td>
	<td width="20" align="center"><?php echo (isset($this->_rootref['FORUM_LOCKED_IMG'])) ? $this->_rootref['FORUM_LOCKED_IMG'] : ''; ?></td>
	<td><span class="gensmall"><?php echo ((isset($this->_rootref['L_FORUM_LOCKED'])) ? $this->_rootref['L_FORUM_LOCKED'] : ((isset($user->lang['FORUM_LOCKED'])) ? $user->lang['FORUM_LOCKED'] : '{ FORUM_LOCKED }')); ?></span></td>
</tr>
</table>

<?php $this->_tpl_include('overall_footer.html'); ?>

        <center> <small> For more templates<a href="http://www/highfreak.ro">HIGHFREAK.RO</a></small><center>