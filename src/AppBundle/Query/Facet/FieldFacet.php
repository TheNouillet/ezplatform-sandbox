<?php

namespace AppBundle\Query\Facet;

use eZ\Publish\API\Repository\Values\Content\Search\Facet;

class FieldFacet extends Facet
{
    /** @var array $entries */
    public $entries;
}