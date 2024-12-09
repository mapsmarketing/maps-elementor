import './css/skin-default.css';

(($) => {
  class ToggleText extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
      return {
        selectors: {
          button: '.maps-toggle-text__btn',
          content: '.maps-toggle-text__content',
        },
      };
    }

    getDefaultElements() {
      const selectors = this.getSettings('selectors');

      return {
        $button: this.$element.find(selectors.button),
        $content: this.$element.find(selectors.content),
      };
    }

    bindEvents() {
      this.elements.$button.on('click touchstart', this.onToggle.bind(this));
    }

    onToggle(e) {
      e.preventDefault();

      const $content = $(e.target).closest('.maps-toggle-text').find('.maps-toggle-text__content');

      $content.toggleClass('on');

      if ($content.hasClass('on')) {
        $(e.target).html('Read Less');
      } else {
        $(e.target).html('Read More');
      }
    }

    onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
    }
  }

  class ToggleTextSlide extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
      return {
        selectors: {
          button: '.maps-toggle-text__btn',
          content: '.maps-toggle-text__content',
        },
      };
    }

    getDefaultElements() {
      const selectors = this.getSettings('selectors');

      return {
        $button: this.$element.find(selectors.button),
        $content: this.$element.find(selectors.content),
      };
    }

    bindEvents() {
      this.elements.$button.on('click touchstart', this.onToggle.bind(this));
    }

    onToggle(e) {
      e.preventDefault();

      const $content = $(e.target).closest('.maps-toggle-text').find('.maps-toggle-text__content');

      $content.toggleClass('on');
    }

    onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
    }
  }

  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler('maps-toggle-text', ToggleText, 'default');
    elementorFrontend.elementsHandler.attachHandler('maps-toggle-text', ToggleTextSlide, 'slide');
  });
})(jQuery);
