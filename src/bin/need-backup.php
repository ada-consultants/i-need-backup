#!/usr/bin/env php
<?php
if (getenv('INB')) {
	set_include_path(get_include_path() . PATH_SEPARATOR . realpath(getenv('INB')));
}

require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Zend_');
$loader->registerNamespace('INB_');

try {
	$opts = new Zend_Console_Getopt(array(
		'comment=s' => 'Provide a comment to the current backup',
		'bottom' => 'Save the current backup to the bottom of the stack',
		'id=s' => 'Provide an identifier to the backup for future references',
		'ini=s' => 'Set the config file to load'
	));
	$opts->parse();
	$args = $opts->getRemainingArgs();
} catch (Zend_Console_Getopt_Exception $e) {
	echo $e->getUsageMessage();
	exit(1);
}

$config_manager = new INB_ConfigManager($opts->ini);
Zend_Registry::set('config', $config_manager->getConfig());

$command = new INB_Command_Backup();
$command->setArguments($args);
$command->setOptions($opts);

$command->execute();