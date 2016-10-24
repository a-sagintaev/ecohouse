<?php
/* TODO: вынести подключение к БД из функций в dbConfig.php.
/**********************************************
функции (хорошобы вынести их отсюда в подключаемый файл)
 **********************************************/
function get_cities()
{
	$username = "ecohouse";
	$password = "MlkmZIceCASc3S";
	$database = "ecohouse";

	$db = new mysqli("localhost", $username, $password,$database);

	if (mysqli_connect_errno()) {
		echo mysqli_connect_error();
	}
	$db->query("SET NAMES utf8");

	$result=$db->query("SELECT id, name, is_region FROM cities ");
	if($result){
		// Cycle through results
		while ($row = $result->fetch_assoc()){
			$cities[] = $row;
		}
		// Free result set
		$result->close();
	}
	return $cities;
	$db->close();
}

function get_id_name($table)
{
	$username = "ecohouse";
	$password = "MlkmZIceCASc3S";
	$database = "ecohouse";

	$db = new mysqli("localhost", $username, $password,$database);

	if (mysqli_connect_errno()) {
		echo mysqli_connect_error();
	}
	$db->query("SET NAMES utf8");
	$result=$db->query("SELECT id, name FROM $table");
	if($result){
		// Cycle through results
		while ($row = $result->fetch_assoc()){
			$data_array[] = $row;
		}
		// Free result set
		$result->close();
	}
	return $data_array;
	$db->close();
}

/**********************************************
начинаем процедуру а потом и вывод контента
 **********************************************/



// отлавливаем ajax-запрос


?>



<!DOCTYPE html>
<html>
<head>
	<?php header('Content-Type: charset=utf-8');?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Home Energy Saver</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="js/general.js"></script>





</head>
<div id="topHeader">
<!-- шапка -->
</div>
<body>
<?php include('dbConfig.php');?>


	<div class="container">


		<ul class="tabs">
			<li class="tab-link current" data-tab="generalInfo">Общие данные</li>
			<li class="tab-link" data-tab="tab-2">Теплообмен</li>
			<li class="tab-link" data-tab="tab-3">Паропроницаемость</li>
			<li class="tab-link" data-tab="tab-4">Теплоустойчивость</li>
			<li class="tab-link" data-tab="tab-5">Воздухопроницаемость</li>
			<li class="tab-link" data-tab="tab-6">Теплобаланс</li>
		<!--	<li class="tab-link" data-tab="tab-7">Экономическая эффективность</li>
			<li class="tab-link" data-tab="tab-8">Экология</li> -->
		</ul>

		<div id="generalInfo" class="tab-content current">
		
		 <form id="generalInfoForm" name="generalInfoForm" method="post" action="">
			
			<select id="citySelect" name="citySelect">
				<option value='null'>- Город -</option>
				<?php
				$cities=get_cities();
				foreach ($cities as $city){
					if ($city['is_region'] == 1) {
						echo "<option value=' " . $city['id'] . " '>" . $city['name'] . "</option>";
					} else {
						echo "<option value=' " . $city['id'] . " '>" ."-". $city['name'] . "</option>";
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
							<select class="selectBox" style="width:400px;">
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
							<select class="selectBox" style="width:400px;">
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
			<th> n= </th>
			<th> Rи </th>
			<th> би </th>
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
				<td> </td>
				<td> </td>
				<td> </td>
			</tr>
			 <?php
			 for ($i=1;$i<=9;$i++) {
				 echo "<tr>";
				 echo "<td>$i."."</td>";
				 // materials
				 echo "<td> <select id='BlockSelect1_$i' name='BlockSelect1_$i' style=' width:400px;'>";

						foreach ($goods as $good){
							echo "<option value=' ".$good['id']." '>".$good['name']."</option>";
						}

				 echo "</select></td>";
				 echo "<td><input type='text' name='mat_depth_1_$i'> </td>";
				 echo "<td><div id='mat_dry_density_1_$i'></div></td>";
				 echo "<td><div id='mat_cal_coef_therm_cond_1_$i'></div></td>";
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
				<td> </td>
				<td> </td>
				<td> </td>
			</tr>
			<tr>
				<td> </td>
				<td> Итого толщина </td>
				<td> 576.500</td>
				<td> </td>
				<td> </td>
				<td> </td>
				<td> Итого R </td>
				<td> 4.761 </td>
				<td> </td>
				<td> </td>
				<td> </td>
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
					<td> </td>	
					<td class="dataName"> Для определения толщины изоляции Rcon = </td>
					<td> </td>					
				</tr>
				<tr>
					<td class="dataName"> Коэффициент теплоотд. наруж.пов.  aн= </td>
					<td> </td>	
					<td class="dataName"> Rусл= </td>
					<td> </td>					
				</tr>
				<tr>
					<td class="dataName"> Коэффициент теплотехн. однород.  rод.= </td>
					<td> </td>	
					<td class="dataName"> Rпр= </td>
					<td> </td>					
				</tr>
				<tr>
					<td class="dataName"> Коэффициент полож.наруж поверхн.  n= </td>
					<td> </td>	
					<td class="dataName"> Ro.мин1= </td>
					<td> </td>					
				</tr>
				<tr>
					<td class="dataName"> Нормируемый  температур.перепад, oC Dtн= </td>
					<td> </td>	
					<td class="dataName"> Ro.мин2= </td>
					<td> </td>					
				</tr>
				<tr>
					<td class="dataName"> Темп. воздуха эксплутац. помещения tc= </td>
					<td> <input type="text"> </td>	
					<td class="dataName"> Необходимая толщина изоляции = </td>
					<td> </td>					
				</tr>
				<tr>
					<td class="dataName"> Тепловая инерция  D= </td>
					<td> </td>	
					<td class="dataName"> Фактическая толщина изоляции= </td>
					<td> </td>					
				</tr>
				<tr>
					<td class="dataName"> 'tповерх.= </td>
					<td> </td>	
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

	</div><!-- container -->




</body>

</html>