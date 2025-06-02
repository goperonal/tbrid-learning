$(document).ready(function () {

  // active menu
  var currentPath = window.location.pathname;

  $('#sidebar a').each(function () {
    var hrefPath = $('<a>').attr('href', $(this).attr('href'))[0].pathname;

    if (currentPath === hrefPath) {
      $(this).addClass('call-active');
    }
  });


    const $menu = $('.nav-mobile-content');
    const $toggleTop = $('#hidden-sidebar');
    const $icon = $('#menu-icon');

    // Toggle on click
    $toggleTop.on('click', function (e) {
      e.stopPropagation(); // Prevent click from bubbling to document
      $menu.toggleClass('show');
      $icon.toggleClass('fa-bars fa-times');
    });

    // Close when clicking outside
    $(document).on('click', function (e) {
      // If menu is open and click is outside both toggle and menu
      if (
        $menu.hasClass('show') &&
        !$(e.target).closest('.nav-mobile-content, #hidden-sidebar').length
      ) {
        $menu.removeClass('show');
        $icon.removeClass('fa-times').addClass('fa-bars');
      }
    });

    $('.class-collaps-class').on('click', function () {
        const $icon = $(this).find('i');
        const $box = $(this).closest('.class-box');
        const $content = $box.find('.class-collaps-content');
    
        // Close other open boxes
        $('.class-box').not($box).find('.class-collaps-content').slideUp();
        $('.class-box').not($box).find('.class-collaps-class i')
          .removeClass('fa-chevron-circle-up')
          .addClass('fa-chevron-circle-down');
    
        // Toggle current box
        $content.slideToggle();
    
        // Toggle icon direction
        $icon.toggleClass('fa-chevron-circle-down fa-chevron-circle-up');
    });


    // Sidebar toggle
    const $sidebar = $('#sidebar');
    const $sidebarContent = $('#content');
    const $iconLeft = $('.sidebar-toggle-btn i');
    const $sidebarChat = $('#zoom_right_sidebar');

    let wrapped = false; // track wrapping state

    function setupSidebarBehavior() {
        if ($(window).width() > 768) {
          // Toggle sidebar
          $('.sidebar-toggle-btn').off('click').on('click', function () {
            $sidebar.toggleClass('collapsed');
            $sidebarContent.toggleClass('content-right-collapsed');
            if ($sidebar.hasClass('collapsed')) {
              $iconLeft.removeClass('fa-angle-double-left').addClass('fa-angle-double-right');
            } else {
              $iconLeft.removeClass('fa-angle-double-right').addClass('fa-angle-double-left');
            }
          });
    
          // Scroll behavior
          $(window).off('scroll.sidebarScroll').on('scroll.sidebarScroll', function () {
            const scrollTop = $(this).scrollTop();
            const windowHeight = $(window).height();
            const documentHeight = $(document).height();
    
            if (scrollTop > 65 && !wrapped) {
              $sidebar.children().wrapAll('<div class="inside-sidebar-wrapper"></div>');
              wrapped = true;
              $sidebarChat.addClass('zoom_right_sidebar_collapse');
            } else if (scrollTop <= 65 && wrapped) {
              $sidebar.find('.inside-sidebar-wrapper').children().unwrap();
              wrapped = false;
              $sidebarChat.removeClass('zoom_right_sidebar_collapse');
            }
    
            if (scrollTop + windowHeight >= documentHeight - 100) {
              $sidebar.addClass('at-bottom');
              $sidebarChat.addClass('zoom_right_sidebar_at-bottom');
            } else {
              $sidebar.removeClass('at-bottom');
              $sidebarChat.removeClass('zoom_right_sidebar_at-bottom');
            }
          });
        } else {
          // Clean up on small screens
          $(window).off('scroll.sidebarScroll');
          $sidebar.removeClass('collapsed at-bottom');
          $sidebarChat.removeClass('zoom_right_sidebar_at-bottom');
          if (wrapped) {
            $sidebar.find('.inside-sidebar-wrapper').children().unwrap();
            wrapped = false;
            $sidebarChat.removeClass('zoom_right_sidebar_collapse');
          }
        }
      }

      function restructureClassForMobile() {
        if ($(window).width() <= 768) {
          $('.class-box').each(function () {
            // Prevent duplicate wrapping
            if ($(this).find('.class-collaps-content').length === 0) {
              const $box = $(this);
              const $badge = $box.children('span.badge');
              const $buttons = $box.children('a');
              const $floatRight = $box.children('.float-right');
    
              // Wrap into .class-collaps-content
              const $wrapper = $('<div class="class-collaps-content"></div>');
              $badge.add($buttons).add($floatRight).appendTo($wrapper);
              $box.append($wrapper);
            }
          });
        }
      }

      // Run on page load
      restructureClassForMobile();
    
      // Initial setup
      setupSidebarBehavior();
    
      // Recheck on window resize
      $(window).on('resize', function () {
        setupSidebarBehavior();
        restructureClassForMobile();
      });
});