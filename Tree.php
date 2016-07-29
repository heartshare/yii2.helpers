<?php

namespace hughcube\helpers;

use yii\base\Object;
use Yii;

class Tree extends Object
{
    /**
     * @var array
     */
    protected $_items = [];

    /**
     * list的id的key
     *
     * @var string
     */
    public $idKey = 'id';

    /**
     * list的parent的key
     *
     * @var string
     */
    public $parentKey = 'parent';

    /**
     * list的left的key
     *
     * @var string
     */
    public $leftKey = 'left';

    /**
     * list的right的key
     *
     * @var string
     */
    public $rightKey = 'right';

    /**
     * list的level的key
     *
     * @var string
     */
    public $levelKey = 'level';

    /**
     * 添加一个节点
     *
     * @param $node
     * @param null $parent 父节点
     */
    public function addChild($node, $parent = null)
    {
        if (null === $parent) {
            $level = $left = 0;
        } else {
            $left = $this->_items[$parent][$this->leftKey];
            $level = $this->_items[$parent][$this->levelKey];
        }

        foreach ($this->_items as $key => $item) {
            if ($item[$this->leftKey] > $left) {
                $this->_items[$key][$this->leftKey] += 2;
            }

            if ($item[$this->rightKey] > $left) {
                $this->_items[$key][$this->rightKey] += 2;
            }
        }

        $node[$this->levelKey] = $level + 1;
        $node[$this->leftKey] = $left + 1;
        $node[$this->rightKey] = $node[$this->leftKey] + 1;
        $node[$this->parentKey] = $parent;
        $this->_items[$node[$this->idKey]] = $node;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return array_values($this->_items);
    }

    /**
     * @return array
     */
    public function setItems($items, $needFormat = false)
    {
        if ($needFormat) {
            $this->formatItems($items);
        } else {
            $items = ArrayHelper::index($items, $this->idKey);
            $this->_items = $items;
        }
    }

    /**
     * 获取节点的子节点
     *
     * @param $node
     * @param null $level 获取几级子节点
     * @return array
     */
    public function getChildren($node, $level = null)
    {
        $nodes = [];
        foreach ($this->_items as $id => $item) {
            if ($item[$this->leftKey] > $this->_items[$node][$this->leftKey]
                && $item[$this->rightKey] < $this->_items[$node][$this->rightKey]
                && (null === $level || $item[$this->levelKey] - $this->_items[$node][$this->levelKey] <= $level)
            ) {
                $nodes[$id] = $item[$this->levelKey];
            }
        }

        return $nodes;
    }

    /**
     * 获取所有的父节点
     *
     * @param $node
     * @return array
     */
    public function getParents($node)
    {
        $nodes = [];
        foreach ($this->_items as $id => $item) {
            if ($item[$this->leftKey] < $this->_items[$node][$this->leftKey]
                && $item[$this->rightKey] > $this->_items[$node][$this->rightKey]
            ) {
                $nodes[$id] = $item[$this->levelKey];
            }
        }

        return $nodes;
    }

    /**
     * 获取某一级的父节点
     *
     * @param $node
     * @param int $level 获取几级子节点
     * @return int|null|string
     */
    public function getParent($node, $level = 1)
    {
        $parent = null;
        foreach ($this->_items as $id => $item) {
            if ($item[$this->leftKey] < $this->_items[$node][$this->leftKey]
                && $item[$this->rightKey] > $this->_items[$node][$this->rightKey]
                && $this->_items[$node][$this->levelKey] - $item[$this->levelKey] == $level
            ) {
                $parent = $id;
                break;
            }
        }

        return $parent;
    }

    /**
     * 节点的层级
     *
     * @param $node
     * @return int
     */
    public function getNodeLevel($node)
    {
        return count($this->getParents($node)) + 1;
    }

    /**
     * 获取树结构
     *
     * @param null $parent
     * @param string $childrenKey
     * @return array
     */
    public function getTree($parent = null, $childrenKey = 'items')
    {
        $items = [];

        if (null === $parent) {
            $items = $this->_items;
        } else {
            $children = $this->getChildren($parent);
            foreach ($children as $child) {
                $items[$child] = $this->_items[$child];
            }
        }

        $tree = [];
        foreach ($items as $id => $item) {
            if (isset($items[$item[$this->parentKey]])) {
                $items[$item[$this->parentKey]][$childrenKey][] = &$items[$id];
            } else {
                $tree[] = &$items[$id];
            }
        }

        return $tree;
    }

    /**
     * 设置list
     *
     * @param array $items
     * @param bool $hasParent
     * @return array
     */
    public function formatItems(array $items, $hasParent = true)
    {
        if (empty($items)) {
            return [];
        }

        $items = ArrayHelper::index($items, $this->idKey);

        if (!$hasParent) {
            $this->_items = $items;
            foreach ($this->_items as $id => $item) {
                $this->_items[$id][$this->parentKey] = $this->getParent($id, 1);
            }
            $items = $this->_items;
            $this->_items = [];
        }

        foreach ($items as $id => $item) {
            if (!isset($this->_items[$id])) {
                $this->recursiveAddChild($id, $items);
            }
        }
    }

    protected function recursiveAddChild($id, $items)
    {
        $parent = $items[$id][$this->parentKey];
        if (!isset($items[$parent]) || isset($this->_items[$parent])) {
            $this->addChild($items[$id], $parent);
        } else {
            call_user_func(__METHOD__, $parent, $items);
        }
    }

    /**
     * @param array $config
     * @return static
     */
    public static function instantiate(array $config = [])
    {
        $config['class'] = get_called_class();

        return Yii::createObject($config);
    }
}
