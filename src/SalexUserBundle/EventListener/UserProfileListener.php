<?php
/**
 * Created by PhpStorm.
 * User: SharfazSairaz
 * Date: 11/04/2016
 * Time: 12:02
 */

namespace SalexUserBundle\EventListener;


use Avanzu\AdminThemeBundle\Event\ShowUserEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserProfileListener
{
    private $token;

    public function __construct(TokenStorage $token)
    {
        $this->token = $token;//add
    }
    public function onShowUser(ShowUserEvent $event)
    {
        $user = $this->token->getToken()->getUser();
        $event->setUser($user);
    }
}