<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
//namespace yii\data;

namespace app\modules\v1\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Object;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\Request;

/**
 * Sort represents information relevant to sorting.
 *
 * When data needs to be sorted according to one or several attributes,
 * we can use Sort to represent the sorting information and generate
 * appropriate hyperlinks that can lead to sort actions.
 *
 * A typical usage example is as follows,
 *
 * ```php
 * function actionIndex()
 * {
 *     $sort = new Sort([
 *         'attributes' => [
 *             'age',
 *             'name' => [
 *                 'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
 *                 'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
 *                 'default' => SORT_DESC,
 *                 'label' => 'Name',
 *             ],
 *         ],
 *     ]);
 *
 *     $models = Article::find()
 *         ->where(['status' => 1])
 *         ->orderBy($sort->orders)
 *         ->all();
 *
 *     return $this->render('index', [
 *          'models' => $models,
 *          'sort' => $sort,
 *     ]);
 * }
 * ```
 *
 * View:
 *
 * ```php
 * // display links leading to sort actions
 * echo $sort->link('name') . ' | ' . $sort->link('age');
 *
 * foreach ($models as $model) {
 *     // display $model here
 * }
 * ```
 *
 * In the above, we declare two [[attributes]] that support sorting: name and age.
 * We pass the sort information to the Article query so that the query results are
 * sorted by the orders specified by the Sort object. In the view, we show two hyperlinks
 * that can lead to pages with the data sorted by the corresponding attributes.
 *
 * @property array $attributeOrders Sort directions indexed by attribute names. Sort direction can be either
 * `SORT_ASC` for ascending order or `SORT_DESC` for descending order. This property is read-only.
 * @property array $orders The columns (keys) and their corresponding sort directions (values). This can be
 * passed to [[\yii\db\Query::orderBy()]] to construct a DB query. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Sort extends \yii\data\Sort {

    /**
     * @var boolean whether the sorting can be applied to multiple attributes simultaneously.
     * Defaults to false, which means each time the data can only be sorted by one attribute.
     */
    public $enableMultiSort = true;
    private $_attributeOrders;

    /**
     * Returns the currently requested sort information.
     * @param boolean $recalculate whether to recalculate the sort directions
     * @return array sort directions indexed by attribute names.
     * Sort direction can be either `SORT_ASC` for ascending order or
     * `SORT_DESC` for descending order.
     */
    public function getAttributeOrders($recalculate = false) {
        if ($this->_attributeOrders === null || $recalculate) {
            $this->_attributeOrders = [];
            if (($params = $this->params) === null) {
                $request = Yii::$app->getRequest();
                $params = $request instanceof Request ? $request->getQueryParams() : [];
            }
            if (isset($params[$this->sortParam]) && is_scalar($params[$this->sortParam])) {
                $attributes = explode($this->separator, $params[$this->sortParam]);
                foreach ($attributes as $attribute) {
                    $descending = false;
                    if (strncmp($attribute, '-', 1) === 0) {
                        $descending = true;
                        $attribute = substr($attribute, 1);
                    }

                    if (isset($this->attributes[$attribute])) {
                        $this->_attributeOrders[$attribute] = $descending ? SORT_DESC : SORT_ASC;
                        if (!$this->enableMultiSort) {
                            return $this->_attributeOrders;
                        }
                    }
                }
            }
            if (empty($this->_attributeOrders) && is_array($this->defaultOrder)) {
                $this->_attributeOrders = $this->defaultOrder;
            }
        }

        return $this->_attributeOrders;
    }

    /**
     * Returns the columns and their corresponding sort directions.
     * @param boolean $recalculate whether to recalculate the sort directions
     * @return array the columns (keys) and their corresponding sort directions (values).
     * This can be passed to [[\yii\db\Query::orderBy()]] to construct a DB query.
     */
    public function getSortParams($recalculate = false) {
        $attributeOrders = $this->getAttributeOrders($recalculate);
        $orders = [];
        foreach ($attributeOrders as $attribute => $direction) {
            $definition = $this->attributes[$attribute];
            $columns = $definition[$direction === SORT_ASC ? 'asc' : 'desc'];
            foreach ($columns as $name => $dir) {
                if ($dir === SORT_ASC) {
                    $dir = 'asc';
                } else {
                    $dir = 'desc';
                }
                $orders[$name] = $dir;
            }
        }
        return $orders;
    }

}
