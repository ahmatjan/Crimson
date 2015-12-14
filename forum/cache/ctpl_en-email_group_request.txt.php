<?php if (!defined('IN_PHPBB')) exit; ?>Subject: A request to join your group has been made

Dear <?php echo (isset($this->_rootref['USERNAME'])) ? $this->_rootref['USERNAME'] : ''; ?>,

The user "<?php echo (isset($this->_rootref['REQUEST_USERNAME'])) ? $this->_rootref['REQUEST_USERNAME'] : ''; ?>" has requested to join the group "<?php echo (isset($this->_rootref['GROUP_NAME'])) ? $this->_rootref['GROUP_NAME'] : ''; ?>" you moderate on "<?php echo (isset($this->_rootref['SITENAME'])) ? $this->_rootref['SITENAME'] : ''; ?>".
To approve or deny this request for group membership please visit the following link:

<?php echo (isset($this->_rootref['U_PENDING'])) ? $this->_rootref['U_PENDING'] : ''; ?>


<?php echo (isset($this->_rootref['EMAIL_SIG'])) ? $this->_rootref['EMAIL_SIG'] : ''; ?>