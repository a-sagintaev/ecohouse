/*tabs*/
$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})

	/* ����� �� default*/
	$("#citySelect").val('null');



	$('#citySelect').on('change',function(){
		var cityID = $(this).val();
		if(cityID){
			$.ajax({
				type:'POST',
				url:'ajaxData.php',
				data:'city_id='+cityID,
				success:function(html){
					var obj=$.parseJSON(html);
					$('#city_calucl_tem_out_houses').html(obj.calucl_tem_out_houses);
					$('#city_temp_in').html(obj.city_temp_in);
					$('#city_vapor_in').html(obj.vapor);
					$('#city_dew_temp_point').html(obj.dew_temp_point);
					$('#city_calucl_tem_out_most_cold_5_day').html(obj.calucl_tem_out_most_cold_5_day);
					$('#city_duration_heating_house').html(obj.duration_heating_house);
					$('#city_deegre_day_civil').html(obj.deegre_day_civil);
					$('#city_zone_ab').html(obj.zone_ab);
					$('#city_cold_mid_hum_month').html(obj.cold_mid_hum_month);

				}
			});
		}
	});



	$('#BlockSelect1_1').on('change',function(){
		var matID = $(this).val();
		if(matID){
			$.ajax({
				type:'POST',
				url:'ajaxData.php',
				data:'mat_id='+matID,
				success:function(html){
					var obj=$.parseJSON(html);
					$('#mat_dry_density_1_1').html(obj.dry_density);
					$('#mat_cal_coef_therm_cond_1_1').html(obj.cal_coef_therm_cond);

				}
			});
		}
	});






})
