<?php

class M_main extends Model
{
    function search($data)
    {

        $date_start = $data['year'].'-'.$data['month'].'-1';
        $date_end = date('Y-m-t', strtotime($date_start));
        $where1 = " WHERE data > '{$date_start}' AND data < '{$date_end}' ";
        $where2 = " WHERE data < '{$date_start}' ";
        $where_clients = '';
        $clients_join = '';
        if($data['type'] != 0) // join clients table only if need
        {
            $clients_join = ' LEFT JOIN clients ON client_id = clients.id ';
            $where_clients = " AND clients.type = '{$data['type']}' ";
        }

        $query = "SELECT services.name, a.consum, a.parish, a.recalculation, b.balance
                    FROM
                    (
                     SELECT payments.id, acnt_id,
                        SUM(IF(summa<0,summa,0)) AS consum,
                        SUM(IF(summa>0,summa,0)) AS parish,
                        SUM(IF(pay_id=3,summa,0)) AS recalculation
                        FROM payments".
                        $clients_join.$where1.$where_clients.
                        "GROUP BY acnt_id
                    ) a
                    LEFT JOIN
                    (
                     SELECT acnt_id, SUM(summa) as balance
                        FROM payments".
                        $clients_join.$where2.$where_clients.
                        "GROUP BY acnt_id
                    ) b
                    ON a.acnt_id = b.acnt_id
                    LEFT JOIN services ON a.acnt_id = services.id";
        return $this->db->select($query);
    }

    function get_users()
    {
        return $this->db->select("SELECT id FROM clients");
    }

    /**
     * for testing
     * @param $users string
     */
    function create_users($users)
    {
        $this->db->query("INSERT INTO clients (name,type) VALUES".$users);
    }

    /**
     * for testing
     * @param $payments string
     */
    function create_payments($payments)
    {
        $this->db->query("INSERT INTO payments (client_id, summa, data, acnt_id, pay_id) VALUES ".$payments);
    }
}