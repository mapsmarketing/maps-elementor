import './css/skin-default.css';
// import 'sticky-sidebar-v2/src/jquery.sticky-sidebar';
import 'sticky-sidebar-v2/dist/jquery.sticky-sidebar';
// import StickySidebar from 'sticky-sidebar-v2';

(($) => {
  class ToggleTimeline extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
      return {
        selectors: {
          tabs: '.maps-toggle-timeline__tabs',
          tabWrapper: '.maps-toggle-timeline__tabs__wrapper',
          tab: '.maps-toggle-timeline__tabs__item',
          button: '.maps-toggle-timeline__tabs__item a',
          item: '.maps-toggle-timeline__items__item',
          toggle: '.maps-toggle-timeline__items__item__toggle',
        },
      };
    }

    getDefaultElements() {
      const selectors = this.getSettings('selectors');

      return {
        $tabWrapper: this.$element.find(selectors.tabWrapper),
        $tab: this.$element.find(selectors.tab),
        $button: this.$element.find(selectors.button),
        $item: this.$element.find(selectors.item),
        $toggle: this.$element.find(selectors.toggle),
      };
    }

    bindEvents() {
      $(window).on('load resize', this.onWindowScroll.bind(this));
      this.elements.$button.on('click', this.onToggle.bind(this));
      this.elements.$toggle.on('click', this.onToggleContent.bind(this));
    }

    onWindowScroll() {
      const elementSettings = this.getElementSettings();

      this.$element.find('.maps-toggle-timeline__tabs__wrapper').stickySidebar({
        topSpacing: elementSettings.menu_top_spacing.size,
        bottomSpacing: 0,
      });

      // if ($(window).width() > 1024) {
      //     // this.$element.find('.maps-toggle-timeline__tabs__wrapper').stickySidebar({
      //     //     topSpacing: 160,
      //     //     bottomSpacing: 0,
      //     // }); // working
      // }
    }

    onToggle(e) {
      e.preventDefault();

      const $this = $(e.target);

      this.elements.$tab.removeClass('on');
      this.elements.$item.removeClass('on');

      $this.parent().toggleClass('on');
      this.$element.find($this.attr('href')).toggleClass('on');
    }

    onToggleContent(e) {
      e.preventDefault();

      const $wrapper = $(e.target)
        .closest('.maps-toggle-timeline__items__item__content')
        .find('.maps-toggle-timeline__items__item__content__wrapper')
        .toggleClass('on');

      if ($wrapper.hasClass('on')) {
        $(e.target).html('Read less');
      } else {
        $(e.target).html('Read more');
      }
    }

    onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
      this.elements.$tab.removeClass('on');
      this.elements.$item.removeClass('on');

      this.elements.$tab.first().addClass('on');
      this.elements.$item.first().addClass('on');
    }

    // onDestroy() {
    //     elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);
    // }
  }

  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler('maps-toggle-timeline', ToggleTimeline, 'default');
  });
})(jQuery);
