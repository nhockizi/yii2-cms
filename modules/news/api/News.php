<?php
namespace nhockizi\cms\modules\news\api;

use Yii;
use yii\data\ActiveDataProvider;
use nhockizi\cms\models\Tag;
use nhockizi\cms\widgets\Fancybox;
use yii\widgets\LinkPager;

use nhockizi\cms\modules\news\models\News as NewsModel;

class News extends \nhockizi\cms\components\API
{
    private $_adp;
    private $_last;
    private $_items;
    private $_item = [];

    public function api_items($options = [])
    {
        if(!$this->_items){
            $this->_items = [];

            $with = ['seo'];
            if(Yii::$app->getModule('admin')->activeModules['news']->settings['enableTags']){
                $with[] = 'tags';
            }
            $query = NewsModel::find()->with($with)->status(NewsModel::STATUS_ON);

            if(!empty($options['where'])){
                $query->andFilterWhere($options['where']);
            }
            if(!empty($options['tags'])){
                $query
                    ->innerJoinWith('tags', false)
                    ->andWhere([Tag::tableName() . '.name' => (new NewsModel)->filterTagValues($options['tags'])])
                    ->addGroupBy('news_id');
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
                $this->_items[] = new NewsObject($model);
            }
        }
        return $this->_items;
    }

    public function api_get($id_slug)
    {
        if(!isset($this->_item[$id_slug])) {
            $this->_item[$id_slug] = $this->findNews($id_slug);
        }
        return $this->_item[$id_slug];
    }

    public function api_last($limit = 1)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $with = ['seo'];
        if(Yii::$app->getModule('admin')->activeModules['news']->settings['enableTags']){
            $with[] = 'tags';
        }

        $result = [];
        foreach(NewsModel::find()->with($with)->status(NewsModel::STATUS_ON)->sortDate()->limit($limit)->all() as $item){
            $result[] = new NewsObject($item);
        }

        if($limit > 1){
            return $result;
        } else {
            $this->_last = count($result) ? $result[0] : null;
            return $this->_last;
        }
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

    private function findNews($id_slug)
    {
        $news = NewsModel::find()->where(['or', 'news_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status(NewsModel::STATUS_ON)->one();
        if($news) {
            $news->updateCounters(['views' => 1]);
            return new NewsObject($news);
        } else {
            return null;
        }
    }
}