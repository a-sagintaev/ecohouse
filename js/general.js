/*tabs*/
$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})


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

	$('.BlockSelect').on('change',function(){
		var select = $(this);
		var matID = select.val();

		var $data = {};
		$data.mat_id = matID;
		$data.city_zone_ab = $('#city_zone_ab').html();
		$data.mat_depth_1 = $('#mat_depth_1_1').val();
		$data.mat_depth_2 = $('#mat_depth_1_2').val();
		$data.mat_depth_3 = $('#mat_depth_1_3').val();
		$data.mat_depth_4 = $('#mat_depth_1_4').val();
		$data.mat_depth_5 = $('#mat_depth_1_5').val();
		$data.mat_depth_6 = $('#mat_depth_1_6').val();
		$data.mat_depth_7 = $('#mat_depth_1_7').val();
		$data.mat_depth_8 = $('#mat_depth_1_8').val();
		$data.mat_depth_9 = $('#mat_depth_1_9').val();
		$data.mat_depth_cur = select.parents('tr').find('.mat_depth_1').val();
		$data.mat_r_1 = $('#mat_therm_res_calc_1_1').html();
		$data.mat_r_2 = $('#mat_therm_res_calc_1_2').html();
		$data.mat_r_3 = $('#mat_therm_res_calc_1_3').html();
		$data.mat_r_4 = $('#mat_therm_res_calc_1_4').html();
		$data.mat_r_5 = $('#mat_therm_res_calc_1_5').html();
		$data.mat_r_6 = $('#mat_therm_res_calc_1_6').html();
		$data.mat_r_7 = $('#mat_therm_res_calc_1_7').html();
		$data.mat_r_8 = $('#mat_therm_res_calc_1_8').html();
		$data.mat_r_9 = $('#mat_therm_res_calc_1_9').html();
		$data.mat_r_cur = select.parents('tr').find('.mat_therm_res_calc_1').val();
		$data.city_temp_in = $('#city_temp_in').html();
		$data.city_temp_out = $('#city_calucl_tem_out_most_cold_5_day').html();
		$data.mat_l_1 = $('#mat_cal_coef_therm_cond_1_1').html();
		$data.mat_l_2 = $('#mat_cal_coef_therm_cond_1_2').html();
		$data.mat_l_3 = $('#mat_cal_coef_therm_cond_1_3').html();
		$data.mat_l_4 = $('#mat_cal_coef_therm_cond_1_4').html();
		$data.mat_l_5 = $('#mat_cal_coef_therm_cond_1_5').html();
		$data.mat_l_6 = $('#mat_cal_coef_therm_cond_1_6').html();
		$data.mat_l_7 = $('#mat_cal_coef_therm_cond_1_7').html();
		$data.mat_l_8 = $('#mat_cal_coef_therm_cond_1_8').html();
		$data.mat_l_9 = $('#mat_cal_coef_therm_cond_1_9').html();
		$data.mat_l_cur = select.parents('tr').find('.mat_cal_coef_therm_cond_1').val();



		if(matID){
			$.ajax({
				type:'POST',
				url:'ajaxData.php',
				data: 	$data,

				success:function(html){
					var obj=$.parseJSON(html);
					select.parents('tr').find('.mat_dry_density_1').html(obj.dry_density);
					select.parents('tr').find('.mat_cal_coef_therm_cond_1').html(obj.cal_coef_therm_cond);
					select.parents('tr').find('.mat_area_1').html(obj.area);
					select.parents('tr').find('.mat_cal_coef_vapor_1').html(obj.cal_coef_vapor);
					select.parents('tr').find('.mat_therm_res_calc_1').html(obj.therm_res_calc);
					select.parents('tr').find('.mat_d_1').html(obj.d);
					select.parents('tr').find('.mat_d1dn_1').html(obj.d1dn);
					select.parents('tr').find('.mat_y_1').html(obj.y);
					select.parents('tr').find('.mat_dw_1').html(obj.dw);
					select.parents('tr').find('.temp_coef_heat_1').html(obj.coef_heat);
					$('#mat_summ_depth').html(obj.summ);
					$('#temp_therm_lag').html(obj.therm_lag);
					$('#temp_surface_temp').html(obj.surface_temp);
					$('#mat_summ_r').html(obj.summ_r);




				}
			});
		}
	});

	$('#BlockTypeSelect').on('change',function(){
		var BlockTypeID = $(this).val();
		if(BlockTypeID){
			$.ajax({
				type:'POST',
				url:'ajaxData.php',
				data:'blocktype_id='+BlockTypeID,
				success:function(html){
					var obj=$.parseJSON(html);
					$('#temp_coef_heat').html(obj.coef_heat);
					$('#temp_coef_heap_cond').html(obj.coef_heap_cond);
					$('#temp_n').html(obj.n);
					$('#temp_diff').html(obj.diff);
				}
			});
		}
	});

	$('#BlockConsSelect').on('change',function(){
		var BlockConsID = $(this).val();
		if(BlockConsID){
			$.ajax({
				type:'POST',
				url:'ajaxData.php',
				data:'blockcons_id='+BlockConsID,
				success:function(html){
					var obj=$.parseJSON(html);
					$('#temp_ratio').html(obj.ratio);
				}
			});
		}
	});




})
