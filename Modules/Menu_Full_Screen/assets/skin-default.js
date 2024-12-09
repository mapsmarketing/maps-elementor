import './css/skin-default.css';

(($) => {
  class MenuFullScreen extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
      return {
        lastScrollTop: 0,
        selectors: {
          widget: '.maps-menu-full-screen',
          main: '.maps-menu-full-screen__main',
          menu: '.maps-menu-full-screen__menu',
          toggle: '.maps-menu-full-screen__main__right__toggle',
          subMenu: '.maps-menu-full-screen__menu__primary .sub-menu',
          subMenuToggle: '.maps-menu-full-screen__menu__primary li.menu-item-has-children > a',
          subMenuBackBtn: '.menu-item-back a',
          search: '.maps-menu-full-screen__search',
          searchToggle: '.maps-menu-full-screen__main__right__search',
          searchInput: '.maps-menu-full-screen__search__text',
          searchResults: '.maps-menu-full-screen__search__results',
        },
      };
    }

    getDefaultElements() {
      const selectors = this.getSettings('selectors');

      return {
        $widget: this.$element.find(selectors.widget),
        $main: this.$element.find(selectors.main),
        $menu: this.$element.find(selectors.menu),
        $toggle: this.$element.find(selectors.toggle),
        $subMenu: this.$element.find(selectors.subMenu),
        $subMenuToggle: this.$element.find(selectors.subMenuToggle),
        $search: this.$element.find(selectors.search),
        $searchToggle: this.$element.find(selectors.searchToggle),
        $searchResults: this.$element.find(selectors.searchResults),
        $searchInput: this.$element.find(selectors.searchInput),
      };
    }

    bindEvents() {
      const selectors = this.getSettings('selectors');

      this.onWindowScroll();
      this.onWindowResize();
      // window.onload = this.onWindowResize.bind(this);

      $(window).on('scroll', this.onWindowScroll.bind(this));
      $(window).on('resize', this.onWindowResize.bind(this));

      this.elements.$searchToggle.on('click', this.onToggleSearch.bind(this));
      this.elements.$toggle.on('click', this.onToggleMenu.bind(this));
      this.elements.$subMenuToggle.on('click', this.onToggleSubMenu.bind(this));
      this.$element
        .find('.maps-menu-full-screen__menu__primary')
        .on('click', selectors.subMenuBackBtn, this.onSubMenuBack.bind(this));
      this.elements.$searchInput.on('keyup', this.onSearch.bind(this));
    }

    onWindowScroll() {
      // console.log('window scroll')
      const $window = $(window);
      const lastScrollTop = this.getSettings('lastScrollTop');

      // Check if we need to apply the `sticky` class to the header
      if ($window.scrollTop() <= 300) {
        this.elements.$main.removeClass('maps-menu-full-screen__main--sticky');
        this.elements.$main.removeClass('maps-menu-full-screen__main--scroll-up');
        this.elements.$main.removeClass('maps-menu-full-screen__main--scroll-down');
      } else {
        this.elements.$main.addClass('maps-menu-full-screen__main--sticky');
      }

      // Scroll direction used for hiding the logo, buttons, etc.
      if ($window.scrollTop() > 300) {
        if ($window.scrollTop() > lastScrollTop) {
          this.elements.$main.addClass('maps-menu-full-screen__main--scroll-down');
          this.elements.$main.removeClass('maps-menu-full-screen__main--scroll-up');
        } else {
          this.elements.$main.addClass('maps-menu-full-screen__main--scroll-up');
          this.elements.$main.removeClass('maps-menu-full-screen__main--scroll-down');
        }
      }

      this.setSettings('lastScrollTop', $window.scrollTop());

      this.positionPanels();
    }

    onWindowResize() {
      this.positionPanels();
    }

    positionPanels() {
      // const elementSettings = this.getElementSettings();
      // const $window = $( window );
      const $adminBar = $('#wpadminbar');

      let mainHeight = this.elements.$main.innerHeight();
      let adminBarHeight = 0;

      if ($adminBar.length) {
        mainHeight += $adminBar.innerHeight();
        adminBarHeight = $adminBar.innerHeight();
      }

      this.elements.$menu.css({
        height: `calc(100vh - ${mainHeight}px)`,
        top: mainHeight - adminBarHeight,
      });

      // $menu.find('.sub-menu').css('height', 'calc(100% - ' + $main.innerHeight() + 'px)' );
      this.elements.$search.css({
        height: `calc(100vh - ${mainHeight}px)`,
        top: mainHeight - adminBarHeight,
      });
    }

    onToggleSearch() {
      if (this.elements.$searchToggle.hasClass('on')) {
        $('body').removeClass('maps-menu-full-screen--active');

        this.hideSearch();
        this.elements.$searchToggle.removeClass('on');
        this.elements.$main.removeClass('on');
      } else {
        $('body').addClass('maps-menu-full-screen--active');

        this.hideMenu();
        this.elements.$searchToggle.addClass('on');
        this.$element.find('.maps-menu-full-screen__main__left__logo').removeClass('off');
        this.$element.find('.maps-menu-full-screen__main__right .elementor-button').removeClass('off');
        this.elements.$searchToggle.addClass('on');
        this.elements.$search.addClass('on');
        this.elements.$main.addClass('on');
      }

      return false;
    }

    onToggleMenu() {
      if (this.elements.$toggle.hasClass('on')) {
        $('body').removeClass('maps-menu-full-screen--active');

        this.hideMenu();
        this.elements.$toggle.removeClass('on');
        this.elements.$main.removeClass('on');
      } else {
        $('body').addClass('maps-menu-full-screen--active');

        this.hideSearch();
        this.elements.$toggle.addClass('on');
        this.$element.find('.maps-menu-full-screen__main__left__logo').removeClass('off');
        this.$element.find('.maps-menu-full-screen__main__right .elementor-button').removeClass('off');
        this.elements.$toggle.addClass('on');
        this.elements.$main.addClass('on');
        this.elements.$menu.addClass('on');
      }

      return false;
    }

    onToggleSubMenu(e) {
      const $this = $(e.currentTarget);
      const $menuBg = this.$element.find('.maps-menu-full-screen__menu__bg');
      const image = $this.data('featured-image');

      this.$element.find('.menu > li.on').removeClass('on');
      $this.closest('li').toggleClass('on');

      $menuBg.removeClass('on');

      if (image) {
        $menuBg.css('background-image', `url(${image})`);

        setTimeout(() => {
          $menuBg.addClass('on');
        }, 500);
      }

      return false;
    }

    onSubMenuBack() {
      this.$element.find('.maps-menu-full-screen__menu__primary .menu-item.on').removeClass('on');
      return false;
    }

    onSearch() {
      const { $searchResults } = this.elements;

      if (this.elements.$searchInput.val().length > 3) {
        $searchResults.html('<i class="fa-3x fas fa-spinner fa-spin"></i>');

        $.getJSON(mapsObject.ajax_url, {
          action: 'maps_menu_full_screen_search',
          s: encodeURIComponent(this.elements.$searchInput.val()),
        }).done((data) => {
          $searchResults.html('');

          if (Object.keys(data).length !== 0) {
            $.each(data, (type, pages) => {
              const $type = $(`
                <div class="maps-menu-full-screen__search__results__type">
                  <h4 class="maps-menu-full-screen__search__results__type__title">${type} (${pages.length})</h4>
                  <ul class="maps-menu-full-screen__search__results__type__list"></ul>
                </div>
              `);

              $.each(pages, (key, val) => {
                const $item = $('<li>');

                const $link = $(`
                  <a href="${val.url}" target="_blank" class="maps-menu-full-screen__search__results__type__list__item"></a>
                `);

                if (val.thumbnail) {
                  $link.append($('<img>').attr('src', val.thumbnail));
                }

                $link.append(
                  $(`
                    <span class="maps-menu-full-screen__search__results__type__list__item__title">${val.title}</span>
                  `),
                );

                $item.append($link);
                $type.find('.maps-menu-full-screen__search__results__type__list').append($item);
              });

              $searchResults.append($type);
            });
          } else {
            $searchResults.html('No results for your query');
          }
        });
      }
    }

    hideSearch() {
      this.elements.$searchInput.val('');
      this.elements.$searchResults.html('');
      this.elements.$searchToggle.removeClass('on');
      this.elements.$search.removeClass('on');
    }

    hideMenu() {
      this.elements.$toggle.removeClass('on');
      this.elements.$menu.removeClass('on');
    }

    onInit() {
      elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

      const elementSettings = this.getElementSettings();

      if ('no' === elementSettings.transparent_header) {
        this.$element.height(this.elements.$widget.height());
      }

      this.elements.$subMenu.each(function() {
        const $li = $('<li>').addClass('menu-item menu-item-back').append($('<a>').attr('href', '#back').html('Menu'));

        $(this).prepend($li);
      });
    }
  }

  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler('maps-menu-full-screen', MenuFullScreen, 'default');
  });
})(jQuery);
