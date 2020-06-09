/**
 * This file is part of 'venedi-group.pl' project.
 * 'venedi-group.pl' is page with informations about
 * "VENEDI exploration group".
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */
$(document).ready(
  function() {
    'use strict';

/*================================[ CLASSES ]=================================*/

    class AdjustPage {

      /**
       * 1) Calculating height '.main_and_aside'
       */
      static calcHeight() {
        $('.main_and_aside').css(
          'min-height', 
          `${Math.max(651, $(window)[0].innerHeight).toString()}px`);
      }

      /**
       * 2) Detecting scrollbars on site
       */
      static detectScrollBars() {
        const WIDTH_OF_BODY_WITH_BARS = 
            $('div.main_and_aside')[0].clientWidth;
        const WIDTH_OF_BODY_WITHOUT_BARS = 
            $('body')[0].clientWidth;
        const BARS_WIDTH = 
            WIDTH_OF_BODY_WITH_BARS - WIDTH_OF_BODY_WITHOUT_BARS;
        if (BARS_WIDTH == 15) {
          $('div.main_and_aside').css(
              'width', 
              `${($(window)[0].innerWidth - 15).toString()}px`);
        }
      }
      
      /**
       * 3) Window resize handling
       */
      static windowResize() {
        if ($('#my_article').height() > 661) {
          $('.main_and_aside').css(
              'height', 
              $('#my_article').height() + 20);
          $('#text_content').css(
              'width', 
              'calc(100% - 235px)');
        }
        if ($('.main_and_aside').width() <= 275) {
          $('#text_content').css(
              'width', 
              $('.main_and_aside').width() - 235 + 40);
        }
      }
    }

/*===========================[ EXECUTED FUNCTIONS ]===========================*/
    /**
     * 1) Handling window resize
     */
    $(window).resize(function() {
      AdjustPage.windowResize();
      AdjustPage.calcHeight();
    });

    /**
     * 2) Calc HEIGHT OF .main_and_aside after 'document' is loaded; 
     * and resize window after loading:
     */
    AdjustPage.windowResize();
    AdjustPage.calcHeight();

    /**
     * N) TESTS:
     */
  }
);
/*............................................................................*/