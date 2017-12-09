<?php

namespace AppBundle\Query\FacetBuilder;

use eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder;

class FieldFacetBuilder extends FacetBuilder
{
    /** @var string $solrIdentifier */
    public $solrIdentifier;
}