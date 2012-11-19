/* Author:

*/
$(document).ready(function () {
  $('.carousel').carousel({
    interval: 2000
  });

  $(window).scroll(function () {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
      // Load more
    }
  });

});




