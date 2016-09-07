(function( $ ) {
	'use strict';

	$(document).on('ready', function(){

		$('.wcsh-set-holiday-status').on('click', function(evt){

			evt.preventDefault();

			var row = $(evt.currentTarget).closest('div');
			var value = row.find('.wcsh-date:first').datepicker('getDate');

			if(!value){
				return;
			}

			var date = new Date(value);
			//date.setHours(0);

			date.setMinutes(new Date().getMinutes());
			date.setHours(new Date().getHours());
			date.setSeconds(date.getSeconds()+120);

			if(isNaN(date.getTime())){
				return;
			}


			$.ajax({

				url: $(evt.currentTarget).attr('href'),
				type: 'POST',
				data:{
					date: date.getTime()/1000,
					id: $(evt.currentTarget).attr('data-id'),
					key: $(evt.currentTarget).attr('data-key')
				},
				success: function(data){
					
					location.reload();

				}

			});

		})

		$('.wcsh-date').datepicker({
			dateFormat: 'dd/mm/yy',
			minDate: 1
		});


	});


})( jQuery );
