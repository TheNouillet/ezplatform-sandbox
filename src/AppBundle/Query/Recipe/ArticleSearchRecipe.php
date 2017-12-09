<?php

namespace AppBundle\Query\Recipe;

use AppBundle\Query\Recipe\ChildrenSearchRecipe;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator;
use AppBundle\Query\FacetBuilder\FieldFacetBuilder;

class ArticleSearchRecipe extends ChildrenSearchRecipe
{
    /** @var boolean $onlyPremium */
    public $onlyPremium;

    public function __construct($parentLocationId, $onlyPremium = false)
    {
        parent::__construct($parentLocationId, "article");
        $this->onlyPremium = $onlyPremium;
    }

    public function getCriterions()
    {
        $criterions = parent::getCriterions();
        if ($this->onlyPremium) {
            $criterions[] = new Criterion\Field("is_premium", Operator::EQ, true);
        }
        return $criterions;
    }

    public function getFacetBuilders(Type $var = null)
    {
        return array(
            new FieldFacetBuilder(array(
                "name" => "pokemoun",
                "minCount" => 0,
                "solrIdentifier" => "article_pokemoun_value_s"
            ))
        );
    }
}
