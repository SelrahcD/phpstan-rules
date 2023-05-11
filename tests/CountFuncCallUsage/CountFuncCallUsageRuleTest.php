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
        '\notCountedYet',
        '\watchedButNotUsed'
    ];

    private UsageCountStore&InMemoryUsageCountStore $usageCountStore;

    protected function setUp(): void
    {
        $this->usageCountStore = new InMemoryUsageCountStore([
            '\somethingUsedTwice' => 2,
            '\somethingUsedOnce' => 1,
        ]);
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

    /**
     * @test
     */
    public function stores_0_usage_count_if_watched_funcCall_is_not_used(): void
    {
        $this->analyse([__DIR__ . '/data/WatchedButNotUsed/Example.php'], [
            [
                <<<'EOE'
            Function \notCountedYet is called 2 time(s), was called 0 time(s) before.
            EOE, 0
            ],
        ]);

        $this->assertEquals(0, $this->usageCountStore->countFor('\watchedButNoUsed'));
    }

    /**
     * @test
     */
    public function stores_funcCall_usage_counts(): void
    {
        $this->analyse([__DIR__ . '/data/NotCountedYet/Example.php'], [
            [
                <<<'EOE'
            Function \notCountedYet is called 2 time(s), was called 0 time(s) before.
            EOE, 0
            ],
        ]);

        $this->assertEquals([
            '\somethingUsedTwice' => 1,
            '\somethingUsedOnce' => 1,
            '\notCountedYet' => 2,
            '\watchedButNotUsed' => 0,
        ], $this->usageCountStore->usageByFuncCall);
    }
}