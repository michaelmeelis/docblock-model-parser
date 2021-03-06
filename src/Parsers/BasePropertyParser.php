<?php

namespace michaelmeelis\DocBlockModelParser\Parsers;


use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag\ParamTag;

abstract class BasePropertyParser
{
    const PROPERTY_POSTFIX_ID = '_id';
    const PROPERTY_BASE_NAME = 'property';
    const PROPERTY_READ_NAME = 'property-read';

    protected $idFieldNamePostFix;
    protected $model;
    protected $baseProperties;

    public function __construct($model)
    {
        $this->idFieldNamePostFix = self::PROPERTY_POSTFIX_ID;
        $this->model = $model;

        $this->baseProperties = $this->getProperties(self::PROPERTY_BASE_NAME);
        $this->basePropertiesKeys = array_keys($this->baseProperties);

        $this->readProperties = $this->getProperties(self::PROPERTY_READ_NAME);
    }

    abstract public function parse();

    protected function getProperties($propertyName)
    {
        $reflection = new \ReflectionClass($this->model);
        $docBlock = new DocBlock($reflection);
        $properties = $docBlock->getTagsByName($propertyName);

        return $this->buildProperties($properties);
    }

    /**
     * @param DocBlock\Tag\ParamTag[]
     * @return array
     */
    private function buildProperties($properties = [])
    {
        $buildProperties = [];
        foreach ($properties as $property) {
            $propertyName = $this->makePropertyNameFromTag($property);
            $buildProperties[$propertyName] = $property->getType();
        }

        return $buildProperties;
    }

    protected function makePropertyNameFromTag(ParamTag $paramTag)
    {
        $name = $paramTag->getVariableName();
        return ltrim($name, '$');
    }

    /**
     * Based on barryvdh ide-helper. Doc block gets the class name and if there is a |
     * then you always need the second one.
     * If there is an array of models (modelname[]) then remove the array notation.
     *
     * @param $className
     * @return string
     */
    public function parseClassName($className)
    {
        if (strpos($className, '|') !== false) {
            $className = explode('|', $className);
            $className = $className[1];
        }

        if (strpos($className, '[]') !== false) {
            $className = rtrim($className, '[]');
        }

        return $className;
    }

    public function getBasePropertyType($basePropertyName)
    {
        return $this->baseProperties[$basePropertyName];
    }


    /**
     * Compare the normal existing fields to the read properties from the ide helper
     * If they match then it's an 1 to N relation.
     * We can do this because a read property is based on a function that links methods to an
     * attribute.
     *
     * @param $propertyName
     * @return bool|string
     */

    public function getBasePropertyName($propertyName)
    {
        foreach ($this->basePropertiesKeys as $basePropertyName) {
            if (strpos($basePropertyName, $propertyName) !== false) {
                return $basePropertyName;
            }
        }
        return false;
    }
}