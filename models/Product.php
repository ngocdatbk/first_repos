<?php

namespace app\models;

use Yii;
use app\models\ProductCategory;
use yii\web\Link; // represents a link object as defined in JSON Hypermedia API Language.
use yii\web\Linkable;
use yii\helpers\Url;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $code mã sản phẩm
 * @property string $name Tên sản phẩm
 * @property string $info thông tin chi tiết sản phẩm
 * @property int $price giá
 * @property string $image_main ảnh chính của sản phẩm
 * @property int $category_id danh mục sản phẩm
 * @property int $deleted_f trạng thái xóa, nhận giá trị 0/1
 */
class Product extends \yii\db\ActiveRecord  implements Linkable
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'category_id'], 'required'],
            [['info'], 'string'],
            [['price', 'category_id', 'deleted_f'], 'integer'],
            [['code'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 255],
            [['image_main'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'info' => 'Info',
            'price' => 'Price',
            'image_main' => 'Image Main',
            'category_id' => 'Category ID',
            'deleted_f' => 'Deleted F',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'code',
            'name',
            'info',
            'price',
            'image_main',
            'category_id',
            'deleted_f',
        ];
    }

    public function extraFields()
    {
        return [
            'productCategory',
            'status' => function ($model) {
                return 1;
            },
        ];
    }

    public function getProductCategory()
    {
        return $this->hasMany(ProductCategory::className(), ['id' => 'category_id']);
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['product/view', 'id' => $this->id], true),
            'edit' => Url::to(['product/update', 'id' => $this->id], true),
            'index' => Url::to(['product/index'], true),
        ];
    }
}
