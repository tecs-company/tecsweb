<?php

namespace Tecs\Model;

/**
 * Class AbstractResponse
 * @package Tecs\Model
 */
abstract class AbstractResponse
{
    protected $data = [];

    /**
     * AbstractResponse constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getData()
    {
        return $this->data;
    }

    public function __call($name, $arguments)
    {
        $key = lcfirst(str_replace('get', '', $name));
        
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
}
