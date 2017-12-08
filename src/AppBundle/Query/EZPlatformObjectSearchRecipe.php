<?php

namespace AppBundle\Query;

use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;

class EZPlatformObjectSearchRecipe
{
    /** @var Criterion[] $criterion */
    public $criterions;

    /** @var SortClause[] $sortClauses */
    public $sortClauses;

    /** @var FacetBuilder[] $facetBuilders*/
    public $facetBuilders;

    /** @var integer $limit */
    public $limit;

    /** @var integer $offset */
    public $offset;

    public function __construct()
    {
        $this->criterions = array();
        $this->sortClauses = array();
        $this->facetBuilders = array();
        $this->limit = false;
        $this->offset = false;
    }

    public function getCriterions()
    {
        return $this->criterions;
    }

    public function getSortClauses()
    {
        return $this->sortClauses;
    }

    public function getFacetBuilders()
    {
        return $this->facetBuilders;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }
}
