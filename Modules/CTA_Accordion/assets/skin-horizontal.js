import './css/skin-horizontal.css';

(($) => {
  class CtaAccordionSkinHorizontal extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
      return {
        selectors: {
          button: '.maps-cta-accordions__slide__title',
          item: '.maps-cta-accordions__slide',
          itemActive: '.maps-cta-accordions__slide.on',
        },
      };
    }

    getDefaultElements() {
      const selectors = this.getSettings('selectors');

      return {
        $button: this.$element.find(selectors.button),
        $item: this.$element.find(selectors.item),
        $itemActive: this.$element.find(selectors.itemActive),
      };
    }

    bindEvents() {
      this.elements.$button.on('click touchstart', this.onToggle.bind(this));
    }

    onToggle(e) {
      e.preventDefault();

      const selectors = this.getSettings('selectors');
      const $this = $(e.target);

      this.$element.find(selectors.itemActive).removeClass('on');
      $this.closest(selectors.item).toggleClass('on');
    }

    onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

      this.elements.$item.first().addClass('on');
    }
  }

  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler(
      'maps-cta-accordion',
      CtaAccordionSkinHorizontal,
      'maps-cta-accordion-horizontal-skin',
    );
  });
})(jQuery);
