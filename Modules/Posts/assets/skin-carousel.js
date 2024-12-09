import './css/skin-carousel.css';

(($) => {
  class PostsSkinCarousel extends elementorModules.frontend.handlers.SwiperBase {
    getDefaultSettings() {
      return {
        selectors: {
          swiperContainer: '.elementor-posts',
          swiperSlide: '.swiper-slide',
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

    getSwiperOptions() {
      const elementSettings = this.getElementSettings();

      const swiperOptions = {
        grabCursor: true,
        // initialSlide: this.getInitialSlide(),
        slidesPerView: this.getDeviceSetting('carousel_slider_slidestoshow', 'slidesPerView', 'desktop'),
        slidesPerGroup: this.getDeviceSetting('carousel_slider_slidestoscroll', 'slidesPerGroup', 'desktop', 1),
        spaceBetween: this.getDeviceSliderSetting('carousel_slider_space', 'spaceBetween', 'desktop', 0),
        loop: 'true' === elementSettings.carousel_slider_loop,
        autoplay:
          'true' === elementSettings.carousel_slider_autoplay
            ? {
                delay: elementSettings.carousel_slider_interval,
              }
            : false,
        centeredSlides: 'true' === elementSettings.carousel_slider_center_mode,
        speed: elementSettings.carousel_slider_speed,
        effect: elementSettings.carousel_slider_effect,
        navigation:
          'true' === elementSettings.carousel_slider_arrows
            ? {
                enabled: true,
                nextEl: this.$element.find('.swiper-button-next'),
                prevEl: this.$element.find('.swiper-button-prev'),
              }
            : false,
        pagination:
          'true' === elementSettings.carousel_slider_pagination
            ? {
                enabled: true,
                el: this.$element.find('.swiper-pagination'),
                type: elementSettings.carousel_slider_pagination_type,
                clickable: true,
              }
            : false,
        preventClicksPropagation: false,
        slideToClickedSlide: true,
        handleElementorBreakpoints: true,
      };

      const breakpointsSettings = {};
      const breakpoints = elementorFrontend.config.responsive.activeBreakpoints;
      Object.keys(breakpoints).forEach((breakpointName) => {
        breakpointsSettings[breakpoints[breakpointName].value] = {
          slidesPerView: this.getDeviceSetting('carousel_slider_slidestoshow', 'slidesPerView', breakpointName),
          slidesPerGroup: this.getDeviceSetting('carousel_slider_slidestoscroll', 'slidesPerGroup', breakpointName, 1),
          spaceBetween: this.getDeviceSliderSetting('carousel_slider_space', 'spaceBetween', breakpointName, 0),
        };
      });

      swiperOptions.breakpoints = breakpointsSettings;

      return swiperOptions;
    }

    async onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
      // const elementSettings = this.getElementSettings();

      if (this.getSlidesCount() <= 1) {
        return;
      }

      const Swiper = elementorFrontend.utils.swiper;
      this.swiper = await new Swiper(this.elements.$swiperContainer, this.getSwiperOptions());

      this.elements.$swiperContainer.data('swiper', this.swiper);
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
    elementorFrontend.elementsHandler.attachHandler('posts', PostsSkinCarousel, 'carousel');
  });
})(jQuery);
