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
        $jsonData = file_get_contents($this->fileName);

        if($jsonData === false) {
            $decodedJson = [];
        }
        else {
            $decodedJson = json_decode($jsonData, true);
        }

        if(!array_key_exists($funcCall, $decodedJson)) {
            return 0;
        }

        return $decodedJson[$funcCall];
    }

    public function storeCountFor(string $watchedFuncCall, int $callCount): void
    {
        $jsonData = file_get_contents($this->fileName);

        if($jsonData === false) {
            $decodedJson = [];
        }
        else {
            $decodedJson = json_decode($jsonData, true);
        }

        $decodedJson[$watchedFuncCall] = $callCount;

        $encodedJson = json_encode($decodedJson);

        file_put_contents($this->fileName, $encodedJson);
    }
}