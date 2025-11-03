/**
 * Samachar Patra Theme JavaScript
 * Main theme functionality including navigation, sticky header, and interactive components
 * Version: 2.0.0
 */

(function() {
    'use strict';

    // Theme configuration
    const THEME_CONFIG = {
        breakpoints: {
            mobile: 768,
            tablet: 992,
            desktop: 1200
        },
        animations: {
            duration: 300,
            easing: 'ease-in-out'
        },
        sticky: {
            offset: 100,
            className: 'sticky'
        },
        debounceDelay: 50
    };

    // Utility functions
    const Utils = {
        // Debounce function for performance optimization
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        // Throttle function for scroll events
        throttle: function(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },

        // Check if element is visible
        isVisible: function(element) {
            if (!element) return false;
            const style = window.getComputedStyle(element);
            return style.display !== 'none' && 
                   style.visibility !== 'hidden' && 
                   parseFloat(style.opacity) > 0;
        },

        // Check current screen size
        getScreenSize: function() {
            const width = window.innerWidth;
            if (width < THEME_CONFIG.breakpoints.mobile) return 'mobile';
            if (width < THEME_CONFIG.breakpoints.tablet) return 'tablet';
            if (width < THEME_CONFIG.breakpoints.desktop) return 'desktop';
            return 'large';
        },

        // Smooth scroll to element
        smoothScrollTo: function(element, offset = 0) {
            if (!element) return;
            const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
            const offsetPosition = elementPosition - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        },

        // Add event listener with cleanup tracking
        addEventListenerWithCleanup: function(element, event, handler, options = {}) {
            if (!element) return null;
            element.addEventListener(event, handler, options);
            return () => element.removeEventListener(event, handler, options);
        }
    };

    // Mobile Navigation Handler
    const MobileNavigation = {
        isOpen: false,
        currentToggle: null,
        currentContainer: null,
        cleanupFunctions: [],

        init: function() {
            console.log('Initializing mobile navigation...');
            this.bindEvents();
            this.updateElements();
        },

        updateElements: function() {
            // Find the best available toggle and container
            const stickyToggle = document.querySelector('.main-navigation.sticky .menu-toggle');
            const stickyContainer = document.querySelector('.main-navigation.sticky .mobile-nav-container');
            const regularToggle = document.querySelector('.main-navigation:not(.sticky) .menu-toggle');
            const regularContainer = document.querySelector('.main-navigation:not(.sticky) .mobile-nav-container');

            // Use sticky if visible, otherwise use regular
            if (stickyToggle && Utils.isVisible(stickyToggle)) {
                this.currentToggle = stickyToggle;
                this.currentContainer = stickyContainer;
            } else if (regularToggle && Utils.isVisible(regularToggle)) {
                this.currentToggle = regularToggle;
                this.currentContainer = regularContainer;
            } else {
                // Fallback to any available elements
                this.currentToggle = document.querySelector('.menu-toggle');
                this.currentContainer = document.querySelector('.mobile-nav-container');
            }

            console.log('Mobile nav elements updated:', {
                toggle: !!this.currentToggle,
                container: !!this.currentContainer,
                isSticky: this.currentToggle?.closest('.main-navigation.sticky') !== null
            });

            this.setARIAAttributes();
        },

        setARIAAttributes: function() {
            if (this.currentToggle) {
                this.currentToggle.setAttribute('aria-expanded', this.isOpen.toString());
            }
            if (this.currentContainer) {
                this.currentContainer.setAttribute('aria-hidden', (!this.isOpen).toString());
            }
        },

        toggle: function() {
            if (!this.currentToggle || !this.currentContainer) {
                this.updateElements();
                if (!this.currentToggle || !this.currentContainer) {
                    console.warn('Cannot toggle menu: elements not found');
                    return;
                }
            }

            this.isOpen = !this.isOpen;
            console.log('Mobile menu toggled:', this.isOpen);

            this.setARIAAttributes();

            const body = document.body;
            const parentNav = this.currentToggle.closest('.main-navigation');

            if (this.isOpen) {
                this.currentToggle.classList.add('active');
                this.currentContainer.classList.add('active');
                body.classList.add('mobile-menu-open');
                if (parentNav) parentNav.classList.add('mobile-menu-open');
            } else {
                this.currentToggle.classList.remove('active');
                this.currentContainer.classList.remove('active');
                body.classList.remove('mobile-menu-open');
                // Remove from all navigation elements
                document.querySelectorAll('.main-navigation').forEach(nav => {
                    nav.classList.remove('mobile-menu-open');
                });
            }
        },

        close: function() {
            if (this.isOpen) {
                this.toggle();
            }
        },

        bindEvents: function() {
            // Clear existing events
            this.cleanup();

            const body = document.body;

            // Use event delegation for menu toggles
            const toggleHandler = (e) => {
                const toggle = e.target.closest('.menu-toggle');
                if (toggle) {
                    e.preventDefault();
                    this.updateElements();
                    if (toggle === this.currentToggle) {
                        this.toggle();
                    }
                }
            };

            // Outside click handler
            const outsideClickHandler = (e) => {
                if (this.isOpen && 
                    !e.target.closest('.menu-toggle') && 
                    !e.target.closest('.mobile-nav-container')) {
                    this.close();
                }
            };

            // Keyboard handler
            const keyboardHandler = (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                    if (this.currentToggle) {
                        this.currentToggle.focus();
                    }
                }
            };

            // Resize handler
            const resizeHandler = Utils.debounce(() => {
                if (Utils.getScreenSize() !== 'mobile' && this.isOpen) {
                    this.close();
                }
                this.updateElements();
            }, THEME_CONFIG.debounceDelay);

            // Scroll handler for sticky nav
            const scrollHandler = Utils.throttle(() => {
                this.updateElements();
            }, 100);

            // Submenu toggle handler
            const submenuHandler = (e) => {
                const submenuToggle = e.target.closest('.submenu-toggle');
                if (submenuToggle) {
                    e.preventDefault();
                    const submenu = submenuToggle.nextElementSibling;
                    const isExpanded = submenuToggle.getAttribute('aria-expanded') === 'true';
                    
                    submenuToggle.setAttribute('aria-expanded', (!isExpanded).toString());
                    if (submenu) {
                        submenu.classList.toggle('active');
                    }
                }
            };

            // Add event listeners and store cleanup functions
            this.cleanupFunctions = [
                Utils.addEventListenerWithCleanup(body, 'click', toggleHandler),
                Utils.addEventListenerWithCleanup(body, 'click', outsideClickHandler),
                Utils.addEventListenerWithCleanup(body, 'click', submenuHandler),
                Utils.addEventListenerWithCleanup(document, 'keydown', keyboardHandler),
                Utils.addEventListenerWithCleanup(window, 'resize', resizeHandler),
                Utils.addEventListenerWithCleanup(window, 'scroll', scrollHandler)
            ];
        },

        cleanup: function() {
            this.cleanupFunctions.forEach(cleanup => {
                if (typeof cleanup === 'function') cleanup();
            });
            this.cleanupFunctions = [];
        }
    };

    // Sticky Navigation Handler
    const StickyNavigation = {
        navigation: null,
        isSticky: false,
        scrollThreshold: THEME_CONFIG.sticky.offset,

        init: function() {
            this.navigation = document.querySelector('.main-navigation');
            if (!this.navigation) {
                console.warn('Navigation element not found for sticky functionality');
                return;
            }

            console.log('Initializing sticky navigation...');
            this.bindEvents();
            this.checkSticky(); // Initial check
        },

        checkSticky: function() {
            if (!this.navigation) return;

            const shouldBeSticky = window.pageYOffset > this.scrollThreshold;
            
            if (shouldBeSticky !== this.isSticky) {
                this.isSticky = shouldBeSticky;
                
                if (this.isSticky) {
                    this.navigation.classList.add(THEME_CONFIG.sticky.className);
                    document.body.classList.add('sticky-nav');
                } else {
                    this.navigation.classList.remove(THEME_CONFIG.sticky.className);
                    document.body.classList.remove('sticky-nav');
                }

                console.log('Sticky navigation toggled:', this.isSticky);
                
                // Update mobile navigation elements when sticky state changes
                if (window.MobileNavigation) {
                    window.MobileNavigation.updateElements();
                }
            }
        },

        bindEvents: function() {
            const scrollHandler = Utils.throttle(() => {
                this.checkSticky();
            }, 16); // ~60fps

            Utils.addEventListenerWithCleanup(window, 'scroll', scrollHandler);
        }
    };

    // Search Functionality
    const SearchHandler = {
        searchForm: null,
        searchInput: null,
        searchToggle: null,
        isSearchOpen: false,

        init: function() {
            this.searchToggle = document.querySelector('.search-toggle');
            this.searchForm = document.querySelector('.search-form');
            this.searchInput = document.querySelector('.search-field');

            if (this.searchToggle && this.searchForm) {
                console.log('Initializing search functionality...');
                this.bindEvents();
            }
        },

        toggleSearch: function() {
            this.isSearchOpen = !this.isSearchOpen;
            
            if (this.isSearchOpen) {
                this.searchForm.classList.add('active');
                if (this.searchInput) {
                    setTimeout(() => this.searchInput.focus(), 100);
                }
            } else {
                this.searchForm.classList.remove('active');
            }
        },

        bindEvents: function() {
            if (this.searchToggle) {
                this.searchToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleSearch();
                });
            }

            // Close search on escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isSearchOpen) {
                    this.toggleSearch();
                }
            });

            // Close search on outside click
            document.addEventListener('click', (e) => {
                if (this.isSearchOpen && 
                    !e.target.closest('.search-form') && 
                    !e.target.closest('.search-toggle')) {
                    this.toggleSearch();
                }
            });
        }
    };

    // Tab Functionality
    const TabHandler = {
        init: function() {
            const tabContainers = document.querySelectorAll('[data-tabs]');
            tabContainers.forEach(container => this.initTabContainer(container));
        },

        initTabContainer: function(container) {
            const tabButtons = container.querySelectorAll('.tab-btn');
            const tabPanes = container.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetId = button.getAttribute('data-tab');
                    this.switchTab(container, targetId);
                });
            });

            // Activate first tab by default
            if (tabButtons.length > 0) {
                const firstTab = tabButtons[0].getAttribute('data-tab');
                this.switchTab(container, firstTab);
            }
        },

        switchTab: function(container, targetId) {
            const tabButtons = container.querySelectorAll('.tab-btn');
            const tabPanes = container.querySelectorAll('.tab-pane');

            // Remove active classes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Add active classes
            const activeButton = container.querySelector(`[data-tab="${targetId}"]`);
            const activePane = container.querySelector(`#${targetId}`);

            if (activeButton) activeButton.classList.add('active');
            if (activePane) activePane.classList.add('active');
        }
    };

    // Smooth Scroll for Internal Links
    const SmoothScroll = {
        init: function() {
            const links = document.querySelectorAll('a[href^="#"]:not([href="#"])');
            links.forEach(link => {
                link.addEventListener('click', this.handleClick.bind(this));
            });
        },

        handleClick: function(e) {
            const href = e.currentTarget.getAttribute('href');
            const target = document.querySelector(href);
            
            if (target) {
                e.preventDefault();
                const offset = this.getOffset();
                Utils.smoothScrollTo(target, offset);
            }
        },

        getOffset: function() {
            const stickyNav = document.querySelector('.main-navigation.sticky');
            return stickyNav ? stickyNav.offsetHeight + 20 : 20;
        }
    };

    // Back to Top Button
    const BackToTop = {
        button: null,
        showThreshold: 300,

        init: function() {
            this.createButton();
            this.bindEvents();
        },

        createButton: function() {
            this.button = document.createElement('button');
            this.button.className = 'back-to-top';
            this.button.innerHTML = '<i class="fas fa-arrow-up"></i>';
            this.button.setAttribute('aria-label', 'Back to top');
            this.button.style.cssText = `
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 50px;
                height: 50px;
                background: #007cba;
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                z-index: 1000;
                display: flex;
                align-items: center;
                justify-content: center;
            `;
            document.body.appendChild(this.button);
        },

        show: function() {
            this.button.style.opacity = '1';
            this.button.style.visibility = 'visible';
        },

        hide: function() {
            this.button.style.opacity = '0';
            this.button.style.visibility = 'hidden';
        },

        bindEvents: function() {
            this.button.addEventListener('click', () => {
                Utils.smoothScrollTo(document.body, 0);
            });

            const scrollHandler = Utils.throttle(() => {
                if (window.pageYOffset > this.showThreshold) {
                    this.show();
                } else {
                    this.hide();
                }
            }, 100);

            window.addEventListener('scroll', scrollHandler);
        }
    };

    // Loading and Error States
    const LoadingStates = {
        init: function() {
            this.setupImageLoading();
            this.setupFormLoading();
        },

        setupImageLoading: function() {
            const images = document.querySelectorAll('img[data-src]');
            images.forEach(img => {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const image = entry.target;
                            image.src = image.getAttribute('data-src');
                            image.removeAttribute('data-src');
                            observer.unobserve(image);
                        }
                    });
                });
                observer.observe(img);
            });
        },

        setupFormLoading: function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                    }
                });
            });
        }
    };

    // Main Theme Controller
    const ThemeController = {
        initialized: false,

        init: function() {
            if (this.initialized) {
                console.warn('Theme already initialized');
                return;
            }

            console.log('Initializing Samachar Patra Theme...');

            // Initialize all components
            StickyNavigation.init();
            MobileNavigation.init();
            SearchHandler.init();
            TabHandler.init();
            SmoothScroll.init();
            BackToTop.init();
            LoadingStates.init();

            // Make mobile navigation globally accessible
            window.MobileNavigation = MobileNavigation;

            this.initialized = true;
            console.log('Theme initialization complete');

            // Dispatch custom event
            document.dispatchEvent(new CustomEvent('themeInitialized', {
                detail: { version: '2.0.0' }
            }));
        },

        reinit: function() {
            console.log('Reinitializing theme...');
            this.initialized = false;
            this.init();
        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            ThemeController.init();
        });
    } else {
        ThemeController.init();
    }

    // Handle dynamic content loading
    const observer = new MutationObserver(Utils.debounce((mutations) => {
        let shouldReinit = false;
        
        mutations.forEach(mutation => {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                // Check if important elements were added
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1 && // Element node
                        (node.classList?.contains('main-navigation') ||
                         node.querySelector?.('.main-navigation') ||
                         node.classList?.contains('menu-toggle'))) {
                        shouldReinit = true;
                    }
                });
            }
        });

        if (shouldReinit) {
            console.log('Important DOM changes detected, reinitializing...');
            ThemeController.reinit();
        }
    }, 500));

    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Smart Date System - Timezone Detection
    const SmartDateDetection = {
        init: function() {
            this.detectAndStoreTimezone();
            this.detectAndStoreLocation();
        },

        detectAndStoreTimezone: function() {
            try {
                const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                document.cookie = `user_timezone=${timezone}; path=/; max-age=2592000`; // 30 days
            } catch (e) {
                console.warn('Timezone detection failed:', e);
            }
        },

        detectAndStoreLocation: function() {
            try {
                const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                let isNepal = false;
                
                // Check if timezone indicates Nepal
                if (timezone === 'Asia/Katmandu' || timezone === 'Asia/Kathmandu') {
                    isNepal = true;
                }
                
                // Additional checks for Nepal detection
                const nepalTimezones = ['Asia/Katmandu', 'Asia/Kathmandu'];
                if (nepalTimezones.includes(timezone)) {
                    isNepal = true;
                }

                // Store location preference
                document.cookie = `user_location=${isNepal ? 'nepal' : 'international'}; path=/; max-age=2592000`; // 30 days
                
                // Store additional timezone info
                const now = new Date();
                const offset = now.getTimezoneOffset();
                document.cookie = `timezone_offset=${offset}; path=/; max-age=2592000`; // 30 days

            } catch (e) {
                console.warn('Location detection failed:', e);
                // Fallback to international
                document.cookie = `user_location=international; path=/; max-age=2592000`;
            }
        }
    };

    // Initialize Smart Date Detection
    SmartDateDetection.init();

    // Global error handler
    window.addEventListener('error', (e) => {
        console.error('Theme JavaScript Error:', e.error);
    });

    // Expose theme controller globally for debugging
    window.SamacharPatraTheme = ThemeController;

})();