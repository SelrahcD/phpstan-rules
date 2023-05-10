<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\DisallowIsArrayFunctionCall;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\FuncCall>
 */
final class DisallowIsArrayFunctionCallRule implements Rule
{

    public function getNodeType(): string
    {
        return Node\Expr\FuncCall::class;
    }

    /**
     * @param Node\Expr\FuncCall $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $nodeName = $node->name;

        if (!$nodeName instanceof Node\Name\FullyQualified) {
            return [];
        }

        if ($nodeName->toCodeString() === '\is_array') {
            return ['The usage of is_array is disallowed.'];
        }

        return [];
    }
}