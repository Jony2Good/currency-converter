<?php

namespace App\System\Interface;

interface DocumentParserInterface
{
    public function readData(mixed $file, string $innerCharCode): array;

}