$(function () {
 // $(window).scroll(function(){
 //  var scrollTop = $(".target").offset().top - $(window).scrollTop();
 //  console.log(scrollTop);
 //  if(scrollTop<=500){
 //    $(".target").animate({opacity:1},0);
 //  } 
 //  if(scrollTop>500){
 //    $(".target").animate({opacity:0},0);
 //  } 
 // });

  $('.menu-id').click(function () {
    var hashidden = $(this).next().hasClass('hidden');
    if (hashidden) {
      $(this).find('.menu-submenu-arrow').html('&#xe7eb;');
      $(this).addClass('menu-submenu-title-active').next().removeClass('hidden');
    } else {
      $(this).find('.menu-submenu-arrow').html('&#xe7ec;');
      $(this).removeClass('menu-submenu-title-active').next().addClass('hidden');
    }
  });


  $('.skill-image').mouseover( function () { 
    $(this).prev().removeClass(' circleOut').removeClass(' hidden').addClass(' circleIn animated');
  });
  $('.skill-image').mouseout( function () { 
    $(this).prev().addClass(' circleOut animated');
  });


  $('#touch-list').click( function () {
    if ($('#touch-list-menu').hasClass('hidden')) {
      $('#touch-list-menu').removeClass('hidden');
    } else {
      $('#touch-list-menu').addClass('hidden');
    }
  });

  $('#handle-list').click( function () {
    if ($('#handle-list-menu').hasClass('hidden')) {
      $('#handle-list-menu').removeClass('hidden');
    } else {
      $('#handle-list-menu').addClass('hidden');
    }
  });

});