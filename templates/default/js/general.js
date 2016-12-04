/*tabs*/

/*console.log('$data.mat_depth[k] = ' + $data.mat_depth[k]);*/

function collect_block_depth(fmatID, fblockID){
	var $fdata = {};

	$fdata.blockID=fblockID;
	$fdata.mat_depth = new Array();
	for(var k = 1; k < 10; k++)
	{
		$fdata.mat_depth[k] = $('#mat_depth_1_' + k).val();
	}

	$fdata.mat_id=fmatID;

	/* @@@@@ Not needed, I hope @@@@@@

	$fdata.mat_depth_cur = fselect.parents('tr').find('.mat_depth_1').val();
	$fdata.mat_r_cur = fselect.parents('tr').find('.mat_therm_res_calc_1').val();
	$fdata.city_zone_ab = $('#city_zone_ab').html();
	$fdata.city_temp_in = $('#city_temp_in').html();
	$fdata.city_temp_out = $('#city_calucl_tem_out_most_cold_5_day').html();
	$fdata.mat_r = new Array();

	for(var k = 1; k < 10; k++)
	{
		$fdata.mat_r[k] = $('#mat_therm_res_calc_1_' + k).html();
	}
	$fdata.mat_l = new Array();
	for(var k = 1; k < 10; k++)
	{
		$fdata.mat_l[k] = $('#mat_cal_coef_therm_cond_1_' + k).html();
	}

	$fdata.mat_l_cur = fselect.parents('tr').find('.mat_cal_coef_therm_cond_1').val();
	*/

	return $fdata;

}


function print_block_data(fdata, fmatID, fselect) {
	fdata=arguments[0];
	fmatID=arguments[1];
	fselect=arguments[2];

	if(fmatID){
		$.ajax({
			type:'POST',
			url:'/',
			data: 	fdata,

			success:function(html){
				var obj=$.parseJSON(html);
				fselect.parents('tr').find('.mat_dry_density_1').html(obj.dry_density);
				fselect.parents('tr').find('.mat_cal_coef_therm_cond_1').html(obj.cal_coef_therm_cond);
				fselect.parents('tr').find('.mat_area_1').html(obj.area);
				fselect.parents('tr').find('.mat_cal_coef_vapor_1').html(obj.cal_coef_vapor);
				fselect.parents('tr').find('.mat_therm_res_calc_1').html(obj.therm_res_calc);
				fselect.parents('tr').find('.mat_d_1').html(obj.d);
				fselect.parents('tr').find('.mat_d1dn_1').html(obj.d1dn);
				fselect.parents('tr').find('.mat_y_1').html(obj.y);
				fselect.parents('tr').find('.mat_dw_1').html(obj.dw);
				fselect.parents('tr').find('.temp_coef_heat_1').html(obj.coef_heat);
				$('#mat_summ_depth').html(obj.summ);
				$('#temp_therm_lag').html(obj.therm_lag);
				$('#temp_surface_temp').html(obj.surface_temp);
				$('#mat_summ_r').html(obj.summ_r);
				$('#temp_rcon').html(obj.rcon);
			}
		});
	}
}

function getCityData(cityID)
{
	$.ajax({
		type:'POST',
		url:'/',
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


$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	});

	// city data
	$('#citySelect').on('change',function(){
		var cityID = $(this).val();
		if(cityID){
			getCityData(cityID);
			if(cityID != 0)
			{
				$('#BlockTypeSelect').prop('disabled', false);
			}
			else
			{
				$('#BlockTypeSelect').prop('disabled', true);
			}
		}
	});
	if($('#citySelect').val() != 0)
	{
		getCityData($('#citySelect').val());
	}


	$('.BlockSelect').on('change',function(){
		var block_1_id = this.id.substring(this.id.length -1, this.id.length);
	    var select = $(this);
		var matID = select.val();

		var $data = collect_block_depth(matID, block_1_id);
		print_block_data($data, matID, select);

        k=parseInt(block_1_id)+1;
        if (k<=9)
        {
            if($(this).val() != 0)
            {
				$('#mat_depth_1_'+ block_1_id).prop('disabled', false);
                $('#BlockSelect_1_'+ k).prop('disabled', false);
            }
            else {
				$('#mat_depth_1_'+ block_1_id).prop('disabled', true);
                $('#BlockSelect_1_' + k).prop('disabled', true);
            }
        }
	});

	$('#BlockTypeSelect').on('change',function(){
		var BlockTypeID = $(this).val();
		if(BlockTypeID){
			$.ajax({
				type:'POST',
				url:'/',
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
				url:'/',
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
	
	
	$('.mat_depth_1').on('change',function(){

		var block_1_id = this.id.substring(this.id.length -1, this.id.length);
		var select = $('#BlockSelect_1_'+ block_1_id);
		var matID = $('#BlockSelect_1_'+ block_1_id).val();

		var $data = collect_block_depth(matID,block_1_id);

		print_block_data($data, matID, select);
	});
	$('.mat_depth_1').on('keyup',function(){

		var block_1_id = this.id.substring(this.id.length -1, this.id.length);
		var select = $('#BlockSelect_1_'+ block_1_id);
		var matID = $('#BlockSelect_1_'+ block_1_id).val();

		var $data = collect_block_depth(matID,block_1_id);

		print_block_data($data, matID, select);
	});
});
