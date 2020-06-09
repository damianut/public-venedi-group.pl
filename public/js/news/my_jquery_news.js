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

/*================================[ CLASSES ]================================*/

    class AdjustPagePhotos {
      /**
       * 1) COLLAPSE (establishing, which text to show after click):
       */
      static collapseMy(){
          const CSS_SELECTOR = 'a.non-stretched-link.font-custom';
          const LINKS = $(CSS_SELECTOR);
          const COLLAPSE_FUNCTION = $(CSS_SELECTOR).collapse;
          for (let i=0; i<LINKS.length; i++) {
            LINKS[i].addEventListener('click', function() {
              COLLAPSE_FUNCTION.call(
                  $(CSS_SELECTOR).parent().siblings(`:eq(${i})`),
                  'toggle');
            });
          }
        }
        
      /**
       * 2) ❮ ❯ (next&previous) buttons positioning
       */
      static previousAndNextButtonsPositioning() {
        const MARGIN_OF_MODAL_CONTENT = 
        Number($('.modal-content').css('margin-left').slice(0, -2));
        /**
         * Horizontal position "position_x" depends on:
         * -margin of ".modal-content" i.e. "const margin" 
         * -width of buttons ❮ and ❯ i.e. 25.141
         */
        const POSITION_X = 
        MARGIN_OF_MODAL_CONTENT === 0 ? 
        0 : `${(MARGIN_OF_MODAL_CONTENT - 25.141).toString()}px`;
        
        /**
         * Vertical position "position_y" depends on:
         * -height of "#img01" i.e. visible picture
         * -height of ❮ and ❯ i.e. 17.5
         * -top padding of picture
         */
        const POSITION_Y = 
        `${(($('#img01')[0].height / 2) - 17.5 + 100).toString()}px`;
        $('.prev').css({
            'left': POSITION_X,
            'top': POSITION_Y
        });
        $('.next').css({
            'right': POSITION_X,
            'top': POSITION_Y
        });
      }
      /**
       * 3) Modal switching - function which makes Modal visible or not,
       * start and stop eventListeners attached with switching
       */
      static offEventListeners() {
        $('#myModal').off('touchstart');
        $('a.prev').off('click');
        $('a.next').off('click');
        $(document).off('keydown');
        $('#myModal')[0].style.display = 'none';
        $('window').off('resize', () => { 
          AdjustPagePhotos.previousAndNextButtonsPositioning();
        });
      }
      
      /**
       * 4) Slides showing
       */
      static showSlides(slideIndex, slides) {
        $('#img01')[0].src = slides[slideIndex].src;
        $('#caption').text(slides[slideIndex].alt);
        this.previousAndNextButtonsPositioning();
      }
      /**
       * 5) Detect parameters of photo
       */
      static detectImgInAlbum(plusOrMinus) {
        for (let i = 0; i < $('img').length; i++) {
          if ($('img')[i].src === $('#img01')[0].src && 
              $('img')[i].className === 'myImg') {
            let imgParent = 
                $('img')[i].parentElement.getElementsByTagName('img');
            for (let j = 0; j < imgParent.length; j++) {
              if (imgParent[j] === $('img')[i]) {
                if ((j + plusOrMinus) > (imgParent.length - 1)) {
                  j = 0;
                } else if (j + plusOrMinus < 0) {
                  j = imgParent.length - 1;
                } else {
                  j += plusOrMinus;
                }
                this.showSlides(j, imgParent);
                j = imgParent.length;
                i = $('img').length;
              }
            }
          }
        }
      }
      
      /**
       * 6) Handling Keyboard Event:
       */
      static keyboardOperating(clickedKey) {
        const CLICKED_KEY_CODE = clickedKey.keyCode;
          switch (CLICKED_KEY_CODE) {
            case 27:
              this.offEventListeners();
              break;
            case 37:
              this.detectImgInAlbum(-1);
              break;
            case 39:
              this.detectImgInAlbum(1);
              break;
          }
      }
      
      /**
       * 7) Handling swipe on touch-based devices 
       */
      static swipe(evt1) {
        $('#myModal').one('touchmove', () => {
          $('#myModal').one('touchend', evt3 => {
            const DELTA_X = 
                evt3.changedTouches[0].clientX - evt1.touches[0].clientX;
            if (DELTA_X > 0){
              this.detectImgInAlbum(1);
            }
            else if (DELTA_X < 0){
              this.detectImgInAlbum(-1);
            }
          });
        });
      }
      
      /**
       * 8) Albums browsing
       */
      static browsingAlbums(clickedImg) {
        $('#myModal')[0].style.display = 'block';
        $('#img01')[0].src = clickedImg.src;
        $('#caption').text(clickedImg.alt);
        this.previousAndNextButtonsPositioning();
        $(window).on('resize', () => { 
            AdjustPagePhotos.previousAndNextButtonsPositioning();
        });
        $('#myModal').on('touchstart', evt1 => { 
            AdjustPagePhotos.swipe(evt1);
        });
        $('.close').one('click', () => { 
            AdjustPagePhotos.offEventListeners();
        });
        $('a.prev').on('click', () => { 
            AdjustPagePhotos.detectImgInAlbum(-1);
        });
        $('a.next').on('click', () => { 
            AdjustPagePhotos.detectImgInAlbum(1);
        });
        $(document).on('keydown', clickedKey => { 
            AdjustPagePhotos.keyboardOperating(clickedKey);
        });
      }
    }
          
/*===========================[ EXECUTED FUNCTIONS ]===========================*/
    /**
     * 1) Collapse:
     */
    AdjustPagePhotos.collapseMy();

    /**
     * 2) Slideshow:
     */
    $('.myImg').on('click', function() {
      AdjustPagePhotos.browsingAlbums(this);
    });      
  }
);
/*............................................................................*/