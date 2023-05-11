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
    public static function getAdditionalConfigFiles(): array
    {
        return array_merge(
            parent::getAdditionalConfigFiles(),
            [
                __DIR__ . '/data/config.neon',
            ]
        );

    }

    protected function getRule(): Rule
    {
        return new CountFuncCallUsageRule(['\is_array', '\is_dir']);
    }

    protected function getCollectors(): array
    {
        return [
            new FuncCallCollector(),
        ];
    }


    /**
     * @test
     */
    public function do_not_show_error_when_is_array_is_not_called(): void
    {
        $this->analyse([__DIR__ . '/data/NoUsageOfIsArray/Example.php'], []);
    }

    /**
     * @test
     */
    public function show_error_when_is_array_is_called_once(): void
    {
        $this->analyse([__DIR__ . '/data/OneUsageOfIsArray/Example.php'],  [
            [
            <<<'EOE'
            Function \is_array is called 1 time(s).
            EOE, 0
            ],
        ]);
    }

    /**
     * @test
     */
    public function show_error_when_is_array_is_called_more_than_once(): void
    {
        $this->analyse([__DIR__ . '/data/TwoUsagesOfIsArray/Example.php'],  [
            [
                <<<'EOE'
            Function \is_array is called 2 time(s).
            EOE, 0
            ],
        ]);
    }

    /**
     * @test
     */
    public function do_not_count_others_FuncCall_than_in_array(): void
    {
        $this->analyse([__DIR__ . '/data/TwoUsagesOfIsArrayAndUsageOfOthersFuncCalls/Example.php'],  [
            [
                <<<'EOE'
            Function \is_array is called 2 time(s).
            EOE, 0
            ],
        ]);
    }

}