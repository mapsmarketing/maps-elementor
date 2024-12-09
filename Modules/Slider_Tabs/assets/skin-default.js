import './css/skin-default.css';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';
import 'slick-carousel';

(($) => {
  class SliderTabs extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
      return {
        selectors: {
          // tabs: '.maps-toggle-timeline__tabs',
        },
        slidesToShow: {
          widescreen: 4,
          desktop: 4,
          laptop: 2,
          tablet_extra: 1.3,
          tablet: 1.3,
          mobile_extra: 1,
          mobile: 1,
        },
        slidesToScroll: {
          widescreen: 1,
          desktop: 1,
          laptop: 1,
          tablet_extra: 1,
          tablet: 1,
          mobile_extra: 1,
          mobile: 1,
        },
      };
    }

    getDefaultElements() {
      // const selectors = this.getSettings( 'selectors' );

      return {
        // $tabWrapper: this.$element.find(selectors.tabWrapper),
      };
    }

    bindEvents() {
      // this.elements.$button.on('click', this.onToggle.bind(this));
    }

    getDeviceBreakpointValue(device) {
      if (!this.breakpointsDictionary) {
        const breakpoints = elementorFrontend.config.responsive.activeBreakpoints;

        this.breakpointsDictionary = {};

        Object.keys(breakpoints).forEach((breakpointName) => {
          this.breakpointsDictionary[breakpointName] = breakpoints[breakpointName].value;
        });
      }

      return this.breakpointsDictionary[device];
    }

    getDeviceSetting(name, setting, device) {
      const field = name + ('desktop' === device ? '' : `_${device}`);
      const val = this.getSettings(setting)[device] || 1;

      return Math.min(+this.getElementSettings(field) || val);
    }

    onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

      let elementClass = this.$element.attr('class').split(' ');
      elementClass = `.${elementClass[1]}`;

      this.$element.find('.maps-slider-tabs__images__slides').slick({
        rows: 0, // fixes double <div> wrap on item
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        dots: false,
        asNavFor: `${elementClass} .maps-slider-tabs__nav__slides`,
        responsive: [
          {
            breakpoint: 1600,
            settings: {
              arrows: true,
            },
          },
        ],
      });

      const breakpoints = elementorFrontend.config.responsive.activeBreakpoints;
      const responsive = Object.keys(breakpoints).map((breakpoint) => ({
        breakpoint: breakpoints[breakpoint].value,
        settings: {
          slidesToShow: this.getDeviceSetting('slider_slidestoshow', 'slidesToShow', breakpoint),
          slidesToScroll: this.getDeviceSetting('slider_slidestoscroll', 'slidesToScroll', breakpoint),
          vertical: !!('tablet' === breakpoint || 'mobile' === breakpoint),
        },
      }));

      this.$element.find('.maps-slider-tabs__nav__slides').slick({
        rows: 0, // fixes double <div> wrap on item
        slidesToShow: this.getDeviceSetting('slider_slidestoshow', 'slidesToShow', 'desktop'),
        slidesToScroll: this.getDeviceSetting('slider_slidestoshow', 'slidesToShow', 'desktop'),
        infinite: false,
        arrows: false,
        dots: false,
        asNavFor: `${elementClass} .maps-slider-tabs__images__slides`,
        focusOnSelect: true,
        responsive,
      });
    }
  }

  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler('maps-slider-tabs', SliderTabs, 'default');
  });
})(jQuery);
