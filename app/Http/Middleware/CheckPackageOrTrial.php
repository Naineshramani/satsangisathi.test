<?php

namespace App\Http\Middleware;

class CheckPackageOrTrial extends CheckMemberPackage
{
    /**
     * Same as parent, except a member currently on their post-approval
     * trial is not blocked even without a package.
     */
    protected function isBlocked($user): bool
    {
        return parent::isBlocked($user) && !($user && $user->onTrial());
    }
}
