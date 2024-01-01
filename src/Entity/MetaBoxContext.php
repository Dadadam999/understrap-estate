<?php

namespace Vendor\UnderstrapEstate\Entity;

enum MetaBoxContext: string
{
    case NORMAL = 'normal';
    case ADVANCED = 'advanced';
    case SIDE = 'side';
}