<?php
namespace nhockizi\cms\modules\catalog\api;

use Yii;

use yii\data\ActiveDataProvider;
use nhockizi\cms\modules\catalog\models\ItemData;
use nhockizi\cms\widgets\Fancybox;
use nhockizi\cms\modules\catalog\models\Category;
use nhockizi\cms\modules\catalog\models\Item;
use yii\widgets\LinkPager;

class Catalog extends \nhockizi\cms\components\API
{
    private $_cats;
    private $_items;
    private $_adp;
    private $_item = [];
    private $_last;

    public function api_cat($id_slug)
    {
        if(!isset($this->_cats[$id_slug])) {
            $this->_cats[$id_slug] = $this->findCategory($id_slug);
        }
        return $this->_cats[$id_slug];
    }

    public function api_tree()
    {
        return Category::tree();
    }

    public function api_cats()
    {
        return Category::cats();
    }

    public function api_items($options = [])
    {
        if(!$this->_items){
            $this->_items = [];

            $query = Item::find()->with(['seo', 'category'])->status(Item::STATUS_ON);

            if(!empty($options['where'])){
                $query->andFilterWhere($options['where']);
            }
            if(!empty($options['orderBy'])){
                $query->orderBy($options['orderBy']);
            } else {
                $query->sortDate();
            }
            if(!empty($options['filters'])){
                $query = self::applyFilters($options['filters'], $query);
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach($this->_adp->models as $model){
                $this->_items[] = new ItemObject($model);
            }
        }
        return $this->_items;
    }

    public function api_last($limit = 1, $where = null)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $result = [];

        $query = Item::find()->with('seo')->sortDate()->status(Item::STATUS_ON)->limit($limit);
        if($where){
            $query->andFilterWhere($where);
        }

        foreach($query->all() as $item){
            $result[] = new ItemObject($item);
        }

        if($limit > 1){
            return $result;
        }else{
            $this->_last = count($result) ? $result[0] : null;
            return $this->_last;
        }
    }

    public function api_get($id_slug)
    {
        if(!isset($this->_item[$id_slug])) {
            $this->_item[$id_slug] = $this->findItem($id_slug);
        }
        return $this->_item[$id_slug];
    }

    public function api_pagination()
    {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages()
    {
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }

    public function api_plugin($options = [])
    {
        Fancybox::widget([
            'selector' => '.nhockizi_cms-box',
            'options' => $options
        ]);
    }
    
    public static function applyFilters($filters, $query)
    {
        if(is_array($filters)){

            if(!empty($filters['price'])){
                $price = $filters['price'];
                if(is_array($price) && count($price) == 2) {
                    if(!$price[0]){
                        $query->andFilterWhere(['<=', 'price', (int)$price[1]]);
                    } elseif(!$price[1]) {
                        $query->andFilterWhere(['>=', 'price', (int)$price[0]]);
                    } else {
                        $query->andFilterWhere(['between', 'price', (int)$price[0], (int)$price[1]]);
                    }
                }
                unset($filters['price']);
            }
            if(count($filters)){
                $filtersApplied = 0;
                $subQuery = ItemData::find()->select('item_id, COUNT(*) as filter_matched')->groupBy('item_id');
                foreach($filters as $field => $value){
                    if(!is_array($value)) {
                        $subQuery->orFilterWhere(['and', ['name' => $field], ['value' => $value]]);
                        $filtersApplied++;
                    } elseif(count($value) == 2){
                        if(!$value[0]){
                            $additionalCondition = ['<=', 'value', (int)$value[1]];
                        } elseif(!$value[1]) {
                            $additionalCondition = ['>=', 'value', (int)$value[0]];
                        } else {
                            $additionalCondition = ['between', 'value', (int)$value[0], (int)$value[1]];
                        }
                        $subQuery->orFilterWhere(['and', ['name' => $field], $additionalCondition]);

                        $filtersApplied++;
                    }
                }
                if($filtersApplied) {
                    $query->join('LEFT JOIN', ['f' => $subQuery], 'f.item_id = '.Item::tableName().'.item_id');
                    $query->andFilterWhere(['f.filter_matched' => $filtersApplied]);
                }
            }
        }
        return $query;
    }
    

    private function findCategory($id_slug)
    {
        $category = Category::find()->where(['or', 'category_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status(Item::STATUS_ON)->one();

        return $category ? new CategoryObject($category) : null;
    }

    private function findItem($id_slug)
    {
        if(!($item = Item::find()->where(['or', 'item_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status(Item::STATUS_ON)->one())){
            return null;
        }

        return new ItemObject($item);
    }
}