<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Helper;

class ClassName
{
    public static function extractClassAndNamespace($className)
    {
        $offset = strpos($className, '\\');
        if ($offset === false) {
            return [$className, null];
        }

        return [
            substr($className, $offset + 1),
            substr($className, 0, $offset),
        ];
    }
}
