<?php

class TreeBlock
{
    public $data;
    public $child;

    /**
     * @param $data
     */
    public	function __construct($data)
    {
        $this->data = $data;
        $this->child = null;
    }
}