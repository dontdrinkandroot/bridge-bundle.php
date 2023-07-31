<?php

namespace Dontdrinkandroot\BridgeBundle\Model;

class Route
{
    const BUNDLE_PREFIX = 'ddr.bridge';
    public const HEALTH = self::BUNDLE_PREFIX . '.health';
    public const SECURITY_LOGIN = self::BUNDLE_PREFIX . '.security.login';
    public const SECURITY_LOGOUT = self::BUNDLE_PREFIX . '.security.logout';
    const SECURITY_RESET_PASSWORD = self::BUNDLE_PREFIX . '.security.reset_password';
}
