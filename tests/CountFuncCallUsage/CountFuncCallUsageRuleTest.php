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

    public static function getAdditionalConfigFiles(): array
    {
        return array_merge(parent::getAdditionalConfigFiles(), [
            __DIR__ . '/../../extension.neon',
            __DIR__ . '/data/config.neon',
        ]);
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