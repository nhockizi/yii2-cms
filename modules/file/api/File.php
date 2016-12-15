<?php
namespace nhockizi\cms\modules\file\api;

use Yii;
use yii\data\ActiveDataProvider;
use nhockizi\cms\modules\file\models\File as FileModel;
use yii\widgets\LinkPager;

class File extends \nhockizi\cms\components\API
{
    private $_adp;
    private $_last;
    private $_items;
    private $_item = [];

    public function api_items($options = [])
    {
        if(!$this->_items){
            $this->_items = [];

            $query = FileModel::find()->with('seo')->sort();

            if(!empty($options['where'])){
                $query->where($options['where']);
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach($this->_adp->models as $model){
                $this->_items[] = new FileObject($model);
            }
        }
        return $this->_items;
    }

    public function api_get($id_slug)
    {
        if(!isset($this->_item[$id_slug])) {
            $this->_item[$id_slug] = $this->findFile($id_slug);
        }
        return $this->_item[$id_slug];
    }

    public function api_last($limit = 1, $where = null)
    {
        if($limit === 1 && $this->_last){
            return $this->_last;
        }

        $result = [];

        $query = FileModel::find()->with('seo')->sort()->limit($limit);
        if($where){
            $query->where($where);
        }
        foreach($query->all() as $item){
            $result[] = new FileObject($item);
        }

        if($limit > 1){
            return $result;
        } else {
            $this->_last = count($result) ? $result[0] : null;
            return $this->_last;
        }
    }

    public function api_pagination()
    {
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function api_pages()
    {
        return $this->_adp ? LinkPager::widget(['pagination' => $this->_adp->pagination]) : '';
    }

    private function findFile($id_slug)
    {
        $file = FileModel::find()->where(['or', 'file_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->one();

        return $file ? new FileObject($file) : null;
    }
}