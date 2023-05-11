<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

final class InMemoryUsageCountStore implements UsageCountStore
{

    public function countFor(string $funcCall): int
    {
        throw new \Exception('countFor() not implemented yet');
    }
}