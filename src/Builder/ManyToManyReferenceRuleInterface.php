<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Builder;

use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;

interface ManyToManyReferenceRuleInterface extends RuleInterface
{
    const TYPE = 'manyToMany';
}
