<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

use PHPUnit\Framework\TestCase;

class JsonFileUsageCountStoreTest extends TestCase
{
    protected function tearDown(): void
    {
        unlink('/Users/charles/Workspace/perso/phpstan-rules/src/CountFuncCallUsage/CountFunCallUsage.json');
    }


    /**
     * @test
     */
    public function returns_0_when_no_value_was_stored_for_funcCall(): void
    {
        $jsonFileUsageCountStore = new JsonFileUsageCountStore();

        $this->assertEquals(0, $jsonFileUsageCountStore->countFor('\bla'));
    }

    /**
     * @test
     */
    public function returns_previously_stored_count(): void
    {
        $jsonFileUsageCountStore = new JsonFileUsageCountStore();

        $jsonFileUsageCountStore->storeCountFor('\plop', 17);

        $this->assertEquals(17, $jsonFileUsageCountStore->countFor('\plop'));
    }
}
