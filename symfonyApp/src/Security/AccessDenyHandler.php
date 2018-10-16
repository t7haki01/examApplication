<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 16/10/2018
 * Time: 10:37
 */

namespace App\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDenyHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException){

        $request->getSession()
            ->getFlashBag()
            ->add('warn',
                'You are not allowed to access');

        return new RedirectResponse('/student/main');
    }

}