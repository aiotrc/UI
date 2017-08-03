<?php
namespace SmartCity\UserBundle\Handler;

use SmartCity\UserBundle\Entity\User;
use Okapon\DoctrineSetTypeBundle\Tests\Fixtures\DBAL\Types\UserGroupType;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler {

	public function __construct(HttpUtils $httpUtils, array $options) {
		parent::__construct( $httpUtils, $options );
	}

	public function onAuthenticationSuccess( Request $request, TokenInterface $token ) {

		if( $request->isXmlHttpRequest() ) {
			$response = new JsonResponse( array( 'success' => true, 'username' => $token->getUsername() ) );
		} else {
			$response = parent::onAuthenticationSuccess( $request, $token );
		}
		return $response;
	}

}