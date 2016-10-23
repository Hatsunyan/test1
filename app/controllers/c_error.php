<?php
/** @property M_main $model */
class C_error extends Controller
{
    public function index()
    {
        $this->tpl->generate('error');
    }
}