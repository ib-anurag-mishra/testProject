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
         name: 'cream',
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
