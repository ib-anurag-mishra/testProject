jQuery(document).ready(function($)
{
  //hide the all of the element with class msg_body
  $(".answer").hide();
  //toggle the componenet with class msg_body
  $(".question").click(function()
  {
    $(this).next(".answer").slideToggle(600);
  });
});