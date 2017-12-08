<?php

namespace AppBundle\Service;

use Pagerfanta\Pagerfanta;
use AppBundle\Query\EZPlatformObjectSearchRecipe;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\Core\Pagination\Pagerfanta\LocationSearchAdapter;

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
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);

        return $pager;
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
     * @throws Exception
     */
    public function searchFacets(EZPlatformObjectSearchRecipe $recipe)
    {
        throw new \Exception("not implemented");
    }
}
