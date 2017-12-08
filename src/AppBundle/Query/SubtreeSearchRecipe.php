<?php

namespace AppBundle\Query;

use AppBundle\Query\EZPlatformObjectSearchRecipe;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;


class SubtreeSearchRecipe extends EZPlatformObjectSearchRecipe
{
    /** @var Location $rootLocation */
    protected $rootLocation;

    /** @var string|string[] $contentTypeIdentifiers */
    protected $contentTypeIdentifiers;

    public function __construct($rootLocation, $contentTypeIdentifiers) {
        $this->rootLocation = $rootLocation;
        $this->contentTypeIdentifiers = $contentTypeIdentifiers;
    }

    public function getCriterions()
    {
        return array(
            new Criterion\Subtree($this->rootLocation->pathString),
            new Criterion\ContentTypeIdentifier($this->contentTypeIdentifiers)
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