$().ready(function(){	
  $("#autoComplete").autocomplete("/freegal/homes/autoComplete",
  {
  minChars: 1,
  cacheLength: 10,
  
   autoFill: true
   });
  });
  
 