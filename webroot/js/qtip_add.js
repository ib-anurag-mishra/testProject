$(document).ready(function() {

	$(document).on('mouseenter','a[title]',function(event){
	
		$(this).qtip({

			
			position: {
				at: 'top left',
				my: 'bottom right'
				
			},
			overwrite: false,
			
			show: {
				
				event: event.type,
				ready:true
			}			
			
			
		});
	
	});
	



   $(document).on('mouseenter','span[title]',function(event){
   
	   $(this).qtip({
			position: {
			 
			    at: 'top right',
			    my: 'bottom right'
			 
			},
			overwrite: false,
			
			show: {
				
				event: event.type,
				ready:true
			}	
	   });   
   
   });

   
   


   $(document).on('mouseenter','#qtip[title]',function(event){
   
	   $(this).qtip({
			position: {
			 
			    at: 'left bottom',
			    my: 'right top'
			 
			},
			overwrite: false,
			
			show: {
				
				event: event.type,
				ready:true
			}	
	   });   
   
   });

      

   $(document).on('mouseenter','span .dload',function(event){
   
	   $(this).qtip({
			position: {
			 
			    at: 'top right',
			    my: 'bottom left'
			 
			},
			overwrite: false,
			
			show: {
				
				event: event.type,
				ready:true
			}	
	   });   
   
   });



 
   
 
   

 
});
