<?php

namespace app\modules\v2\models;

use app\models\Product as ComProduct;

class Product extends ComProduct
{
    public function fields()
    {
        return [
            'id',
            'code',
            'name',
            'price',
            'image_main',
            'category_id',
            'deleted_f',
        ];
    }
}
