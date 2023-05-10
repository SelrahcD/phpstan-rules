<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;

/**
 * @implements Collector<Node\Expr\FuncCall, array{string, int}>
 */
final class FuncCallCollector implements Collector
{

    public function __construct()
    {
    }

    public function getNodeType(): string
    {
       return Node\Expr\FuncCall::class;
    }

    public function processNode(Node $node, Scope $scope)
    {
        $nodeName = $node->name;

        if (!$nodeName instanceof Node\Name\FullyQualified) {
            return null;
        }

        return [$nodeName->toCodeString(), $node->getLine()];
    }
}