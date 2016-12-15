<?php
namespace nhockizi\cms\modules\article\api;

use Yii;

use yii\data\ActiveDataProvider;
use nhockizi\cms\models\Tag;
use nhockizi\cms\modules\article\models\Category;
use nhockizi\cms\modules\article\models\Item;
use nhockizi\cms\widgets\Fancybox;
use yii\widgets\LinkPager;

/**
 * Article module API
 * @package nhockizi\cms\modules\article\api
 *
 * @method static CategoryObject cat(mixed $id_slug) Get article category by id or slug
 * @method static array tree() Get article categories as tree
 * @method static array cats() Get article categories as flat array
 * @method static array items(array $options = []) Get list of articles as ArticleObject objects
 * @method static ArticleObject get(mixed $id_slug) Get article object by id or slug
 * @method static mixed last(int $limit = 1) Get last articles
 * @method static void plugin() Applies FancyBox widget on photos called by box() function
 * @method static string pages() returns pagination html generated by yii\widgets\LinkPager widget.
 * @method static \stdClass pagination() returns yii\data\Pagination object.
 */

class Article extends \nhockizi\cms\components\API
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

            $with = ['seo', 'category'];
            if(Yii::$app->getModule('admin')->activeModules['article']->settings['enableTags']){
                $with[] = 'tags';
            }
            $query = Item::find()->with($with)->status(Item::STATUS_ON);

            if(!empty($options['where'])){
                $query->andFilterWhere($options['where']);
            }
            if(!empty($options['tags'])){
                $query
                    ->innerJoinWith('tags', false)
                    ->andWhere([Tag::tableName() . '.name' => (new Item())->filterTagValues($options['tags'])])
                    ->addGroupBy('item_id');
            }
            if(!empty($options['orderBy'])){
                $query->orderBy($options['orderBy']);
            } else {
                $query->sortDate();
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach($this->_adp->models as $model){
                $this->_items[] = new ArticleObject($model);
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

        $with = ['seo'];
        if(Yii::$app->getModule('admin')->activeModules['article']->settings['enableTags']){
            $with[] = 'tags';
        }
        $query = Item::find()->with($with)->status(Item::STATUS_ON)->sortDate()->limit($limit);
        if($where){
            $query->andFilterWhere($where);
        }

        foreach($query->all() as $item){
            $result[] = new ArticleObject($item);
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

    public function api_plugin($options = [])
    {
        Fancybox::widget([
            'selector' => '.nhockizi_cms-box',
            'options' => $options
        ]);
    }

    public function api_pagination()
    {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages()
    {
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }

    private function findCategory($id_slug)
    {
        $category = Category::find()->where(['or', 'category_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status(Item::STATUS_ON)->one();

        return $category ? new CategoryObject($category) : null;
    }

    private function findItem($id_slug)
    {
        $article = Item::find()->where(['or', 'item_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status(Item::STATUS_ON)->one();
        if($article) {
            $article->updateCounters(['views' => 1]);
            return new ArticleObject($article);
        } else {
            return null;
        }
    }
}