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

/*================================[ FUNCTIONS ]===============================*/

    /**
     * 1) Photo browsing
     */ 
    function photoBrowsing(selectedPhoto) {
      $('#myModal')[0].style.display = 'block';
      $('#img01')[0].src = selectedPhoto.src;
      $('#caption').text(selectedPhoto.alt);
      
      /** 
       * Handling Modal closing by cross in right, top corner
       */
      $('.close').one('click', () => {
        $('#myModal')[0].style.display = 'none';
        });

      /** 
       * Handling Modal closing by cling "ESC" key
       */
      $(document).on('keydown', event => {
        if (event.which === 27 && $('#myModal')[0].style.display === 'block') {
          $('#myModal')[0].style.display = 'none';
          $(document).off('keydown');
        }
      });
    }
        
/*===========================[ EXECUTED FUNCTIONS ]===========================*/
    /**
     * 1) Photo browsing
     */
    $('.img_venedi').on('click', function() {
      photoBrowsing(this);
    });
  }
);
/*............................................................................*/