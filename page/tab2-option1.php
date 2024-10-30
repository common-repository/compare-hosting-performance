<?php ?>
<p><a id="startTest" href="#" class="button button-primary">Тестировать</a>
    <span id="load" hidden="hidden">Попейте кофе, пока идёт тест :)<img height="30px" width="30px" src="<?php echo plugins_url() . '/' . self::PATCH_PLUGIN . '/' . 'img/load.gif' ?>"/></span>
</p>
<!--Результат ajax запроса-->
<div id="tableRes"></div>

<?php
$jornalarray = get_option('chp_jornal');
$i = 1;
?>
<h2>Ваши последние замеры производительности</h2>
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>№</th> 
            <th>Дата теста</th> 
            <th>Сервер</th>
            <th>PHP версия</th>
            <th>MySQL версия</th>
            <th>Тест MySQL</th>
            <th>Тест PHP</th>
            <th>Общее время</th>
            <th>Индекс производительности MySQL</th>
            <th>Индекс производительности PHP</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($jornalarray as $jornalprint) { ?>
            <tr class="success">
                <th><?php echo $i; ?></th>
                <th><?php echo $jornalprint['date']; ?></th>
                <th><?php echo $jornalprint['hosting']; ?></th>
                <th><?php echo $jornalprint['vphp']; ?></th>
                <th><?php echo $jornalprint['vmysql']; ?></th>
                <th><?php echo $jornalprint['tphp']; ?></th>
                <th><?php echo $jornalprint['tmysql']; ?></th>
                <th><?php echo $jornalprint['summ']; ?></th>
                <th><?php echo $jornalprint['ipmysql']; ?></th>
                <th><?php echo $jornalprint['ipphp']; ?></th>
            </tr>
            <?php
            $i++;
        }
        ?>
    </tbody>



</table>

