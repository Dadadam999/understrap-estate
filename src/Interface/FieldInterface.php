<?php

namespace Vendor\UnderstrapEstate\Interface;

interface FieldInterface
{
    public function renderLabel(): string;
    public function renderField(): string;
}
