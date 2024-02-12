<?php

namespace Dontdrinkandroot\BridgeBundle\Request;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait RedirectToRefererTrait
{
    public function getRedirectToRefererResponse(Request $request): ?Response
    {
        $referer = $request->headers->get('referer');
        if (null === $referer) {
            return null;
        }

        $refererHost = parse_url($referer, PHP_URL_HOST);
        $requestHost = $request->getHost();
        if ($refererHost !== $requestHost) {
            return null;
        }

        return new RedirectResponse($referer);
    }
}
