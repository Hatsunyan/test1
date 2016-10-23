<div class="filter">
    <form id="form-search">
        <label for="label-month">Месяц</label>
        <select id="label-month" name="month">
            <option value="1">Январь</option>
            <option value="2">Февраль</option>
            <option value="3">Март</option>
            <option value="4">Апрель</option>
            <option value="5">Май</option>
            <option value="6">Июнь</option>
            <option value="7">Июль</option>
            <option value="8">Август</option>
            <option value="9">Сентябрь</option>
            <option value="10">Октябрь</option>
            <option value="11">Ноябрь</option>
            <option value="12">Декабрь</option>
        </select>
        <label for="label-year">Год</label>
        <input type="number" id="label-year" name="year" min="2000" max="<?=date('Y')?>" value="<?=date('Y')?>">
        <label for="label-type">Тип</label>
        <select name="type" id="label-type">
            <option value="0">Все</option>
            <option value="1">Физ. лица</option>
            <option value="2">Юр. лица</option>
        </select>
    </form>
    <input type="button" value="Поиск" id="start-search">
</div>
<table>
    <tr>
        <th>Услуга</th>
        <th>Баланс на начало периода</th>
        <th>Приход</th>
        <th>Расход</th>
        <th>Перерасчет</th>
        <th>Итого</th>
    </tr>
    <tr>
        <td colspan="6">Выберите параметры, чтобы начать поиск</td>
    </tr>
</table>