--TEST--
Bug #73933 (error/segfault with ldap_mod_replace and opcache)
--SKIPIF--
<?php
require_once('skipif.inc');
?>
--FILE--
<?php
/* We are assuming 3333 is not connectable */
$ldap = ldap_connect('127.0.0.1', 3333);

ldap_mod_replace($ldap, null, array(
	'lockoutTime' => array(0),
));

ldap_close($ldap);

?>
--EXPECTF--
Warning: ldap_mod_replace(): Modify: Can't contact LDAP server in %sbug73933.php on line %d
