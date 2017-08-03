<?php
// src/SmartCity/CoreBundle/Services/AuthService.php
namespace SmartCity\CoreBundle\Services;

use SmartCity\UserBundle\Entity\User;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Class AuthService
 * @package SmartCity\CoreBundle\Services
 */
class AuthService extends ContainerAware
{
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * Logins the user manually in the system
     *
     * @param  User $user
     * @return Boolean
     */
    public function loginManually(User $user, Request $request, $firewall = 'panel')
    {
        $providerKey = 'main';
        $firewallName = $firewall;
        $unauthenticatedToken = new UsernamePasswordToken($user, $user->getPassword(), $providerKey, $user->getRoles());
        //$authenticatedToken = $this->get('security.authentication.manager')->authenticate($unauthenticatedToken); // TODO: check it later
        $authenticatedToken = $unauthenticatedToken;
        $this->container->get("security.token_storage")->setToken($authenticatedToken); // now the user is logged in

        // Fire the login event
        // Logging the user in above the way we do it doesn't do this automatically
        $event = new InteractiveLoginEvent($request, $authenticatedToken);
        $this->container->get("event_dispatcher")->dispatch("security.authentication", $event); // interactive_login changed to authentication
        $this->container->get('event_dispatcher')->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);
        $this->container->get('event_dispatcher')->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($authenticatedToken) );

        $this->container->get('session')->set('_security_' . $firewallName, serialize($authenticatedToken));

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return true;
    }
}