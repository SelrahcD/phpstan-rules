<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
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
        private array $watchedFuncCalls)
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

        $errors = [];

        foreach ($this->watchedFuncCalls as $checkedFuncCall) {
            $callCount = $funcCallCount[$checkedFuncCall];

            if(!array_key_exists($checkedFuncCall, $funcCallCount)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(sprintf(
                'Function %s is called %d time(s).',
                $checkedFuncCall,
                $callCount,
            ))->file('index.php')->line(0)->build();
        }

        return $errors;
    }
}