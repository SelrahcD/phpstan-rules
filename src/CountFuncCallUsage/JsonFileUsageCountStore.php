<?php

declare(strict_types=1);

namespace Selrahcd\PhpstanRules\CountFuncCallUsage;

final class JsonFileUsageCountStore implements UsageCountStore
{
    private string $fileName;

    public function __construct()
    {
        $this->fileName = __DIR__ . '/CountFunCallUsage.json';
    }

    public function countFor(string $funcCall): int
    {
        $decodedJson = $this->readDataFromFile();

        if(!array_key_exists($funcCall, $decodedJson)) {
            return 0;
        }

        return $decodedJson[$funcCall];
    }

    public function storeCountFor(string $watchedFuncCall, int $callCount): void
    {
        $decodedJson = $this->readDataFromFile();

        $decodedJson[$watchedFuncCall] = $callCount;

        $this->storeDataInFile($decodedJson);
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

    /**
     * @param array<string, int> $decodedJson
     */
    private function storeDataInFile(array $decodedJson): void
    {
        $encodedJson = json_encode($decodedJson);

        file_put_contents($this->fileName, $encodedJson);
    }
}