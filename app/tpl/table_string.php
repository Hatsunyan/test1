<?php
/**@var $data array*/
foreach($data as $d)
{
?>
    <tr>
        <td><?=$d['name']?></td>
        <td><?=$d['balance']?></td>
        <td><?=$d['parish']?></td>
        <td><?=$d['consum']*-1?></td>
        <td><?=$d['recalculation']?></td>
        <td><?=$d['balance']+$d['parish']+$d['consum']?></td>
    </tr>
<? }