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
     * @param string[] $watchedFuncCalls
     */
    public function __construct(
        private readonly UsageCountStore $usageCountStore,
        private readonly array $watchedFuncCalls)
    {
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

                if(!in_array($name, $this->watchedFuncCalls, true)) {
                    continue;
                }

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

        foreach ($this->watchedFuncCalls as $watchedFuncCall) {
            $callCount = $funcCallCount[$watchedFuncCall];

            if (!array_key_exists($watchedFuncCall, $funcCallCount)) {
                continue;
            }

            $previousCount = $this->usageCountStore->countFor($watchedFuncCall);

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
}