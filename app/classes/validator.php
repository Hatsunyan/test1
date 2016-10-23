<?php
//Created by hatsu
//25.06.14 16:22
class Validator
{
    private $errors = false;
    private $terms = null;
    private $patterns =
        [
            'email' =>
                [
                    '/([a-z0-9\.-_]+)@([a-z0-9\.-_]+)/',
                    'Неправильно введён E-mail'
                ],
            'num' =>
                [
                    '/^\d+$/',
                    '* может содержать только цифры'
                ],
            'int' =>  //копия num для большей понятности и обратной совместимости
                [
                    '/^\d+$/',
                    '* может содержать только цифры'
                ],
            'login' =>
                [
                    '/^([A-Za-z][-A-Za-z0-9]+)$/',
                    '* может содержать только латинские буквы, цифры и знак -'
                ],
            'rus' =>
                [
                    '/^[а-яёА-ЯЁ\s]+$/u',
                    '* может содержать только кириллицу'
                ],
            'suburl' =>
                [
                    '/^[a-zA-Z0-9\-]+$/u',
                    '* может содержать латинские буквы и цифры и тире'
                ],
            'site' =>
                [
                    '/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/',
                    'Адрес сайта введён неверно'
                ],
            'phone' =>
                [
                    '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/',
                    'Поле * не соответствует телефонному номеру'
                ]
        ];
    private $messages =
        [
            'required' => 'Поле * должно быть заполнено',
            'max'      => 'Поле * должно быть меньше #',
            'min'      => 'Поле * должно быть больше #',
            'maxLen'   => 'Поле * не может быть длиннее # знаков',
            'minLen'   => 'Поле * не может быть короче # знаков',
            'eqLen'    => 'Поле * должно быть длинной # знаков'
        ];

    private $data = null;
    private $clear_data = [];


    function set_rule(array $rules,$data = null)
    {
        $this->terms = $rules;
        if($data)
        {
            $this->set_data($data);
        }
    }
    function set_data($data)
    {
        if(isset($data[0]['name']))
        {
            $data = $this->convert_array($data);
        }
        $this->data = $data;
    }
    private function convert_array($data)
    {
        $new_arr = [];
        foreach($data as $arr)
        {
            $new_arr[$arr['name']] = isset($arr['value']) ? $arr['value'] : null;
        }
        return $new_arr;
    }
    function errors()
    {
        return $this->errors;
    }
    function get_data()
    {
        return $this->clear_data;
    }
    function check($data = null)
    {
        if($data)
        {
            $this->set_data($data);
        }
        if(!$this->data) //так же надо проверить масcив с условиями
        {
            return false;
        }


        //checking
        foreach($this->terms as $item_name=>$item)
        {
            $value = isset($this->data[$item_name])?$this->data[$item_name]:false;
            $this->clear_data[$item_name] = $value;
            foreach($item as $rule_name=>$rule)
            {
                if($rule == 'required')
                {
                    if(!$this->required($value, $item_name))
                    {
                        break;
                    }

                }elseif($rule == 'strip')
                {
                    $value = $this->strip($value, $item_name);
                }
                elseif(is_int($rule_name)) // если правило без параметра напр. ['int']
                {
                    $this->pattern($rule, $value, $item_name);
                } else {
                    $this->$rule_name($rule, $value, $item_name);
                }
            }
        }
        if(!$this->errors)
        {
            return true;
        }else{
            return false;
        }
    }

    /** Создаёт шаблон сообщения об ошибке
     * @param $method string название метода, необходимо бля шаблона
     * @param null $val
     * @return mixed
     */
    private function create_message($method, $val = null)
    {
        $method_name = explode('::',$method)[1];
        $message = $this->messages[$method_name];
        if($val)
        {
            $message = str_replace('#',$val,$message);
        }
        return $message;
    }

    // checking function //


    /** Проверяет существование значения
     * @param $value
     * @param $item_name
     * @return bool
     */
    private function required($value, $item_name)
    {
        if($value == '' || $value == null)
        {
            $this->errors[$item_name][] = $this->create_message(__METHOD__);
            return false;
        }
        return true;
    }

    /** Проверяет что число меньше максимально возможного
     * @param $rule
     * @param $value
     * @param $item_name
     */
    private function max($rule, $value, $item_name)
    {
        if($rule < $value)
        {
            $this->errors[$item_name][] = $this->create_message(__METHOD__,$rule);
        }
    }

    /** Проверяет что число больше необходимого
     * @param $rule
     * @param $value
     * @param $item_name
     */
    private function min($rule, $value, $item_name)
    {
        if($rule > $value)
        {
            $this->errors[$item_name][] = $this->create_message(__METHOD__,$rule);
        }
    }

    /** Проверяет длину строки
     * @param $rule
     * @param $value
     * @param $item_name
     */
    private function maxLen($rule, $value, $item_name)
    {
        if(mb_strlen($value,'UTF-8') > $rule)
        {
            $this->errors[$item_name][] = $this->create_message(__METHOD__,$rule);
        }
    }

    /** Проверяет точное соответствие длины строки
     * @param $rule
     * @param $value
     * @param $item_name
     */
    private function eqLen($rule, $value, $item_name)
    {
        if(mb_strlen($value,'UTF-8') != $rule)
        {
            $this->errors[$item_name][] = $this->create_message(__METHOD__,$rule);
        }
    }

    /** Проверяет длину строки
     * @param $rule
     * @param $value
     * @param $item_name
     */
    private function minLen($rule, $value, $item_name)
    {
        if(mb_strlen($value,'UTF-8') < $rule)
        {
            $this->errors[$item_name][] = $this->create_message(__METHOD__,$rule);
        }
    }

    /** Проверяет соответствие шаблону
     * @param $rule
     * @param $value
     * @param $item_name
     */
    private function pattern($rule, $value, $item_name)
    {
        if($value!='' && !preg_match($this->patterns[$rule][0],$value))
        {
            $this->errors[$item_name][] = $this->patterns[$rule][1];
        }
    }

    /** Убирает опасные символы
     * @param $value
     * @param $item_name
     * @return string
     */
    private function strip($value,$item_name)
    {
        return $this->clear_data[$item_name] = htmlentities(strip_tags($value));
    }
}