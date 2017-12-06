<?php

namespace AppBundle\Controller;

use AppBundle\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Query\LocationSearchRecipe;
use AppBundle\Query\ChildrenLocationSearchRecipe;
use AppBundle\Query\ArticleSearchRecipe;

class DefaultController extends Controller
{
    public function folderAction($locationId, $viewType, $layout = false, array $params = array())
    {
        /** @var SearchService $search */
        $search = $this->get("search_service");

        $locations = $search->searchLocations(new ArticleSearchRecipe($locationId));
        $pager = $search->searchPaginatedLocations(new ArticleSearchRecipe($locationId), 2, 2);
        $resultCount = $search->searchLocationCount(new ArticleSearchRecipe($locationId));
        $params += array(
            "locations" => $locations,
            "pager" => $pager,
            "resultCount" => $resultCount
        );

        return $this->get( 'ez_content' )->viewLocation( $locationId, $viewType, $layout, $params );
    }

    public function articleAction($locationId, $viewType, $layout = false, array $params = array())
    {
        return $this->get( 'ez_content' )->viewLocation( $locationId, $viewType, $layout, $params );
    }
}