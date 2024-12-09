import './css/skin-default.css';

(($) => {
  class MapsSliderTabs extends elementorModules.frontend.handlers.SwiperBase {
    getDefaultSettings() {
      return {
        selectors: {
          slider: '.maps-slider-tabs__images',
          slide: '.maps-slider-tabs__images__slides__item',
          activeSlide: '.swiper-slide-active',
          activeDuplicate: '.swiper-slide-duplicate-active',
          thumbs: '.maps-slider-tabs__nav',
        },
        slidesPerView: {
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
      const selectors = this.getSettings('selectors');
      const elements = {
        $swiperContainer: this.$element.find(selectors.slider),
      };

      elements.$slides = elements.$swiperContainer.find(selectors.slide);
      elements.$thumbs = this.$element.find(selectors.thumbs);

      return elements;
    }

    getSwiperOptions() {
      const elementSettings = this.getElementSettings();
      const breakpoints = elementorFrontend.config.responsive.activeBreakpoints;

      const swiperOptions = {
        thumbs: {
          swiper: this.elements.$thumbs[0].swiper,
          autoScrollOffset: 1,
        },
        grabCursor: false,
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 0,
        loop: 'yes' === elementSettings.slider_loop,
        loopedSlides: this.getSlidesCount() || null,
        autoplay:
          'yes' === elementSettings.slider_autoplay
            ? {
                delay: elementSettings.slider_interval,
              }
            : false,
        speed: elementSettings.slider_speed,
        effect: elementSettings.slider_effect,
        navigation: {
          enabled: true,
          nextEl: this.$element.find('.swiper-button-next'),
          prevEl: this.$element.find('.swiper-button-prev'),
        },
        preventClicksPropagation: false,
        slideToClickedSlide: true,
        handleElementorBreakpoints: true,
        on: {
          // slideChange: function() {
          //     let swiper = this,
          //         nav = swiper.$el.closest('.maps-slider-tabs').find('.maps-slider-tabs__nav'),
          //         navSwiper = nav.data('swiper');
          //     navSwiper.slideTo( swiper.realIndex );
          // },
        },
      };

      const breakpointsOptions = {};

      Object.keys(breakpoints).forEach((breakpoint) => {
        breakpointsOptions[breakpoints[breakpoint].value] = {
          grabCursor: true,
        };
      });

      swiperOptions.breakpoints = breakpointsOptions;

      return swiperOptions;
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

    async onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

      // const elementSettings = this.getElementSettings();
      const Swiper = elementorFrontend.utils.swiper;

      if (this.getSlidesCount() < 1) {
        return;
      }

      this.swiper = await new Swiper(this.elements.$swiperContainer, this.getSwiperOptions());

      this.elements.$swiperContainer.data('swiper', this.swiper);
    }
  }

  class MapsSliderTabsNav extends elementorModules.frontend.handlers.SwiperBase {
    getDefaultSettings() {
      return {
        selectors: {
          slider: '.maps-slider-tabs__nav',
          slide: '.maps-slider-tabs__nav__slides__item',
          activeSlide: '.swiper-slide-active',
          activeDuplicate: '.swiper-slide-duplicate-active',
          images: '.maps-slider-tabs__images',
        },
        slidesPerView: {
          widescreen: 4,
          desktop: 4,
          laptop: 3,
          tablet_extra: 2.5,
          tablet: 1.5,
          mobile_extra: 4,
          mobile: 4,
        },
      };
    }

    getDefaultElements() {
      const selectors = this.getSettings('selectors');
      const elements = {
        $swiperContainer: this.$element.find(selectors.slider),
      };

      elements.$slides = elements.$swiperContainer.find(selectors.slide);
      elements.$images = this.$element.find(selectors.images);

      return elements;
    }

    getSwiperOptions() {
      const elementSettings = this.getElementSettings();
      const breakpoints = elementorFrontend.config.responsive.activeBreakpoints;

      const swiperOptions = {
        // thumbs: {
        //     swiper: this.elements.$images[0].swiper
        // },
        // direction: 'vertical',
        centeredSlides: false,
        grabCursor: false,
        slideToClickedSlide: true,
        slidesPerView: this.getDeviceSetting('slider_slidestoshow', 'slidesPerView', 'desktop'),
        slidesPerGroup: 1,
        spaceBetween: 0,
        autoHeight: true,
        loop: 'yes' === elementSettings.slider_loop,
        loopedSlides: this.getSlidesCount() || null,
        preventClicksPropagation: false,
        handleElementorBreakpoints: true,
        on: {
          // init: function() {
          //     let swiper = this,
          //         wrapper = swiper.$el.find('.swiper-wrapper');
          //     if ( $(window).width() > 767 )
          //         return;
          //     for ( let i = 0; swiper.slides.length > i; i++) {
          //         let slideHeight = 0;
          //         for ( let item of swiper.slides[ i ].children ) {
          //             slideHeight += item.clientHeight;
          //         }
          //         swiper.slides[ i ].style.height = slideHeight + 'px';
          //     }
          //     let activeSlide = swiper.slides[ swiper.activeIndex ],
          //         offset = 0;
          //     for ( let item of activeSlide.children ) {
          //         offset += item.clientHeight;
          //     }
          //     $(wrapper).css('height', offset + 'px');
          //     if ( swiper.activeIndex > 0 ) {
          //         offset *= swiper.activeIndex;
          //         $(wrapper).css('transform', `translate3d(0px, -${offset}px, 0px)`);
          //     }
          // },
          // slideChange: function() {
          //     let swiper = this,
          //         wrapper = swiper.$el.find('.swiper-wrapper');
          //     if ( $(window).width() > 767 )
          //         return;
          //     for ( let i = 0; swiper.slides.length > i; i++) {
          //         let slideHeight = 0;
          //         for ( let item of swiper.slides[ i ].children ) {
          //             slideHeight += item.clientHeight;
          //         }
          //         swiper.slides[ i ].style.height = slideHeight + 'px';
          //     }
          //     let activeSlide = swiper.slides[ swiper.activeIndex ],
          //         offset = 0;
          //     for ( let item of activeSlide.children ) {
          //         offset += item.clientHeight;
          //     }
          //     $(wrapper).css('height', offset + 'px');
          //     if ( swiper.activeIndex > 0 ) {
          //         offset *= swiper.activeIndex;
          //         $(wrapper).css('transform', `translate3d(0px, -${offset}px, 0px)`);
          //     }
          // },
          // click: function() {
          //     let swiper = this,
          //         images = swiper.$el.closest('.maps-slider-tabs').find('.maps-slider-tabs__images'),
          //         imagesSwiper = images.data('swiper');
          //     imagesSwiper.slideTo( swiper.realIndex );
          // },
        },
      };

      const breakpointsOptions = {};

      Object.keys(breakpoints).forEach((breakpoint) => {
        breakpointsOptions[breakpoints[breakpoint].value] = {
          slidesPerView: this.getDeviceSetting('slider_slidestoshow', 'slidesPerView', breakpoint),
        };

        if (breakpoint.indexOf('mobile') !== -1) {
          breakpointsOptions[breakpoints[breakpoint].value].direction = 'vertical';
          breakpointsOptions[breakpoints[breakpoint].value].autoHeight = true;
          // breakpointsOptions[ breakpoints[ breakpoint ].value ].freeMode = true;
          // breakpointsOptions[ breakpoints[ breakpoint ].value ].loop = false;
        }
      });

      swiperOptions.breakpoints = breakpointsOptions;

      return swiperOptions;
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

    async onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

      // const elementSettings = this.getElementSettings();
      const Swiper = elementorFrontend.utils.swiper;

      if (this.getSlidesCount() < 1) {
        return;
      }

      this.swiper = await new Swiper(this.elements.$swiperContainer, this.getSwiperOptions());

      this.elements.$swiperContainer.data('swiper', this.swiper);
    }
  }

  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler('maps-slider-tabs', MapsSliderTabsNav);
    elementorFrontend.elementsHandler.attachHandler('maps-slider-tabs', MapsSliderTabs);
  });
})(jQuery);
