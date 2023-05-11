<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

use PHPUnit\Framework\TestCase;

class JsonFileUsageCountStoreTest extends TestCase
{
    private const FILE_NAME = '/tmp/CountFunCallUsage.json';

    protected function tearDown(): void
    {
        unlink(self::FILE_NAME);
    }


    /**
     * @test
     */
    public function returns_0_when_no_value_was_stored_for_funcCall(): void
    {
        $jsonFileUsageCountStore = new JsonFileUsageCountStore(self::FILE_NAME);

        $this->assertEquals(0, $jsonFileUsageCountStore->countFor('\bla'));
    }

    /**
     * @test
     */
    public function returns_previously_stored_count(): void
    {
        $jsonFileUsageCountStore = new JsonFileUsageCountStore(self::FILE_NAME);

        $jsonFileUsageCountStore->storeCountFor('\plop', 17);

        $this->assertEquals(17, $jsonFileUsageCountStore->countFor('\plop'));
    }
}
