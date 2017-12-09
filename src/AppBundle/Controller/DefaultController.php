<?php

namespace AppBundle\Controller;

use AppBundle\Query\Recipe\ArticleSearchRecipe;
use AppBundle\Query\Recipe\SubtreeSearchRecipe;
use AppBundle\Query\Recipe\ChildrenSearchRecipe;
use AppBundle\Service\LocationSearchService;
use eZ\Bundle\EzPublishCoreBundle\Controller;
use AppBundle\Query\Recipe\EZPlatformObjectSearchRecipe;

class DefaultController extends Controller
{
    public function folderAction($locationId, $viewType, $layout = false, array $params = array())
    {
        /** @var LocationSearchService $search */
        $search = $this->get("search.location");

        $locations = $search->search(new ArticleSearchRecipe($locationId));
        $pager = $search->searchPaginated(new ArticleSearchRecipe($locationId), 1, 2);
        $resultCount = $search->searchCount(new ArticleSearchRecipe($locationId));
        $facets = $search->searchFacets(new ArticleSearchRecipe($locationId));

        $location = $this->getRepository()->getLocationService()->loadLocation($locationId);
        $subtree = $search->search(new SubtreeSearchRecipe($location, "article"));

        $params += array(
            "locations" => $locations,
            "locationPager" => $pager,
            "locationResultCount" => $resultCount,
            "facets" => $facets,
            "subtree" => $subtree
        );

        return $this->get( 'ez_content' )->viewLocation( $locationId, $viewType, $layout, $params );
    }

    public function articleAction($locationId, $viewType, $layout = false, array $params = array())
    {
        return $this->get( 'ez_content' )->viewLocation( $locationId, $viewType, $layout, $params );
    }
}