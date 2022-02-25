(function ($) {

  // Toggles mobile navigation
  $('.header-navigation-button').click(function (e) {
    var $me = $(e.currentTarget)
    var opening = !$me.hasClass('open')
    $me.add('.header-navigation').toggleClass('open', opening)
  })


  // Make the footer stay at the bottom of the page
  $(window).on('load resize', function () {
    if ($('body').outerHeight(true) < window.innerHeight) {
      $('footer').css({
        position: 'fixed',
        bottom: 0,
        left: 0,
        right: 0
      })
    } else {
      $('footer').removeAttr('style')
    }
  })

})(jQuery)
