$(document).ready(function()
{
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
   $("#qtip").qtip({
      content : "In order to provide Freegal Music to the greatest number of library patrons, each patron has a weekly download limit, which is the same for all accounts.   A counter appears in the upper right corner of the Freegal Site that informs you of your weekly allotment.  For instance, 1/5 means that you have a weekly limit of 5 downloads, and you have used 1 of those downloads.   Each week the counter resets to zero used.The individual limit is different than the library's overall ability to offer downloads to patrons as a whole.  It is possible from time to time that the library could run out of downloads for the patron community, just as your favorite cd, dvd or book might not be in the library at any one time.",
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
               width: 1,
               radius: 8,
               color: '#FAF7AA'
         },
         tip: true
      }
   });  
});
