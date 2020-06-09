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
        const widthOfBodyWithBars = $('div.main_and_aside')[0].clientWidth;
        const widthOfBodyWithoutBars = $('body')[0].clientWidth;
        const barsWidth = widthOfBodyWithBars - widthOfBodyWithoutBars;
        if (barsWidth == 15) {
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

/*===============================[ FUNCTIONS ]===============================*/

    /**
     * 1) Form choosing
     */
    function selectSubpage() {
      $('#subpages').on('change', (event) => {
        $('#news_form')[0].style.display = 
            (event.target.value === 'news') ? 'block' : 'none';
        $('#photos_album_form')[0].style.display = 
            (event.target.value === 'photos') ? 'block' : 'none';
        $('#videos_album_form')[0].style.display = 
            (event.target.value === 'videos') ? 'block' : 'none';
      });
    }

    /**
     * 2) Validation after "SUBMIT" button clicking
     */
    function validationCheck() {

      /**
       * Get the forms we want to add validation styles to
       */
      let forms = $('.needs-validation');

      /**
       * Loop over them and prevent submission
       */
      let validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }


    /**
     * 3) Add input depends on which inputs are filled
     * 
     * Recursive functions to invoke
     */
    function recursive(evt, nameOfContainer, inputName) {
      if (evt.currentTarget.value !== "") {
        const CONTAINER_SELECTOR = '#' + nameOfContainer + '>input';
        let isEmpty = 0;
        for (let i = 0; i < $(CONTAINER_SELECTOR).length; i++) {
          isEmpty += ($(CONTAINER_SELECTOR)[i].value === "") ? 1 : 0;
        }
        if (isEmpty === 0) {
          let inputOfForm = document.createElement('input');
          inputOfForm.setAttribute('type', 'file')
          inputOfForm.setAttribute('class', 'm-sm-1');
          inputOfForm.setAttribute('style', 
              'margin-left: 95px !important; display: block');
          inputOfForm.setAttribute('name', inputName);
          inputOfForm.setAttribute('accept', "image/*");
          document.getElementById(nameOfContainer).appendChild(inputOfForm);
          $('#' + nameOfContainer + '>input:last-child').on(
              "input", 
              function(evt4) {
                recursive(evt4, nameOfContainer, inputName);
              });
        }
      } else if(evt.currentTarget.value === "") {
        for (let i = 0; i < $(CONTAINER_SELECTOR).length; i++) {
          if (($(CONTAINER_SELECTOR)[i].value === "") && 
              ($(CONTAINER_SELECTOR).length > 5)) {
            document.getElementById(nameOfContainer).removeChild(
                $(CONTAINER_SELECTOR)[i]);
            return;
          }
        }
      }
    }
      
    /**
     * Invoke recursive function
     */
    function addOrRemoveInput(nameOfContainer, inputName) {
      $('#' + nameOfContainer + '>input').on('input', function(evt) {
        recursive(evt, nameOfContainer, inputName);
      });
    }
    
    /**
     * Add form ('form' in Symfony's definition)
     */
    function addForm($collectionHolder, $newLinkLi) {
      let prototype = $collectionHolder.data('prototype');
      let index = $collectionHolder.data('index');
      let newForm = prototype;
      newForm = newForm.replace(/<div>/, '<div class="nested">');
      newForm = newForm.replace(/__name__/g, index);
      $collectionHolder.data('index', index + 1);
      let $newFormLi = $('<li></li>').append(newForm);
      $newLinkLi.before($newFormLi);
      addFormDeleteLink($newFormLi);
    }
    
    /**
     * Add button for delete form.
     */
    function addFormDeleteLink($formLi) {
      let $delButton = $('<button type="button">Usuń</button>');
      $formLi.find('div.nested').first().append($delButton);
      $delButton.on('click', function () {
        $formLi.remove(); 
      });
    }
    
    /**
     * Rest actions needed to perform in purpose of given user possibility
     * to add and remove file's input
     */
    function addOrRemoveForm(formCN) {
        let $collectionHolder = $('ul.' + formCN);
        $collectionHolder.find('li').each(function() {
            $(this).find('div').first().addClass('nested');
            addFormDeleteLink($(this));
        });
        let htmlTxt =
            '<button type="button" class="add_form_link">Dodaj kolejny</button>';
        let $addButton = $(htmlTxt);
        let $newLinkLi = $('<li></li>').append($addButton);
        $collectionHolder.append($newLinkLi);
        $collectionHolder.data('index', $collectionHolder.find(':input').length);
        $addButton.on('click', function () {
            addForm($collectionHolder, $newLinkLi);
        });
        
        return $collectionHolder;
    }

    /**
     * N) TESTS:
     */

/*===========================[ EXECUTED FUNCTIONS ]===========================*/
    /**
     * 1) Handling window resize.
     */
    $(window).resize(function() {
      AdjustPage.windowResize();
      AdjustPage.calcHeight();
    });

    /**
     * 2) Adjust once after content loaded.
     */
    AdjustPage.windowResize();
    AdjustPage.calcHeight();

    /**
     * 3) Handling forms:
     * 
     * Changing of visibility
     */
    selectSubpage();
  
    /**
     * Checking data validity
     */
    validationCheck();
  
    /**
     * Adding or removing input for files:
     */
    addOrRemoveInput('album_container', 'images_of_news[]');
    addOrRemoveInput('album_of_photos_container', 'album_of_photos[]');

    /**
     * Add remove fields with Tags entities to or from TaskType
     */
    let $handler1 = addOrRemoveForm('album1');
    let $handler2 = addOrRemoveForm('album2');
    let $handler3 = addOrRemoveForm('album3');
    let $handler4 = addOrRemoveForm('album4');
    /**
     * N) TESTS:
     */
  }
);
/*............................................................................*/