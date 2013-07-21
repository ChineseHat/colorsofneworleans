$(document).ready(function(){

  var uri = window.location.pathname.replace("/","");
  if (uri == '') { uri = 'home'; }
  $("#menu a").each(function(){
    if($(this).attr("class").indexOf(uri) != -1){
      $(this).addClass('active');
    }
  });
});