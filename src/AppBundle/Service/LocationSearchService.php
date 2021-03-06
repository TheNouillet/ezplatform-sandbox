<?php

namespace AppBundle\Service;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\AdapterInterface;
use eZ\Publish\API\Repository\Repository;
use AppBundle\Query\Recipe\EZPlatformObjectSearchRecipe;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;
use eZ\Publish\Core\Pagination\Pagerfanta\LocationSearchAdapter;
use eZ\Publish\Core\Pagination\Pagerfanta\LocationSearchHitAdapter;

class LocationSearchService
{
    /** @var Repository $repository */
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * Prépare la requête depuis une recette
     *
     * @param EZPlatformObjectSearchRecipe $recipe
     * @return LocationQuery
     */
    protected function prepareLocationQuery(EZPlatformObjectSearchRecipe $recipe)
    {
        $query = new LocationQuery();
        $criterions = $recipe->getCriterions();
        if (!empty($criterions)) {
            $query->filter = new Criterion\LogicalAnd($criterions);
        }

        $sortClauses = $recipe->getSortClauses();
        if (!empty($sortClauses)) {
            $query->sortClauses = $sortClauses;
        }

        $facetBuilders = $recipe->getFacetBuilders();
        if (!empty($facetBuilders)) {
            $query->facetBuilders = $facetBuilders;
        }

        $limit = $recipe->getLimit();
        if ($limit) {
            $query->limit = $limit;
        }

        $offset = $recipe->getOffset();
        if ($offset) {
            $query->offset = $offset;
        }

        return $query;
    }

    /**
     * Prépare la pagination selon un adapter
     *
     * @param AdapterInterface $adapter
     * @param integer $page
     * @param integer $limit
     * @return Pagerfanta
     */
    protected function preparePagination($adapter, $page, $limit)
    {
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);
        return $pager;
    }

    /**
     * Recherche des locations selon une recette
     *
     * @param EZPlatformObjectSearchRecipe $recipe
     * @return Location[]|null
     */
    public function search(EZPlatformObjectSearchRecipe $recipe)
    {
        $query = $this->prepareLocationQuery($recipe);
        $searchResult = $this->repository->getSearchService()->findLocations($query);
        if (count($searchResult->searchHits)) {
            return \array_map(function ($searchHit) {
                return $searchHit->valueObject;
            }, $searchResult->searchHits);
        } else {
            return null;
        }
    }

    /**
     * Renvoie les SearchHits selon une recette
     *
     * @param EZPlatformObjectSearchRecipe $recipe
     * @return SearchHit
     */
    public function searchHits(EZPlatformObjectSearchRecipe $recipe)
    {
        $query = $this->prepareLocationQuery($recipe);
        $searchResult = $this->repository->getSearchService()->findLocations($query);
        return $searchResult->searchHits;
    }

    /**
     * Recherche paginée des locations selon une recette
     *
     * @param EZPlatformObjectSearchRecipe $recipe
     * @param integer $page
     * @param integer $limit
     * @return Pagerfanta
     */
    public function searchPaginated(EZPlatformObjectSearchRecipe $recipe, $page, $limit)
    {
        $query = $this->prepareLocationQuery($recipe);
        $adapter = new LocationSearchAdapter($query, $this->repository->getSearchService());
        return $this->preparePagination($adapter, $page, $limit);
    }

    /**
     * Recherche paginée des SearchHits selon une recette
     *
     * @param EZPlatformObjectSearchRecipe $recipe
     * @param integer $page
     * @param integer $limit
     * @return Pagerfanta
     */
    public function searchHitsPaginated(EZPlatformObjectSearchRecipe $recipe, $page, $limit)
    {
        $query = $this->prepareLocationQuery($recipe);
        $adapter = new LocationSearchHitAdapter($query, $this->repository->getSearchService());
        return $this->preparePagination($adapter, $page, $limit);
    }

    /**
     * Récupère le nombre de location selon une recette
     *
     * @param EZPlatformObjectSearchRecipe $recipe
     * @return integer
     */
    public function searchCount(EZPlatformObjectSearchRecipe $recipe)
    {
        $query = $this->prepareLocationQuery($recipe);
        $searchResult = $this->repository->getSearchService()->findLocations($query);
        return $searchResult->totalCount;
    }

    /**
     * Récupère les facets d'une recherche selon une recette
     *
     * @param EZPlatformObjectSearchRecipe $recipe
     * @return array
     */
    public function searchFacets(EZPlatformObjectSearchRecipe $recipe)
    {
        $query = $this->prepareLocationQuery($recipe);
        $searchResult = $this->repository->getSearchService()->findLocations($query);
        return $searchResult->facets;
    }
}
