import './css/skin-default.css';

(($) => {
  class Carousel extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
      return {
        selectors: {
          swiperContainer: '.swiper-container',
          swiperSlide: '.swiper-slide',
          nextButton: '.swiper-button-next',
          prevButton: '.swiper-button-prev',
          paginationContainer: '.swiper-pagination',
        },
        slidesPerView: {
          widescreen: 3,
          desktop: 3,
          laptop: 3,
          tablet_extra: 3,
          tablet: 2,
          mobile_extra: 2,
          mobile: 1,
        },
      };
    }

    getDefaultElements() {
      const selectors = this.getSettings('selectors');
      const elements = {
        $swiperContainer: this.$element.find(selectors.swiperContainer),
      };
      elements.$slides = elements.$swiperContainer.find(selectors.swiperSlide);
      return elements;
    }

    async onInit() {
      await elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

      if (this.elements.$slides.length <= 1) {
        return;
      }

      const Swiper = elementorFrontend.utils.swiper;

      // Initialize Swiper and wait for the instance to resolve
      this.swiper = await new Promise((resolve) => {
        new Swiper(this.elements.$swiperContainer, {
          ...this.getSwiperOptions(),
          on: {
            init: function() {
              resolve(this);
            },
          },
        });
      });

      this.elements.$swiperContainer.data('swiper', this.swiper);
    }

    getSwiperOptions() {
      const elementSettings = this.getElementSettings();

      return {
        grabCursor: true,
        slidesPerView: this.getDeviceSetting('slider_slidestoshow', 'slidesPerView', 'desktop', 1),
        slidesPerGroup: this.getDeviceSetting('slider_slidestoscroll', 'slidesPerGroup', 'desktop', 1),
        spaceBetween: this.getDeviceSliderSetting('slider_space', 'spaceBetween', 'desktop', 0),
        loop: 'true' === elementSettings.slider_loop,
        direction: elementSettings.slider_direction,
        autoplay:
          'true' === elementSettings.slider_autoplay
            ? {
                delay: elementSettings.slider_interval,
              }
            : false,
        centeredSlides: 'true' === elementSettings.slider_center_mode,
        speed: elementSettings.slider_speed,
        effect: elementSettings.slider_effect,
        navigation:
          'true' === elementSettings.slider_arrows
            ? {
                nextEl: this.elements.$swiperContainer.find(this.getSettings('selectors.nextButton'))[0],
                prevEl: this.elements.$swiperContainer.find(this.getSettings('selectors.prevButton'))[0],
              }
            : false,
        pagination:
          'true' === elementSettings.slider_pagination
            ? {
                el: this.elements.$swiperContainer.find(this.getSettings('selectors.paginationContainer'))[0],
                type: elementSettings.slider_pagination_type || 'bullets',
                clickable: true,
              }
            : false,
        preventClicksPropagation: false,
        slideToClickedSlide: true,
        handleElementorBreakpoints: true,
        preloadImages: true,
        breakpoints: this.getBreakpointsSettings(),
      };
    }

    getBreakpointsSettings() {
      const breakpointsSettings = {};
      const breakpoints = elementorFrontend.config.responsive.activeBreakpoints;

      Object.keys(breakpoints).forEach((breakpointName) => {
        const breakpointValue = breakpoints[breakpointName].value;
        breakpointsSettings[breakpointValue] = {
          slidesPerView: this.getDeviceSetting('slider_slidestoshow', 'slidesPerView', breakpointName),
          slidesPerGroup: this.getDeviceSetting('slider_slidestoscroll', 'slidesPerGroup', breakpointName, 1),
          spaceBetween: this.getDeviceSliderSetting('slider_space', 'spaceBetween', breakpointName, 0),
        };
      });

      return breakpointsSettings;
    }

    getDeviceSetting(name, setting, device, override = false) {
      const field = name + ('desktop' === device ? '' : `_${device}`);
      const val = override || this.getSettings(setting)[device];
      return Math.min(+this.getElementSettings(field) || val);
    }

    getDeviceSliderSetting(name, setting, device, override = false) {
      const field = name + ('desktop' === device ? '' : `_${device}`);
      const val = override !== false ? override : this.getSettings(setting)[device];
      return Math.min(+this.getElementSettings(field).size || val);
    }
  }

  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler('maps-carousel', Carousel, 'default');
  });
})(jQuery);
