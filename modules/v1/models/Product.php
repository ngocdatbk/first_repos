<?php

namespace app\modules\v1\models;

use app\models\Product as ComProduct;

class Product extends ComProduct
{
    public function fields()
    {
        return [
            'id',
            'code',
            'name',
            'category_id',
        ];
    }
}
