<?php

namespace AppBundle\Query;

use AppBundle\Query\ChildrenLocationSearchRecipe;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator;


class ArticleSearchRecipe extends ChildrenLocationSearchRecipe
{
    /** @var boolean $onlyPremium */
    public $onlyPremium;

    public function __construct($parentLocationId, $onlyPremium = false) {
        parent::__construct($parentLocationId, "article");
        $this->onlyPremium = $onlyPremium;
    }

    public function getCriterions()
    {
        $criterions = parent::getCriterions();
        if($this->onlyPremium) {
            $criterions[] = new Criterion\Field("is_premium", Operator::EQ, true);
        }
        return $criterions;
    }
}