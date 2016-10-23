<?php
class Controller
{
    public $model;
    public $tpl;
    public $params;
    function __construct()
    {
        $this->tpl = new Template();
    }
}