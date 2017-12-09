<?php

namespace AppBundle\Query\FacetBuilderVisitor;

use AppBundle\Query\Facet\FieldFacet;
use AppBundle\Query\FacetBuilder\FieldFacetBuilder;
use eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder;
use EzSystems\EzPlatformSolrSearchEngine\Query\FacetFieldVisitor;
use EzSystems\EzPlatformSolrSearchEngine\Query\FacetBuilderVisitor;

class FieldFacetBuilderVisitor extends FacetBuilderVisitor implements FacetFieldVisitor
{
    /**
     * {@inheritdoc}.
     */
    public function mapField($field, array $data, FacetBuilder $facetBuilder)
    {
        return new FieldFacet(
            array(
                'name' => $facetBuilder->name,
                'entries' => $this->mapData($data),
            )
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function canVisit(FacetBuilder $facetBuilder)
    {
        return $facetBuilder instanceof FieldFacetBuilder;
    }

    /**
     * {@inheritdoc}.
     */
    public function visitBuilder(FacetBuilder $facetBuilder, $fieldId)
    {
        return array(
            'facet.field' => "{!ex=dt key=${fieldId}}" . $facetBuilder->solrIdentifier,
            'f.' . $facetBuilder->solrIdentifier . '.facet.limit' => $facetBuilder->limit,
            'f.' . $facetBuilder->solrIdentifier . '.facet.mincount' => $facetBuilder->minCount,
        );
    }
}