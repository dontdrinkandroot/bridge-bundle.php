<?php

namespace Dontdrinkandroot\BridgeBundle\Model\Container;

class RoutePath
{
    public const string HEALTH = '/_health';
    public const string SECURITY_LOGIN = '/login';
    public const string SECURITY_LOGIN_LINK_REQUEST = '/security/login-link/request';
    public const string SECURITY_LOGIN_LINK_CHECK = '/security/login-link/check';
}
