<?php

namespace AppBundle\Query;

use AppBundle\Query\EZPlatformObjectSearchRecipe;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;

class ChildrenSearchRecipe extends EZPlatformObjectSearchRecipe
{
    /** @var integer $parentLocationId */
    public $parentLocationId;

    /** @var string[]|string $contentTypeIdentifiers */
    public $contentTypeIdentifiers;

    public function __construct($parentLocationId, $contentTypeIdentifiers)
    {
        parent::__construct();
        $this->parentLocationId = $parentLocationId;
        $this->contentTypeIdentifiers = $contentTypeIdentifiers;
    }

    public function getCriterions()
    {
        return array(
            new Criterion\ParentLocationId($this->parentLocationId),
            new Criterion\ContentTypeIdentifier($this->contentTypeIdentifiers),
        );
    }

    public function getSortClauses()
    {
        return array(
            new SortClause\Location\Priority(),
            new SortClause\ContentName(),
        );
    }
}
