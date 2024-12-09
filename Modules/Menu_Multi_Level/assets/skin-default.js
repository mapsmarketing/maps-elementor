import './css/skin-default.css';

(($) => {
  class MenuMultiLevel extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
      return {
        selectors: {
          widget: '.maps-menu-multi-level',
          hamburger: '.maps-menu-multi-level__hamburger',
          tabs: '.maps-menu-multi-level__tabs',
          tabItemToggle: '.maps-menu-multi-level__tabs__list__item__toggle',
          megaItemToggle: '.maps-menu-multi-level__tabs__mega__item__toggle',
          megas: '.maps-menu-multi-level__tabs__mega',
          megaItem: '.maps-menu-multi-level__tabs__mega__item',
          megaMenuPrimaryItem: '.maps-menu-multi-level__tabs__mega__menus__primary > ul > li',
          megaMenuPrimaryParent:
            '.maps-menu-multi-level__tabs__mega__menus__primary > ul > .menu-item-has-children > a',
          megaMenuSecondary: '.maps-menu-multi-level__tabs__mega__menus__secondary',
        },
      };
    }

    getDefaultElements() {
      const selectors = this.getSettings('selectors');

      return {
        $widget: this.$element.find(selectors.widget),
        $hamburger: this.$element.find(selectors.hamburger),
        $tabs: this.$element.find(selectors.tabs),
        $tabItemToggle: this.$element.find(selectors.tabItemToggle),
        $megaItemToggle: this.$element.find(selectors.megaItemToggle),
        $megas: this.$element.find(selectors.megas),
        $megaItem: this.$element.find(selectors.megaItem),
        $megaMenuPrimaryItem: this.$element.find(selectors.megaMenuPrimaryItem),
        $megaMenuPrimaryParent: this.$element.find(selectors.megaMenuPrimaryParent),
        $megaMenuSecondary: this.$element.find(selectors.megaMenuSecondary),
      };
    }

    bindEvents() {
      // const selectors = this.getSettings( 'selectors' );

      $(window).on('click', this.onWindowClick.bind(this));

      this.elements.$hamburger.on('click', this.onToggleHamburger.bind(this));
      this.elements.$tabItemToggle.on('click', this.onToggleMega.bind(this));
      this.elements.$megaItemToggle.on('click', this.onToggleMegaTabs.bind(this));

      if ($(window).width() < 1025) {
        this.elements.$megaMenuPrimaryParent.on('click', this.onToggleMegaMenuPrimaryParent.bind(this));
        this.elements.$megaMenuSecondary.on('click', 'h3 a', this.onToggleMegaMenuSecondary.bind(this));
      } else {
        this.elements.$megaMenuPrimaryParent.off('click', this.onToggleMegaMenuPrimaryParent.bind(this));
        this.elements.$megaMenuSecondary.off('click', 'h3 a', this.onToggleMegaMenuSecondary.bind(this));
      }
    }

    onWindowClick(e) {
      const selectors = this.getSettings('selectors');

      if (0 === $(e.target).closest(selectors.widget).length) {
        this.elements.$tabItemToggle.closest('li').removeClass('on');
      }
    }

    onToggleHamburger() {
      this.elements.$tabs.toggleClass('on');

      return false;
    }

    onToggleMega(e) {
      const selectors = this.getSettings('selectors');

      if ($(e.target).closest('li').hasClass('on')) {
        this.elements.$tabItemToggle.closest('li').removeClass('on');
      } else {
        this.elements.$tabItemToggle.closest('li').removeClass('on');
        $(e.target).closest('li').addClass('on');

        $(e.target).closest('li').find(selectors.megaItem).removeClass('on');
        $(e.target).closest('li').find(selectors.megaItem).first().addClass('on');
      }

      return false;
    }

    onToggleMegaTabs(e) {
      this.elements.$megaItemToggle.closest('li').removeClass('on');
      $(e.target).closest('li').addClass('on');

      return false;
    }

    onToggleMegaMenuPrimaryParent(e) {
      if ($(e.target).closest('li').hasClass('on')) {
        this.elements.$megaMenuPrimaryItem.removeClass('on');
      } else {
        this.elements.$megaMenuPrimaryItem.removeClass('on');
        $(e.target).closest('li').addClass('on');
      }

      return false;
    }

    onToggleMegaMenuSecondary(e) {
      $(e.target).closest(this.elements.$megaMenuSecondary).toggleClass('on');

      return false;
    }

    onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

      // const elementSettings = this.getElementSettings();
    }
  }

  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler('maps-menu-multi-level', MenuMultiLevel, 'default');
  });
})(jQuery);
