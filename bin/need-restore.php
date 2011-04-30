<?php

/**
 * @todo find a way to remove those two lines. Should work with default include_path.
 * Maybe I should add a configuration on the ini file for that ?!
 */
define('ROOT_PATH', dirname(dirname(__FILE__)));
set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_PATH . '/library');

require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Zend_');
$loader->registerNamespace('INB_');

try {
	$opts = new Zend_Console_Getopt(array(
		'keep' => 'Keep the backup at the top of the stack',
		'id=s' => 'Restore the backup with given id (keeps it on the stack by default)',
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

$command = new INB_Command_Restore();
$command->setArguments($args);
$command->setOptions($opts);

$command->execute();