// Stores are nice-select instances so we can reference them later
var niceSelectInstances = {};

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
    var percentX = (e.pageX / window.innerWidth * 100)
    if (window.innerWidth > 1250 || percentX < 90) return
    e.preventDefault()
    var $me = $(e.currentTarget)
    var $parent = $me.parent()
    var opening = !$parent.hasClass('open')
    $parent.toggleClass('open', opening)
  })


  // Initializes nice-select2 - Used for select inputs - https://github.com/bluzky/nice-select2
  function initializeNiceSelect (element) {
    $(element || 'select:not(.not-nice)').each(function (idx, select) {
      var niceSelectInstance = NiceSelect.bind(select, {
        placeholder: select.dataset.placeholder,
        searchable: Object.keys(select.dataset).includes('searchable'),
        searchtext: select.dataset.searchtext,
        selectedtext: select.dataset.selectedtext
      })

      if (select.id) {
        niceSelectInstances[select.id] = niceSelectInstance
      }
    })
  }
  initializeNiceSelect()


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
