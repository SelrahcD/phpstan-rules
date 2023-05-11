<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

final class InMemoryUsageCountStore implements UsageCountStore
{
    public function __construct(
        public array $usageByFuncCall = [])
    {
    }

    public function countFor(string $funcCall): int
    {
        if(array_key_exists($funcCall, $this->usageByFuncCall)) {
            return $this->usageByFuncCall[$funcCall];
        }

        return 0;
    }
}