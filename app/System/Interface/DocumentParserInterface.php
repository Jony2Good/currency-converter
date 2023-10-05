<?php

namespace App\System\Interface;

interface DocumentParserInterface
{
    /**
     * @param mixed $file
     * @param string $innerCharCode
     * @return array<string>
     */
    public function readData(mixed $file, string $innerCharCode): array;

}
