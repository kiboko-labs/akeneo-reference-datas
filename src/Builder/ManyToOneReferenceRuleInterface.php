<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Builder;

use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;

interface ManyToOneReferenceRuleInterface extends RuleInterface
{
    const TYPE = 'manyToOne';
}
