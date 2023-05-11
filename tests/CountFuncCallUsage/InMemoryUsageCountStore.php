<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

final class InMemoryUsageCountStore implements UsageCountStore
{
    /**
     * @param array<string, int> $usageByFuncCall
     */
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

    public function storeCountFor(string $watchedFuncCall, int $callCount): void
    {
       $this->usageByFuncCall[$watchedFuncCall] =  $callCount;
    }
}