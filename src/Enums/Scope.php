<?php

namespace Freemius\SDK\Enums;

enum Scope: string
{
    case DEVELOPER = 'developers';
    case PLUGIN = 'plugins';
    case INSTALL = 'installs';
    case USER = 'users';
    case APP = 'apps';
    case STORE = 'stores';
}