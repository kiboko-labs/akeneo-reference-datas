<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer;

interface TransformerInterface
{
    /**
     * @param object $object
     * @param string $desiredType
     * @return object
     */
    public function transform($object, $desiredType);

    /**
     * @param object $object
     * @param string $desiredType
     * @return bool
     */
    public function supportsTransformation($object, $desiredType);
}
