<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<CountFuncCallUsageRule>
 */
final class CountFuncCallUsageRuleStoresTheUsageCountTest extends RuleTestCase
{
    const WATCHED_FUNC_CALLS = [
        '\somethingUsedTwice',
        '\somethingUsedOnce',
    ];

    private UsageCountStore&InMemoryUsageCountStore $usageCountStore;

    protected function setUp(): void
    {
        $this->usageCountStore = new InMemoryUsageCountStore();
    }


    protected function getRule(): Rule
    {
        return new CountFuncCallUsageRule(
            $this->usageCountStore,
            self::WATCHED_FUNC_CALLS
        );
    }

    protected function getCollectors(): array
    {
        return [
            new FuncCallCollector(self::WATCHED_FUNC_CALLS),
        ];
    }

    /**
     * @test
     */
    public function stores_funcCall_usage_counts(): void
    {
        $this->analyse([__DIR__ . '/data/UsageCountStayedTheSame/Example.php'], [
            [
                <<<'EOE'
            Function \somethingUsedTwice is called 2 time(s), was called 0 time(s) before.
            EOE, 0
            ],
            [
                <<<'EOE'
            Function \somethingUsedOnce is called 1 time(s), was called 0 time(s) before.
            EOE, 0
            ],
        ]);

        $this->assertEquals([
            '\somethingUsedTwice' => 2,
            '\somethingUsedOnce' => 1
        ], $this->usageCountStore->usageByFuncCall);
    }
}