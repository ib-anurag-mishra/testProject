$(document).ready(function()
{
	/*
   $('a[title]').qtip({
      position: {
         corner: {
            target: 'topLeft',
            tooltip: 'bottomRight'
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
   

	$('a[title]').qtip({
		position: {
			corner: {
				target: 'topLeft',
				tooltip: 'bottomRight'
			}
		},
		style: { 
				
				color:'#444',
				fontSize:12,
				border: {

					color: '#444'
				},
				
				width: {
					max:350,
					min:0
				},
		
				tip:{
					corner:'bottomRight',
					size: {
						x:5,
						y:5
					}
				}
				
		
		}
	});

   
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
 
});
