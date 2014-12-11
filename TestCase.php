<?php

namespace yii\phpunit;

use Yii;
use yii\base\ExitException;


class TestCase extends \PHPUnit_Framework_TestCase {

	public function run(\PHPUnit_Framework_TestResult $result = null) {
		$this->setPreserveGlobalState(false);
		return parent::run($result);
	}

	protected static function appRun() {
		/** @var \yii\console\Application $app */
		$app = Yii::$app;
		$response = new \yii\web\Response;
		try {

			$app->state = \yii\base\Application::STATE_BEFORE_REQUEST;
			$app->trigger(\yii\base\Application::EVENT_BEFORE_REQUEST);

			$app->state = \yii\base\Application::STATE_HANDLING_REQUEST;
			$request = $app->getRequest();
			static::onAppRunRequest($request);
			$response = $app->handleRequest($request);

			$app->state = \yii\base\Application::STATE_AFTER_REQUEST;
			$app->trigger(\yii\base\Application::EVENT_AFTER_REQUEST);

			$app->state = \yii\base\Application::STATE_SENDING_RESPONSE;
			//$response->send();

			$app->state = \yii\base\Application::STATE_END;

		} catch (ExitException $e) {
			//$app->end($e->statusCode, isset($response) ? $response : null);
			$response->statusCode = $e->statusCode;

		}
		return $response;
	}

	protected static function onAppRunRequest($request) {
	}

}