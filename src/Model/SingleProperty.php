<?php

namespace BeFriends\Admin\FormCreator\DocBlock\Model;


class SingleProperty extends BaseProperty
{
    public $modelClassName;

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return $this->modelClassName;
    }

    /**
     * @param string $modelClassName
     */
    public function setModelClassName($modelClassName)
    {
        $this->modelClassName = $modelClassName;
    }
}