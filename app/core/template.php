<?php
class Template
{
    /**
     * Генерирует шаблон
     * @param $template_name string
     * @param null $data
     * @param bool|true $full_page
     */
    function generate($template_name, $data = null, $full_page = true)
    {
        $path_arr = explode('/',$template_name);
        if(count($path_arr) == 2)
        {
            $template_name = $path_arr[0].'/t_'.$path_arr[1];
        }else{
            $template_name = 't_'.$path_arr[0];
        }
        if($full_page)
        {
            include 'app/tpl/t_top.php';
        }

        include 'app/tpl/'.$template_name.'.php';

        if($full_page)
        {
            include 'app/tpl/t_bottom.php';
        }
    }

    /**
     * Генерирует кусок шаблона без top и bottom
     * @param $template_name
     * @param null $data
     */
    function part_generate($template_name, $data = null)
    {
        $this->generate($template_name,$data,false);
    }

    /**
     * @param $template_name
     * @param $data
     * @return string
     * @function create_items
     */
    function items_generate($template_name,$data)
    {
        ob_start();
        include 'app/tpl/'.$template_name.'.php';
        return ob_get_clean();
    }
}