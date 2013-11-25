$(document).ready(function() {

	
	$(document).on('mouseover','a[title]',function(event){
	
		$(this).qtip({


			position:{
				
				my:'bottom right', //target
				at:'top left' //tooltipo
			},
				


			
			overwrite: false,
			
			show: {
				
				event: event.type,
				ready:true
			}			
			
			
		});
	
	});

   
/*
   $('span[title]').qtip({
      position: {
         corner: {
            target: 'topRight',
            tooltip: 'bottomRight'
         }
      },
      style: {
         name:'cream',
         padding: '10px 0px',
         margin: 0,
         width: {
            max: 210,
            min: 0
         },
         border: {
               width: 7,
               radius: 5,
               color: '#FAF7AA'
         },
         tip: true
      }
   });
   
*/   


/*
$("#qtip[title]").each(function(){
	
   $(this).qtip({
      position: {
         corner: {
	    target: 'leftBottom',
            tooltip: 'rightTop'
         }
      },
      style: {
	 name:'cream',
         padding: '2px 5px',
         width: {
            max: 500,
            min: 0
         },
         border: {
               width: 8,
               radius: 1,
               color: '#FAF7AA'
         },
         tip: true
      }
   });	
	
});
*/


/*     
   $("#qtip[title]").qtip({
      position: {
         corner: {
	    target: 'leftBottom',
            tooltip: 'rightTop'
         }
      },
      style: {
	 name:'cream',
         padding: '2px 5px',
         width: {
            max: 500,
            min: 0
         },
         border: {
               width: 8,
               radius: 1,
               color: '#FAF7AA'
         },
         tip: true
      }
   });
*/

/*

 
   
   $('span .dload').qtip({
      position: {
         corner: {
            target: 'topRight',
            tooltip: 'bottomLeft'
         }
      },
      style: {
	 name:'cream',
         padding: '5px 10px',
         width: {
            max: 350,
            min: 0
         },
         border: {
               width: 1,
               radius: 8,
               color: '#FAF7AA'
         },
         tip: true
      }
   }); 
*/   

 
});
