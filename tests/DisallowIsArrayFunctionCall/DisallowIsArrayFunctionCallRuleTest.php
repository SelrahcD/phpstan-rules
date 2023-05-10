<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\DisallowIsArrayFunctionCall;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<DisallowIsArrayFunctionCallRule>
 */
final class DisallowIsArrayFunctionCallRuleTest extends RuleTestCase
{

    protected function getRule(): Rule
    {
        return new DisallowIsArrayFunctionCallRule();
    }

    /**
     * @test
     */
    public function do_not_show_error_when_is_array_is_not_called(): void
    {
        $this->analyse([__DIR__ . '/data/IsArrayIsNotCalled/Example.php'], []);
    }

    /**
     * @test
     */
    public function show_an_error_when_is_array_is_called(): void
    {
        $this->analyse([__DIR__ . '/data/IsArrayIsCalled/Example.php'], [
            [
                <<<'EOE'
            The usage of is_array is disallowed.
            EOE,
                3
            ]
        ]);
    }
}
