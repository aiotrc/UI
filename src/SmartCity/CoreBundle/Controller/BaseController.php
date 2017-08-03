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
	 * @throws \Exception
	 */
	protected function required($variableName)
	{
		$request = $this->get('request');
		$value = $request->get($variableName);
		if (!isset($value) || $value == "") {
			$response = $this->get('translator')->trans('label.is_required', array(), 'labels').' '.$variableName;
			throw new \Exception($response);
		}
		return $value;
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


	// protected function login($username, $password)
	// {
	// 	$request = $this->container->get('request');
	// 	$em = $this->getDoctrine()->getManager();
	// 	$userModel = $em->getRepository('SmartCityUserBundle:User');

	// 	try {
	// 		$user = $userModel->loadUserByUsername($username);
	// 	} catch(UsernameNotFoundException $e) {
	// 		$this->error('errors.user.wrong_username_or_password', Logger::ERROR, array('username' => $username));
	// 		return null;
	// 	}

	// 	// Get the encoder for the users password
	// 	$encoder_service = $this->get('security.encoder_factory');
	// 	$encoder = $encoder_service->getEncoder($user);

	// 	// logging a new user in, manually
	// 	$providerKey = 'main';
	// 	$firewallName = 'api_v1';
	// 	if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
	// 		$unauthenticatedToken = new UsernamePasswordToken($user, $user->getPassword(), $providerKey, $user->getRoles());
	// 		$authenticatedToken = $unauthenticatedToken;
	// 		$this->get("security.token_storage")->setToken($authenticatedToken); // now the user is logged in

	// 		// Fire the login event
	// 		// Logging the user in above the way we do it doesn't do this automatically
	// 		$event = new InteractiveLoginEvent($request, $authenticatedToken);
	// 		$this->get("event_dispatcher")->dispatch("security.authentication", $event); // interactive_login changed to authentication
	// 		$this->get('event_dispatcher')->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);
	// 		$this->get('event_dispatcher')->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($authenticatedToken) );

	// 		$this->get('session')->set('_security_' . $firewallName, serialize($authenticatedToken));
	// 	} else {
	// 		$this->error('errors.user.wrong_username_or_password', Logger::ERROR, array('username' => $username));
	// 		return null;
	// 	}
	// 	return $user;
	// }

	protected function login($username, $password, $manualLogin = false)
    {

        // $em = $this->getDoctrine()->getManager();
        // $userModel = $em->getRepository('SmartCityUserBundle:User');
        // $user = $userModel->loadUserByUsername($username);
        
        // if (!$user) {
        //     throw new UsernameNotFoundException("User not found");
        // } 
        // else {
        //     $token = new UsernamePasswordToken($user, null, "your_firewall_name", $user->getRoles());
        //     $this->get("security.context")->setToken($token); //now the user is logged in
             
        //     //now dispatch the login event
        //     $request = $this->get("request");
        //     $event = new InteractiveLoginEvent($request, $token);
        //     $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        // }

        $request = $this->container->get('request');
        $em = $this->getDoctrine()->getManager();
        $userModel = $em->getRepository('SmartCityUserBundle:User');

        try {
            $user = $userModel->loadUserByUsername($username);
        } 
        catch(UsernameNotFoundException $e) {
            throw new LogicException(ErrorCode::WRONG_USERNAME_OR_PASSWORD, array(
                'username' => $username, 
                'password' => $password
            ));
        }

        
        $encoder_service = $this->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($user);

        $providerKey = 'main';
        $firewallName = 'panel_register';
        if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt()) || $manualLogin) {

            $token = new UsernamePasswordToken($user, $user->getPassword(), $providerKey, $user->getRoles());
            $this->get("security.token_storage")->setToken($token);

            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.authentication", $event);
            $this->get('event_dispatcher')->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);
            $this->get('event_dispatcher')->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($token) );

            $session = $request->getSession();
            $session->set('_security_' . $firewallName, serialize($token));
            $session->save();

        } 
        else {
            throw new LogicException(ErrorCode::WRONG_USERNAME_OR_PASSWORD, array(
                'username' => $username, 
                'password' => $password
            ));
        }

        return $user;
    }

	/**
	 * @param Product $product
	 * @return string
	 */
	public function getEncodedHash(Product $product) {
		$title = $product->getTitle();
		$id = $product->getId();

		$plain_hash = $title . $id . $title;
		$sha_first = sha1($plain_hash);
		$md5 = md5($sha_first);
		$final = sha1($md5);
		return $final;
	}

	public function isValidIdCode($idCode) {
		$idCode = (int) $idCode;
		$control_digit = $idCode % 10;
		$number = $idCode / 10;

		$sum = 0;
		$i = 2;

		while ($number > 0) {
			$current_digit = $number % 10;
			$sum += $current_digit * $i;
			$i += 1;
			$number /= 10;
		}
		$reminder = $sum % 11;
		if ($reminder < 2) {
			if ($reminder == $control_digit)
				return true;
			else return false;
		}
		if (11 - $reminder == $control_digit)
			return true;
		return false;
	}
}