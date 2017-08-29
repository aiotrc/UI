<?php

namespace SmartCity\CoreBundle\Controller;

use SmartCity\ProductBundle\Entity\Product;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use SmartCity\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Base controller.
 */
class BaseController extends Controller
{

	/**
     * Check required variable in request
     *
     * @param $variableName
     * @return mixed
     */
	 protected function required($variableName)
	 {
		 $request = $this->container->get('request');
		 $value = $request->get($variableName);
		 if (! isset($value)) {
			if ($request->isXmlHttpRequest()){
				$response = $this->get('translator')->trans('label.is_required', array(), 'labels').' '.$variableName;
				throw new \Exception($response);
			}
			else {
				$this->error("$variableName is required", Logger::ERROR);
			}
		 } else {
			 return $value;
		 }
		 return null;
	 }

	/**
	 * Check optional variable in request
	 *
	 * @param $variableName
	 * @return mixed
	 */
	protected function optional($variableName)
	{
		$request = $this->container->get('request');
		return $request->get($variableName);
	}

	/**
	 * Send JSON response to the client
	 * {
	 *   'status': true,
	 *   'data': {  }
	 * }
	 *
	 * @param array $data
	 * @return JsonResponse
	 */
	protected function success(array $data)
	{
		return new JsonResponse(array(
			'status' => true,
			'data' => $data
		));
	}

	/**
	 * Log an error and then throw an Exception
	 * @param  string $text The log message
	 * @param  mixed $level The log level
	 * @param  array $context The log context
	 * @throws \Exception
	 */
	protected function error($text, $level = Logger::INFO, array $context = array())
	{
		$this->log($text, $level, $context);
		throw new \Exception($text);
	}

	/**
	 * Log an error and then throw an Exception
	 * @param  string $text The log message
	 * @param  mixed $level The log level
	 * @param  array $context The log context
	 * @return JsonResponse
	 */
	protected function errorJson($text, $level = Logger::INFO, array $context = array())
	{
		$this->log($text, $level, $context);
		return new JsonResponse(
			array(
				'status' => false,
				'error' => $text
			)
		);
	}

	/**
	 * Adds a log record at an arbitrary level.
	 *
	 * @param  mixed $level The log level
	 * @param  string $text The log message
	 * @param  array $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	protected function log($text, $level = Logger::INFO, array $context = array())
	{
		$request = $this->container->get('request');
		$routeName = $request->get('_route');

		$logger = $this->get('logger');
		$message = "$routeName: $text";

		$logger->log($level, $message, $context);
	}

	/**
     * Return logged in User
     *
     * @return User
     */
    protected function getCurrentUser()
    {
        $user = $this->getUser();
        if (! $user) {
            throw new LogicException(ErrorCode::USER_NOT_LOGGED_IN);
        }

        return $user;
    }

	/**
	 * Validate Entity with Validator Service
	 *
	 * @param $entity
	 * @return bool
	 */
	protected function validate($entity)
	{
		$validator = $this->get('validator');
		$errors = $validator->validate($entity);

		if (count($errors) > 0) {
			/** @var ConstraintViolation $error */
			$error = $errors[0];
			$property = $error->getPropertyPath();
			$message = $error->getMessage();
			$this->error("$property: $message", Logger::ERROR);
		}
		return true;
	}

	/**
	 *  Check a device for being an iphone
	 *
	 * @param string $type
	 * @return bool|mixed
	 */
	public function isAppleDevice($type = 'Device')
	{
		if (!array_key_exists('HTTP_USER_AGENT', $_SERVER))
			return false;

		// Apple detection
		$Apple = array();
		$Apple['UA'] = $_SERVER['HTTP_USER_AGENT'];
		$Apple['Device'] = false;
		$Apple['Types'] = array('iPhone', 'iPod', 'iPad');
		foreach ($Apple['Types'] as $d => $t) {
			$Apple[$t] = (strpos($Apple['UA'], $t) !== false);
			$Apple['Device'] |= $Apple[$t];
		}

		return $Apple[$type];
	}

	/**
	 * Checks a device for being an android device
	 *
	 * @param string $type
	 * @return mixed
	 */
	public function isAndroidDevice($type = 'Device')
	{
		// Apple detection
		$Android = array();
		$Android['UA'] = $_SERVER['HTTP_USER_AGENT'];
		$Android['Device'] = false;
		$Android['Types'] = array('Android');
		foreach ($Android['Types'] as $d => $t) {
			$Android[$t] = (stripos($Android['UA'], $t) !== false);
			$Android['Device'] |= $Android[$t];
		}

		return $Android[$type];
	}

	/**
	 * Get's current User
	 *
	 * @return null | User
	 */
	public function getUser()
	{
		if (!$this->container->has('security.token_storage')) {
			throw new \LogicException('The SecurityBundle is not registered in your application.');
		}

		if (null === $token = $this->container->get('security.token_storage')->getToken()) {
			return null;
		}

		if (!is_object($user = $token->getUser())) {
			return;
		}

		return $user;
	}


	/**
	 * Send mail
	 *
	 * @param $from
	 * @param $to
	 * @param $subject
	 * @param $message
	 */
	public function sendMail($from, $to, $subject, $message) {

		date_default_timezone_set('Asia/Tehran');

		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= "From: Jewelry shop <$from>\n";
		$headers .= "Reply-To: $from";

		mail($to, $subject, $message, $headers);
	}
}