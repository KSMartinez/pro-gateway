<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;


/**
 * Check if a user X has the role X
 */
class GrantedService
{
    /**
     * @param AccessDecisionManagerInterface $accessDecisionManager
     */
    public function __construct(private AccessDecisionManagerInterface $accessDecisionManager)
    {
    }

    /**
     * @param User $user
     * @param string|string[] $attributes
     * @param mixed $object
     * @return bool
     */
    public function isGranted(User $user, array|string $attributes, mixed $object = null): bool
    {
        if (!is_array($attributes)) {
            $attributes = [$attributes];
        }
        $token = new UsernamePasswordToken($user, 'none', $user->getRoles());
        return ($this->accessDecisionManager->decide($token, $attributes, $object));
    }
}