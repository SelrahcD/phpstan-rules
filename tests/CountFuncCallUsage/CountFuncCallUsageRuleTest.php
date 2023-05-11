<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<CountFuncCallUsageRule>
 */
final class CountFuncCallUsageRuleTest extends RuleTestCase
{
    const WATCHED_FUNC_CALLS = [
        '\somethingUsedTwice',
        '\somethingUsedOnce',
    ];

    protected function getRule(): Rule
    {
        return new CountFuncCallUsageRule(
            new class implements UsageCountStore {

                const USAGE_COUNTS = [
                    '\somethingUsedTwice' => 2,
                    '\somethingUsedOnce' => 1,
                ];

                public function countFor(string $funcCall): int
                {
                    if(array_key_exists($funcCall, self::USAGE_COUNTS)) {
                        return self::USAGE_COUNTS[$funcCall];
                    }

                    return 0;
                }
            },
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
    public function do_not_show_error_if_func_call_usage_count_didnt_increase(): void
    {
        $this->analyse([__DIR__ . '/data/UsageCountStayedTheSame/Example.php'], []);
    }

    /**
     * @test
     */
    public function do_not_show_error_if_func_call_usage_count_decreased(): void
    {
        $this->analyse([__DIR__ . '/data/UsageCountDecreased/Example.php'], []);
    }

    /**
     * @test
     */
    public function show_error_when_usage_count_increased(): void
    {
        $this->analyse([__DIR__ . '/data/UsageCountIncreased/Example.php'],  [
            [
                <<<'EOE'
            Function \somethingUsedOnce is called 2 time(s), was called 1 time(s) before.
            EOE, 0
            ],
        ]);
    }
}