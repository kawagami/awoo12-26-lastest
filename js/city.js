(function($) {
	
    $.fn.citySelect = function(options) {
    	options = $.extend({  
            cityJson: "city.json",
           	cityTraget: ""
        }, options);
        	var city;
        	var element = this;
        	var element_tmp = $(this).attr("id");
        	var element_for = $(this).attr("for");
		$.getJSON(options.cityJson, function(data){
			$(element).html('<select id="sel_'+element_tmp+'"><option value="">未選擇</option></select><select id="selc_'+element_tmp+'"><option value="">未選擇</option></select>');
			city = data;
			
			$.each(data, function(i,item){
				$("#sel_"+element_tmp).append('<option>'+i+'</option>');
			});
		});
		
		$("#sel_"+element_tmp).live('change', function() {
			var itemKey = $(this).val();
			var option;
			$.each(city[itemKey], function(i,item){
				option += '<option value="'+i+'">'+item+'</option>';
			});
			$("#selc_"+element_tmp).html(option);
			setCityTraget();
		})
		$("#selc_"+element_tmp).live('change', function() {
			setCityTraget();
		})
		function setCityTraget(){
			$("#"+options.cityTraget).val( $("#selc_"+element_tmp).val() + $("#sel_"+element_tmp).val() + $("#selc_"+element_tmp+" :selected").text() );
			$('#kit_county').val( $("#sel_"+element_tmp).val() );
			$('#kit_city').val( $("#selc_"+element_tmp+" :selected").text() );
			
		
		}
	}
})(jQuery); 
