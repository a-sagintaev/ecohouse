/*tabs*/

/*console.log('$data.mat_depth[k] = ' + $data.mat_depth[k]);*/

function collect_block_depth(fmat_id, fblock_id){
	var $fdata = {};

	$fdata.block_id=fblock_id;
	$fdata.mat_depth = new Array();
	for(var k = 0; k < 9; k++)
	{
		n=k+1;
		$fdata.mat_depth[k] = $('#mat_depth_1_' + n).val();
	}

	$fdata.mat_id=fmat_id;


	return $fdata;

}


function get_block_data(fdata, fmat_id, fselect) {
	fdata=arguments[0];
	fmat_id=arguments[1];
	fselect=arguments[2];

	if(fmat_id){
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
				$('#mat_summ_depth').html(obj.summ_depth);
				$('#temp_therm_lag').html(obj.therm_lag);
				$('#temp_surface_temp').html(obj.surface_temp);
				$('#mat_summ_r').html(obj.summ_r);
				$('#temp_rcon').html(obj.rcon);
			}
		});
	}
}

function get_city_data(cityID)
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
			$('#city_deegre_day_houses').html(obj.deegre_day_houses);
			$('#city_zone_ab').html(obj.zone_ab);
			$('#city_cold_mid_hum_month').html(obj.cold_mid_hum_month);
		}
	});
}

function get_block_type_data(BlockTypeID) {
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

function get_block_cons_data(BlockConsID) {
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
			get_city_data(cityID);
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
		get_city_data($('#citySelect').val());
	}



	$('.BlockSelect').on('change',function(){
		var block_1_id = this.id.substring(this.id.length -1, this.id.length);
	    var select = $(this);
		var mat_id = select.val();

		var $data = collect_block_depth(mat_id, block_1_id);
		get_block_data($data, mat_id, select);

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
	for (var block_i = 1; block_i <= 9; block_i++) {
		if ($('#BlockSelect_1_'+block_i).val() != 0) {
			var select = $('#BlockSelect_1_'+block_i);  //<select#BlockSelect_1_1.BlockSelect>
			var $data = collect_block_depth($('#BlockSelect_1_'+block_i).val(), block_i);
			get_block_data($data, $('#BlockSelect_1_'+block_i).val(), select);
		}
	}


	$('#BlockTypeSelect').on('change',function(){
		var BlockTypeID = $(this).val();
		if(BlockTypeID){
			get_block_type_data(BlockTypeID);
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


	if($('#BlockTypeSelect').val() != 0)
	{
		get_block_type_data($('#BlockTypeSelect').val());
	}


	$('#BlockConsSelect').on('change',function(){
		var BlockConsID = $(this).val();
		if(BlockConsID){
			get_block_cons_data(BlockConsID);
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

	if($('#BlockConsSelect').val() != 0)
	{
		get_block_cons_data($('#BlockConsSelect').val());
	}


	$('.mat_depth_1').on('change',function(){

		var block_1_id = this.id.substring(this.id.length -1, this.id.length);
		var select = $('#BlockSelect_1_'+ block_1_id);
		var mat_id = $('#BlockSelect_1_'+ block_1_id).val();

		var $data = collect_block_depth(mat_id,block_1_id);

		get_block_data($data, mat_id, select);
	});
	$('.mat_depth_1').on('keyup',function(){

		var block_1_id = this.id.substring(this.id.length -1, this.id.length);
		var select = $('#BlockSelect_1_'+ block_1_id);
		var mat_id = $('#BlockSelect_1_'+ block_1_id).val();

		var $data = collect_block_depth(mat_id,block_1_id);

		get_block_data($data, mat_id, select);
	});
});
