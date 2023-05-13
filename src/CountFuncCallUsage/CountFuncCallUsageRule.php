<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<CollectedDataNode>
 */
final class CountFuncCallUsageRule implements Rule
{
    /**
     * @var string[]
     */
    private array $watchFuncCallNames;

    /**
     * @param array<string, int> $watchedFuncCalls
     */
    public function __construct(
        private readonly array $watchedFuncCalls
    )
    {
        $this->watchFuncCallNames = array_keys($this->watchedFuncCalls);
    }


    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $countFuncCallUsageRuleData = $node->get(FuncCallCollector::class);

        $funcCallCount = [];

        foreach ($countFuncCallUsageRuleData as $declarations) {
            foreach ($declarations as [$name, $line]) {

                if(!array_key_exists($name, $funcCallCount)) {
                    $funcCallCount[$name] = 0;
                }

                $funcCallCount[$name]++;
            }
        }

        return $this->generateErrors($funcCallCount);
    }

    /**
     * @param array<string, int> $funcCallCount
     * @return (string|RuleError)[] errors
     */
    private function generateErrors(array $funcCallCount): array
    {
        $errors = [];

        foreach ($this->watchFuncCallNames as $watchedFuncCall) {
            $callCount = $funcCallCount[$watchedFuncCall] ?? 0;

            $previousCount = $this->getPreviousUsageCount($watchedFuncCall);

            if($previousCount >= $callCount) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Function %s is called %d time(s), was called %s time(s) before.',
                    $watchedFuncCall,
                    $callCount,
                    $previousCount
                )
            )->file('index.php')->line(0)->build();
        }

        return $errors;
    }

    protected function getPreviousUsageCount(string $watchedFuncCall): int
    {
        return $this->watchedFuncCalls[$watchedFuncCall] ??0;
    }
}