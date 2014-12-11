<?php

namespace yii\phpunit;

use Yii;

class ConsoleTestCase extends TestCase {

	public function __construct($name = null, array $data = array(), $dataName = '') {
		parent::__construct($name, $data, $dataName);

		//defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
		//defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));

		$config = \yii\helpers\ArrayHelper::merge(
			require(__DIR__ . '/../config/console.php'),
			require(__DIR__ . '/../config/test.php')
		);
		new \yii\console\Application($config);
	}

	public function __destruct() {
		//parent::__destruct();

		//fclose(STDIN);
		//fclose(STDOUT);
	}

	/**
	 * @param string $command
	 * @param array $argv
	 * @throws \yii\web\NotFoundHttpException
	 * @return \yii\web\Response
	 */
	public static function handleRequest($command, $argv = []) {
		$_SERVER['argv'] = array_merge([ 'yii', $command ], $argv);
		return self::appRun();
	}

}