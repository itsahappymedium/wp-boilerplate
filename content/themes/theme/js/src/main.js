(function ($) {

  // Toggles mobile navigation
  $('header .header-navigation-btn').click(function (e) {
    var $me = $(e.currentTarget)
    var opening = !$me.hasClass('open')
    $me.add('.header-navigation').toggleClass('open', opening)
    $('body').toggleClass('mobile-nav-open', opening)
  })


  // Toggles mobile sub-navigation
  $('header .menu-item-has-children > a').click(function (e) {
    var $me = $(e.currentTarget)
    var $parent = $me.parent()
    var opening = !$parent.hasClass('open')
    $parent.toggleClass('open', opening)
  })


  // Makes the footer stay at the bottom of the page
  function bottomFooter () {
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
  }
  $(window).on('load resize', bottomFooter)
  var resizeObserver = new ResizeObserver(bottomFooter)
  resizeObserver.observe(document.body)

})(jQuery)
