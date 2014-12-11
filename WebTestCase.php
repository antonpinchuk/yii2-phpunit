<?php

namespace yii\phpunit;

use Yii;

class WebTestCase extends TestCase {

	public function __construct($name = null, array $data = array(), $dataName = '') {
		parent::__construct($name, $data, $dataName);

		$_SERVER['SCRIPT_FILENAME'] = YII_TEST_ENTRY_FILE;
		$_SERVER['SCRIPT_NAME'] = YII_TEST_ENTRY_URL;
		$_SERVER['SERVER_NAME'] = 'localhost';

		$config = \yii\helpers\ArrayHelper::merge(
			require(__DIR__ . '/../config/web.php'),
			require(__DIR__ . '/../config/test.php')
		);
		new \yii\web\Application($config);
	}

	public function run(\PHPUnit_Framework_TestResult $result = null) {
		$this->setPreserveGlobalState(false);
		return parent::run($result);
	}

	/**
	 * @param string $url
	 * @param array $get
	 * @param array $post
	 * @throws \yii\web\NotFoundHttpException
	 * @return \yii\web\Response
	 */
	public static function handleRequest($url, $get = [], $post = null) {
		$_SERVER['REQUEST_URI'] = $url;
		$_SERVER['REQUEST_METHOD'] = $post !== null ? 'POST' : 'GET';
		foreach ($get as $k => $v) {
			$_GET[$k] = $v;
		}
		if ($post !== null) {
			foreach ($post as $k => $v) {
				$_POST[$k] = $v;
			}
		}
		return self::appRun();
	}

	/**
	 * @param \yii\web\Request $request
	 */
	protected static function onAppRunRequest($request) {
		$request->setBodyParams(null);
	}

}