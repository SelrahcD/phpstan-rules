<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

interface UsageCountStore
{
    public function countFor(string $funcCall): int;
}