/*tabs*/


$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	});
	
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
		
		if($(this).val() != 0)
		{
			$('#BlockTypeSelect').prop('disabled', false);
		}
		else
		{
			$('#BlockTypeSelect').prop('disabled', true);
		}
		
	});

	$('.BlockSelect').on('change',function(){
		var block_1_id = this.id.substring(this.id.length -1, this.id.length);

	    var select = $(this);
		var matID = select.val();

		var $data = {};
		$data.mat_id = matID;
		$data.city_zone_ab = $('#city_zone_ab').html();

		$data.mat_depth = new Array();
		for(var k = 1; k < 10; k++)
		{
			/*console.log('k = ' + k);*/
			$data.mat_depth[k] = $('#mat_depth_1_' + k).val();
			/*console.log('$data.mat_depth[k] = ' + $data.mat_depth[k]);*/
		}
		$data.mat_depth_cur = select.parents('tr').find('.mat_depth_1').val();

        $data.mat_r = new Array();
        for(var k = 1; k < 10; k++)
        {
            $data.mat_r[k] = $('#mat_therm_res_calc_1_' + k).html();
        }
		$data.mat_r_cur = select.parents('tr').find('.mat_therm_res_calc_1').val();

        $data.city_temp_in = $('#city_temp_in').html();
		$data.city_temp_out = $('#city_calucl_tem_out_most_cold_5_day').html();

        $data.mat_l = new Array();
        for(var k = 1; k < 10; k++)
        {
            $data.mat_l[k] = $('#mat_cal_coef_therm_cond_1_' + k).html();
        }

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
					$('#temp_rcon').html(obj.rcon);
				}
			});
		}

        k=parseInt(block_1_id)+1;
        if (k<=9)
        {
            if($(this).val() != 0)
            {

                $('#BlockSelect_1_'+ k).prop('disabled', false);
            }
            else {
                $('#BlockSelect_1_' + k).prop('disabled', true);
            }
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
        if($(this).val() != 0)
        {
            $('#BlockConsSelect').prop('disabled', false);
        }
        else
        {
            $('#BlockConsSelect').prop('disabled', true);
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
        if($(this).val() != 0)
        {
            $('#BlockSelect_1_1').prop('disabled', false);
        }
        else
        {
            $('#BlockSelect_1_1').prop('disabled', true);
        }
	});
	
	
	// $('.mat_depth_1').on('change',function(){
		
	// });
	
});


// TODO: выделить логику сбора / простановки данных в отдельные функции и вызывать на разных событиях
// Например, $data = collectData();