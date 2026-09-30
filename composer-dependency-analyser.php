<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

return new Configuration()
    ->addPathToExclude(__DIR__ . '/src/Service/DdrCrudAdmin/FieldRenderer/MillisecondsRendererProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Validator/Security/PasswordValidator.php')
    ->addPathToExclude(__DIR__ . '/src/Validator/FlexDate.php')
    ->addPathToExclude(__DIR__ . '/src/Validator/FlexDateValidator.php')
    ->addPathToExclude(__DIR__ . '/src/Command/User/EditCommand.php')
    ->addPathToExclude(__DIR__ . '/src/Controller/ValueResolver/IdEntityArgumentValueResolver.php')
    ->addPathToExclude(__DIR__ . '/src/Controller/ValueResolver/UuidEntityArgumentValueResolver.php')
    ->addPathToExclude(__DIR__ . '/src/DependencyInjection/DdrBridgeExtension.php')
    ->addPathToExclude(__DIR__ . '/src/Form/Type/FlexDateType.php')
    ->addPathToExclude(__DIR__ . '/src/Menu/DdrCrudAdminMenuBuilder.php')
    ->addPathToExclude(__DIR__ . '/src/Menu/MoreDropdownTrait.php')
    ->addPathToExclude(__DIR__ . '/src/Menu/NavbarBuilder.php')
    ->addPathToExclude(__DIR__ . '/src/Repository/User/UserRepository.php')
    ->addPathToExclude(__DIR__ . '/src/Service/DdrCrudAdmin/DontdrinkandrootTemplateProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Service/DdrCrudAdmin/FieldRenderer/FontAwesome5BooleanRendererProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Service/DdrCrudAdmin/FieldRenderer/InstantFieldRendererProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Service/DdrCrudAdmin/UuidEntityIdProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Service/DdrCrudAdmin/UuidEntityItemProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Service/Mail/MailService.php')
    ->addPathToExclude(__DIR__ . '/src/Validator/Security/Password.php');


