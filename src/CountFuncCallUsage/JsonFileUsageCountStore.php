<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

final class JsonFileUsageCountStore implements UsageCountStore
{
    public function __construct(private readonly string $fileName,
    ) {
    }

    public function countFor(string $funcCall): int
    {
        $decodedJson = $this->readDataFromFile();

        if (!array_key_exists($funcCall, $decodedJson)) {
            return 0;
        }

        return $decodedJson[$funcCall];
    }

    /**
     * @return array<string, int>
     */
    private function readDataFromFile(): array
    {
        $jsonData = file_get_contents($this->fileName);

        if ($jsonData === false) {
            return [];
        }

        $decodedData = json_decode($jsonData, true);

        assert(is_array($decodedData));

        return $decodedData;
    }
}