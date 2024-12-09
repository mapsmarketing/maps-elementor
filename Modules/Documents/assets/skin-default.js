import './css/skin-default.css';

(($) => {
  class Documents extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
      return {
        lastScrollTop: 0,
        selectors: {
          tabTitle: '.elementor-tab-title',
          accordionTitle: '.elementor-accordion-title',
        },
      };
    }

    getDefaultElements() {
      const selectors = this.getSettings('selectors');

      return {
        $tabTitle: this.$element.find(selectors.tabTitle),
        $accordionTitle: this.$element.find(selectors.accordionTitle),
      };
    }

    bindEvents() {
      this.elements.$tabTitle.on('click', this.onToggle.bind(this));
      this.elements.$accordionTitle.on('click', this.onAccordionToggle.bind(this));
    }

    onToggle(e) {
      const $tabTitle = $(e.currentTarget);
      const $tabContent = $(`#${$tabTitle.attr('aria-controls')}`);

      if ($tabTitle.hasClass('elementor-active')) {
        $tabTitle.removeClass('elementor-active');
        $tabContent.slideUp().removeClass('elementor-active');
      } else {
        $tabTitle.addClass('elementor-active');
        $tabContent.slideDown().addClass('elementor-active');
      }
    }

    onAccordionToggle(e) {
      e.preventDefault();
    }

    onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
    }
  }

  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler('maps-documents', Documents, 'default');
  });
})(jQuery);
