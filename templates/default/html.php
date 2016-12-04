<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Zhylyhouse</title>
    <link rel="stylesheet" type="text/css" href="/templates/default/css/style.css">
    <script type="text/javascript" src="/templates/default/js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="/templates/default/js/general.js"></script>

</head>
<body>
<div id="topHeader">
    <!-- шапка -->
</div>

<div class="container">

    <ul class="tabs">

        <li class="tab-link current" data-tab="generalInfo">Общие данные</li>
        <li class="tab-link" data-tab="tab-2">Теплообмен</li>
        <li class="tab-link" data-tab="tab-3">Паропроницаемость</li>
        <li class="tab-link" data-tab="tab-4">Теплоустойчивость</li>
        <li class="tab-link" data-tab="tab-5">Воздухопроницаемость</li>
        <li class="tab-link" data-tab="tab-6">Теплобаланс</li>
        <!--<li class="tab-link" data-tab="tab-7">Экономическая эффективность</li>
        <li class="tab-link" data-tab="tab-8">Экология</li>
        <li class="tab-link" data-tab="tab-1">О проекте</li>-->
    </ul>
    <div id="tab-1" class="tab-content">
        Цель проекта
        разработка расчетного инструмента для быстрой оценки энергоэффективного малоэтажного здания с применением необходимых расчетов согласно СН РК. <br>

        Калькулятор основываются на базе расчетной таблицы, созданной на предыдущем этапе. Онлайн-калькулятор отображает и позволяет рассчитать следующее: <br>
        Определение сопротивления паропроницанию конструкций<br>
        Определение сопротивления воздухопроницанию конструкций<br>
        Определение удельной теплоты на отопление<br>
        Определение экономической целесообразности и окупаемости<br>
        Определение количества СО2 <br>
        Согласно СН РК. <br>

        Калькулятор состоит из следующих страниц (ориентировочно, возможно скомбинируем мелкие расчеты на странице): <br>
        1. Введение, область применения (описание калькулятора для чего нужен) <br>
        2. Инструкция по использованию калькулятора<br>
        3. Название, описание проекта, исходные данные <br>
        4. Расчет 1. Определение сопротивления теплопередачи ограждающих конструкций. Расчет необходимого слоя изоляции <br>
        5. Расчет 2. Определение сопротивления воздухопроницанию ограждающих конструкций<br>
        6. Расчет 3. Определение сопротивления паропроницанию ограждающих конструкций<br>
        7. Расчет 4. Определение теплоусвоения поверхности полов<br>
        8. Расчет 5. Определение удельной потребности в полезной тепловой энергии на отопление зданий за отопительный период<br>
        9. Энергетический паспорт здания <br>
        10. Экономика <br>
        11. Выбросы С02 <br>
        12. Построение графиков <br>

        Описание калькулятора: <br>
        Область применения калькулятора: <br>

    </div>
    <div id="generalInfo" class="tab-content current">
        <!--	<div class="domik"> </div>
            <div class="chelovek"> </div> -->

        <form id="generalInfoForm" name="generalInfoForm" method="post" action="">

            <select id="citySelect" name="citySelect">
                <?php
                // получаем данные
                $arCities = get_cities();

                // добавляем первый пункт селекта в общий массив
                $arCities = array_merge(array(array("id" => 0, "name" => "Выберете город - ","is_region" => 0)), $arCities);

                foreach ($arCities as $city){
                    $selected = isset($_SESSION['city_id']) && $_SESSION['city_id'] == $city['id'] ? " selected='selected'" : "";

                    if ($city['is_region'] == 1) {
                        echo "<option value='" . $city['id'] . "' disabled>" . $city['name'] . "</option>\n";
                    } else {
                        echo "<option value='" . $city['id'] . "'".$selected.">" ." - ". $city['name'] . "</option>\n";
                    }
                }

                ?>
            </select>

            <div class="cityInfo">
                <table>
                    <tr>
                        <td>Температура внутреннего воздуха </td>
                        <td> <div id="city_temp_in"></div> </td>
                    </tr>
                    <tr>
                        <td>Влажность внутреннего воздуха</td>
                        <td> <div id="city_vapor_in"></div> </td>
                    </tr>
                    <tr>
                        <td>Температура точки росы </td>
                        <td> <div id="city_dew_temp_point"></div></td>
                    </tr>
                    <tr>
                        <td>Температура наружного воздуха </td>
                        <td> <div id="city_calucl_tem_out_most_cold_5_day"></div> </td>
                    </tr>
                    <tr>
                        <td>Сред.темп. отоплит. периода </td>
                        <td> <div id="city_calucl_tem_out_houses"></div> </td>
                    </tr>
                    <tr>
                        <td>Продолжит. отоп. периода </td>
                        <td> <div id="city_duration_heating_house"></div> </td>
                    </tr>
                    <tr>
                        <td>ГСОП</td>
                        <td> <div id="city_deegre_day_civil"></div> </td>
                    </tr>
                    <tr>
                        <td>Условия экспл. в зонах влажности</td>
                        <td> <div id="city_zone_ab"></div> </td>
                    </tr>
                    <tr>
                        <td> Влажность  наружного воздуха </td>
                        <td><div id="city_cold_mid_hum_month"></div></td>
                    </tr>

                </table>

            </div>

        </form>
        <form id="generalBlocksForm" name="generalBlocksForm" method="post" action="">
            <div class="block1-1">
                <table>
                    <tr>
                        <td> <strong>Блок 1.1</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><strong>Вид наружного ограждения </strong></td>
                        <td>
                            <select class="BlockTypeSelect" id="BlockTypeSelect" disabled="true">
                                <option selected disabled>Выберете вид наружного ограждения </option>
                                <?php
                                $block_types=get_id_name('block_types');
                                foreach ($block_types as $block_type){
                                    echo "<option value=' ".$block_type['id']." '>".$block_type['name']."</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td> <strong>Конструктивное решение наружного ограждения</strong>  </td>
                        <td>
                            <div>
                                <select class="BlockConsSelect" id="BlockConsSelect" disabled="true">
                                    <option selected disabled>Выберете конструктивное решение наружного ограждения</option>
                                    <?php
                                    $uniformitys=get_id_name('uniformity');
                                    foreach ($uniformitys as $uniformity){
                                        echo "<option value=' ".$uniformity['id']." '>".$uniformity['name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>

            </div>


        </form>

        <form id="generalGoodsForm" name="generalGoodsForm" method="post" action="">
            <table>
                <th> Номер </th>
                <th> Слои </th>
                <th> 'd(мм) </th>
                <th> ρ </th>
                <th> l </th>
                <th> S </th>
                <th> m </th>
                <th> R </th>
                <th> D </th>
                <th> D1+Dn </th>
                <th> Y </th>
                <th> Dw </th>
                <!--<th> n= </th>
                <th> Rи </th>
                <th> би </th> -->
                <?php $goods=get_id_name('goods'); ?>
                <tr>

                    <td>  </td>
                    <td> Коэффициент теплоотдачи внутренней поверхности </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> 0.115 </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>

                </tr>
                <?php
                for ($i=1;$i<=9;$i++) {
                    echo "<tr>";
                    echo "<td>$i."."</td>";
                    // materials
                    echo "<td> <select class='BlockSelect' id='BlockSelect_1_$i'  disabled=\"true\">";
                    echo "<option selected value='0'>Материал не выбран</option>";
                    foreach ($goods as $good){
                        echo "<option value=' ".$good['id']." '>".$good['name']."</option>";
                    }

                    echo "</select></td>";
                    echo "<td><input type='text' class='mat_depth_1' id='mat_depth_1_$i' name='mat_depth_1_$i' disabled=\"true\"> </td>";
                    echo "<td><div class='mat_dry_density_1' id='mat_dry_density_1_$i'></div></td>";
                    echo "<td><div class='mat_cal_coef_therm_cond_1' id='mat_cal_coef_therm_cond_1_$i'></div></td>";
                    echo "<td><div class='mat_area_1' id='mat_area_1_$i'></div></td>";
                    echo "<td><div class='mat_cal_coef_vapor_1' id='mat_cal_coef_vapor_1_$i'></div></td>";
                    echo "<td><div class='mat_therm_res_calc_1' id='mat_therm_res_calc_1_$i'></div></td>";
                    echo "<td><div class='mat_d_1' id='mat_d_1_$i'></div></td>";
                    echo "<td><div class='mat_d1dn_1' id='mat_d1dn_1_$i'></div></td>";
                    echo "<td><div class='mat_y_1' id='mat_y_1_$i'></div></td>";
                    echo "<td><div class='mat_dw_1' id='mat_dw_1_$i'></div></td>";
                    echo "</tr>";
                }
                ?>
                <tr>
                    <td> </td>
                    <td> Коэфециент теплоотдачи наружней поверхности </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> 0.043 </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>

                </tr>
                <tr>
                    <td> </td>
                    <td> Итого толщина </td>
                    <td> <div id="mat_summ_depth"></div></td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> Итого R </td>
                    <td> <div id="mat_summ_r"></div> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>

                </tr>


            </table>
        </form>
        <button class="addBlock"> Добавить еще один блок</button>


    </div>

    <div id="tab-2" class="tab-content">
        <table class="dataTable">
            <th colspan="1"> Теплообмен </th>
            <tr>
                <td class="dataName"> Коэффициент теплоотд. внутр. пов.  aв= </td>
                <td> <div id="temp_coef_heat"></div></td>
                <td class="dataName"> Для определения толщины изоляции Rcon = </td>
                <td> <div id="temp_rcon"></div> </td>
            </tr>
            <tr>
                <td class="dataName"> Коэффициент теплоотд. наруж.пов.  aн= </td>
                <td> <div id="temp_coef_heap_cond"></div></td>
                <td class="dataName"> Rусл= </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> Коэффициент теплотехн. однород.  rод.= </td>
                <td> <div id="temp_ratio"></div> </td>
                <td class="dataName"> Rпр= </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> Коэффициент полож.наруж поверхн.  n= </td>
                <td> <div id="temp_n"></div></td>
                <td class="dataName"> Ro.мин1= </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> Нормируемый  температур.перепад, oC Dtн= </td>
                <td> <div id="temp_diff"></div> </td>
                <td class="dataName"> Ro.мин2= </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> Темп. воздуха эксплутац. помещения tc= </td>
                <td> <input type="text" value="5"> </td>
                <td class="dataName"> Необходимая толщина изоляции = </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> Тепловая инерция  D= </td>
                <td> <div id="temp_therm_lag"></div></td>
                <td class="dataName"> Фактическая толщина изоляции= </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> 'tповерх.= </td>
                <td> <div id="temp_surface_temp"></div></td>
                <td class="dataName"> Толщина Изоляции по формуле= </td>
                <td> </td>
            </tr>

        </table>
    </div>

    <div id="tab-3" class="tab-content">
        <table class="dataTable">
            <th colspan="2"> Паропроницаемость </th>
            <tr>
                <td class="dataName"> Rпо = </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> Rп = </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName">  Rп.н. Tmp = </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> Rп.н. = </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> P = </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName">  Rп1тр= </td>
                <td> </td>
            </tr>

        </table>
    </div>


    <div id="tab-4" class="tab-content">
        <table class="dataTable">
            <th colspan="3"> Теплоустойчивость </th>
            <tr>
                <td class="dataName"> AtBтр= </td>
                <td> </td>
                <td class="dataName"> r= </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> Аtнрас= </td>
                <td> </td>
                <td class="dataName"> Iср= </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> AtB= </td>
                <td> </td>
                <td class="dataName"> aн= </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> n= </td>
                <td> </td>
                <td class="dataName"> Аtн= </td>
                <td> </td>
            </tr>
            <tr>
                <td class="dataName"> скорост ветра за Июль  v= </td>
                <td> </td>
                <td class="dataName"> Imax= </td>
                <td> </td>
            </tr>
            <tr>
                <th colspan="4"> пол </th>
            </tr>
            <tr>
                <td class="dataName"> Yпн= </td>
                <td> </td>
                <td class="dataName"> Y1= </td>
                <td> </td>
            </tr>

        </table>
    </div>
    <div id="tab-5" class="tab-content">
        <table class="dataTable">
            <th colspan="6"> Воздухонипроницаемость </th>
            <tr>
                <td> Вид ограждающей конструкции </td>
                <td colspan="5"> <select> <option> Наружные стены </option> </select> </td>
            </tr>
            <tr>
                <td> Высота здания H= </td>
                <td> 15 </td>
                <td> Dp= </td>
                <td> 38 </td>
                <td> Ghtp= </td>
                <td> 0,5 </td>
            </tr>
            <tr>
                <td> gh= </td>
                <td> 14 </td>
                <td> Rhtp= </td>
                <td> 76 </td>
                <td> Gh= </td>
                <td> 1,5 </td>
            </tr>
            <tr>
                <td> gв= </td>
                <td> 11 </td>
                <td> Ra= </td>
                <td> 235 </td>
                <td> </td>
                <td> </td>
            </tr>
            <tr>
                <td> Нормативная воздухопроницаемость </td>
                <td colspan="5" > <select> <option> Входные двери квартиры </option> </select> </td>
            </tr>
            <tr>
                <td> Rhtp= </td>
                <td> 1,64141 </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
            </tr>

        </table>
    </div>
    <div id="tab-6" class="tab-content">
        <table class="dataTable">
            <th colspan="7"> Теплобаланс </th>
            <tr>
                <td colspan="2"> Данные для ввода </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
            </tr>
            <tr>
                <td> Размеры </td>
                <td> прочие данные </td>
                <td colspan="4"> Отклонение расчетного от нормативного </td>
                <td> -5,24214 </td>
            </tr>
            <tr>
                <td> 10 </td>
                <td> bh = </td>
                <td> <select> <option> 1,13 </option> </select> </td>
                <td colspan="3"> Коэффициент остекленности фасада здания p= </td>
                <td> 0,07 </td>
            </tr>
            <tr>
                <td> 10 </td>
                <td> k = </td>
                <td> <select> <option> 1 </option> </select> </td>
                <td colspan="3"> Показатель компактности здания kedes = </td>
                <td> 0,84 </td>
            </tr>
            <td> 5,92 </td>
            <td> z = </td>
            <td> <select> <option> 0,94 </option> </select> </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            <tr>
                <td> 1 </td>
                <td> bv = </td>
                <td> 0,85 </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td> 2,1 </td>
                <td> </td>
                <td> </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

        </table>
    </div>

</div><!-- container -->
</body>
</html>