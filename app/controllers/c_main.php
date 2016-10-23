<?php
/** @property M_main $model */
class C_main extends Controller
{
    function main()
    {
        $this->tpl->generate('main');
    }

    function search()
    {
        if(!Router::isAjax())
        {
            Route::error404();
        }
        $data = isset($_POST['data']) ? $_POST['data'] : false;
        if(!$data)
        {
            echo json_encode(['errors'=>'Данные не переданны']);
            die;
        }
        $validator = new Validator();
        $validator->set_rule(
            [
                'month' =>['required', 'int', 'min'=>1, 'max'=>12],
                'year'  =>['required', 'int', 'min'=>2000, 'max'=>date('Y')],
                'type'  =>['required', 'int', 'min'=>0, 'max' =>2]
            ]);
        if(!$validator->check($data))
        {
            echo json_encode($validator->errors());
            die;
        }
        $data = $validator->get_data();
        $result = $this->model->search($data);
        if(count($result) == 0)
        {
            echo json_encode(['errors'=>'Нет данных']);
            die;
        }
        $html = $this->tpl->items_generate('table_string',$result);
        echo $html;
    }

    /**
     * create random data for testing
     */
    function create_data()
    {
        $this->create_users(1000);
        $this->create_payments(100000);
        echo 'данные созданы';
    }

    /**
     * @param $count int total users created
     */
    private function create_users($count)
    {
        $users = '';
        for($i=0; $i<$count; $i++)
        {
            $users.= "( 'user_name_".rand(0,9999999)."','".rand(1,2)."'),";
        }
        $users = substr($users,0,-1);
        $this->model->create_users($users);
    }

    /**
     * @param $count int total payments created
     */
    private function create_payments($count)
    {
        $users = $this->model->get_users();
        $users_count = count($users);
        $payments = '';
        for($i=0; $i<$count; $i++)
        {
            $user = $users[rand(0,$users_count-1)]['id'];
            $date = rand(2000,2016).'-'.rand(1,12).'-'.rand(1,28);
            $service = rand(1,6);
            $payments_type = rand(1,4);
            $summa = ($payments_type < 3 ? rand(-100000,-100): rand(1,100000)) / 100;
            $payments.= "('".$user."','".$summa."','".$date."','".$service."','".$payments_type."'),";
        }
        $payments = substr($payments,0,-1);
        $this->model->create_payments($payments);
    }

}

