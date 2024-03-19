<?php

namespace Dontdrinkandroot\BridgeBundle\Model\Container;

class RouteName
{
    public const string BUNDLE_PREFIX = 'ddr.bridge';
    public const string HEALTH = self::BUNDLE_PREFIX . '.health';
    public const string SECURITY_LOGIN = self::BUNDLE_PREFIX . '.security.login';
    public const string SECURITY_LOGOUT = '_logout_main';
    public const string SECURITY_RESET_PASSWORD = self::BUNDLE_PREFIX . '.security.reset_password';
    public const string SECURITY_LOGIN_LINK_CHECK = self::BUNDLE_PREFIX . '.security.login_link.check';
    public const string SECURITY_LOGIN_LINK_REQUEST = self::BUNDLE_PREFIX . '.security.login_link.request';
}
