<?php

namespace AppBundle\Service;

use eZ\Publish\API\Repository\Repository;
use AppBundle\Query\LocationSearchRecipe;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\Core\Pagination\Pagerfanta\LocationSearchAdapter;
use Pagerfanta\Pagerfanta;

class SearchService
{
    /** @var Repository $repository */
    protected $repository;

    public function __construct($repository) {
        $this->repository = $repository;
    }

    /**
     * Prépare la requête depuis une recette
     *
     * @param LocationSearchRecipe $recipe
     * @return LocationQuery
     */
    protected function prepareLocationQuery(LocationSearchRecipe $recipe)
    {
        $query = new LocationQuery();
        $criterions = $recipe->getCriterions();
        if(! empty($criterions)) {
            $query->filter = new Criterion\LogicalAnd($criterions);
        }

        $sortClauses = $recipe->getSortClauses();
        if(! empty($sortClauses)) {
            $query->sortClauses = $sortClauses;
        }

        $facetBuilders = $recipe->getFacetBuilders();
        if(! empty($facetBuilders)) {
            $query->facetBuilders = $facetBuilders;
        }

        $limit = $recipe->getLimit();
        if($limit) {
            $query->limit = $limit;
        }

        $offset = $recipe->getOffset();
        if($offset) {
            $query->offset = $offset;
        }

        return $query;
    }

    /**
     * Recherche des locations selon une recette
     *
     * @param LocationSearchRecipe $recipe
     * @return Location[]|null
     */
    public function searchLocations(LocationSearchRecipe $recipe)
    {
        $query = $this->prepareLocationQuery($recipe);
        $searchResult = $this->repository->getSearchService()->findLocations($query);
        if(count($searchResult->searchHits)) {
            $locations = \array_map(function($searchHit){
                return $searchHit->valueObject;
            }, $searchResult->searchHits);
            return $locations;
        } else {
            return null;
        }
    }

    /**
     * Recherche paginée des locations selon une recette
     *
     * @param LocationSearchRecipe $recipe
     * @param integer $page
     * @param integer $limit
     * @return Pagerfanta
     */
    public function searchPaginatedLocations(LocationSearchRecipe $recipe, $page, $limit)
    {
        $query = $this->prepareLocationQuery($recipe);
        $adapter = new LocationSearchAdapter($query, $this->repository->getSearchService());
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);

        return $pager;
    }

    /**
     * Récupère le nombre de location selon une recette
     *
     * @param LocationSearchRecipe $recipe
     * @return integer
     */
    public function searchLocationCount(LocationSearchRecipe $recipe)
    {
        $query = $this->prepareLocationQuery($recipe);
        $searchResult = $this->repository->getSearchService()->findLocations($query);
        return $searchResult->totalCount;
    }

    // TODO: Facets
    // public function FunctionName(Type $var = null)
    // {
    //     # code...
    // }
}