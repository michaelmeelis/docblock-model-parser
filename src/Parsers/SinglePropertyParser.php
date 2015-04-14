<?php

namespace BeFriends\Admin\FormCreator\DocBlock\Parsers;


class SinglePropertyParser extends BasePropertyParser
{

    public function parse()
    {
        $readProperties = [];
        foreach ($this->readProperties as $readPropertyKey => $readProperty) {
            if ($this->getBasePropertyName($readProperty) !== false) {
                $readProperties[$readPropertyKey] = $this->parseClassName($readProperty);
            }
        }

        return $readProperties;
    }

}