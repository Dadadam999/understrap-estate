<?php

namespace Vendor\UnderstrapEstate\Interface;

interface MetaBoxInterface
{
    public function render($post): void;
    public function callback($postId): void;
}
