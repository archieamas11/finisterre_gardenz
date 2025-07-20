"use strict";

// Performance cache for frequently accessed elements
const ElementCache = {
    legend: null,
    stats: null,
    showLegendBtn: null,
    minimizeLegendBtn: null,
    minimizeStatsBtn: null,
    
    init() {
        this.legend = document.getElementById('mapLegend');
        this.stats = document.getElementById('floatingStats');
        this.showLegendBtn = document.getElementById('showLegendBtn');
        this.minimizeLegendBtn = document.getElementById('minimizeLegend');
        this.minimizeStatsBtn = document.getElementById('minimizeStats');
    },
    
    get(elementName) {
        return this[elementName];
    }
};

// Enhanced animation utilities with better performance
const AnimationUtils = {
    // Animation queue to prevent conflicting animations
    animationQueue: new Map(),
    
    // Optimized RAF scheduler
    scheduler: {
        callbacks: [],
        isScheduled: false,
        
        add(callback) {
            this.callbacks.push(callback);
            if (!this.isScheduled) {
                this.isScheduled = true;
                requestAnimationFrame(() => {
                    this.callbacks.forEach(cb => cb());
                    this.callbacks.length = 0;
                    this.isScheduled = false;
                });
            }
        }
    },
    
    // Smooth slide down/up animation with better performance
    slideToggle: function(element, duration = 300) {
        if (!element) return Promise.resolve();
        
        const cardBody = element.querySelector('.card-body');
        if (!cardBody) return Promise.resolve();

        // Check if animation is already running
        const elementId = element.id || 'unknown';
        if (this.animationQueue.has(elementId)) {
            return this.animationQueue.get(elementId);
        }

        const isMinimized = element.classList.contains('minimized');
        
        const animationPromise = new Promise((resolve) => {
            // Use CSS custom properties for better performance
            cardBody.style.setProperty('--animation-duration', `${duration}ms`);
            cardBody.style.transition = `all var(--animation-duration) cubic-bezier(0.4, 0, 0.2, 1)`;
            
            this.scheduler.add(() => {
                if (isMinimized) {
                    element.classList.remove('minimized');
                } else {
                    element.classList.add('minimized');
                }
            });
            
            setTimeout(() => {
                // Clean up
                cardBody.style.removeProperty('--animation-duration');
                this.animationQueue.delete(elementId);
                resolve();
            }, duration);
        });
        
        this.animationQueue.set(elementId, animationPromise);
        return animationPromise;
    },

    // Optimized show/hide with fade and slide using Web Animations API
    toggleVisibility: function(element, show, duration = 500) {
        if (!element) return Promise.resolve();
        
        const elementId = element.id || 'unknown';
        
        // Cancel any existing animation
        if (this.animationQueue.has(elementId)) {
            const existingAnimation = this.animationQueue.get(elementId);
            if (existingAnimation.cancel) {
                existingAnimation.cancel();
            }
        }
        
        // Use Web Animations API for better performance
        const keyframes = show ? [
            { 
                opacity: '0', 
                transform: 'translateX(100%) scale(0.8)', 
                visibility: 'hidden' 
            },
            { 
                opacity: '1', 
                transform: 'translateX(0) scale(1)', 
                visibility: 'visible' 
            }
        ] : [
            { 
                opacity: '1', 
                transform: 'translateX(0) scale(1)', 
                visibility: 'visible' 
            },
            { 
                opacity: '0', 
                transform: 'translateX(100%) scale(0.8)', 
                visibility: 'hidden' 
            }
        ];
        
        const animation = element.animate(keyframes, {
            duration,
            easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
            fill: 'forwards'
        });
        
        this.animationQueue.set(elementId, animation);
        
        return new Promise((resolve) => {
            if (show) {
                element.classList.remove('d-none');
            }
            
            animation.addEventListener('finish', () => {
                if (!show) {
                    element.classList.add('d-none');
                }
                
                // Clean up animation queue
                this.animationQueue.delete(elementId);
                resolve();
            });
            
            animation.addEventListener('cancel', () => {
                this.animationQueue.delete(elementId);
                resolve();
            });
        });
    },

    // Optimized icon rotation with CSS transforms
    rotateIcon: function(iconElement, isMinimized) {
        if (!iconElement) return;
        
        // Use CSS custom properties for better performance
        iconElement.style.setProperty('--rotation-duration', '0.3s');
        iconElement.style.transition = 'transform var(--rotation-duration) ease';
        
        this.scheduler.add(() => {
            if (isMinimized) {
                iconElement.classList.remove('bi-dash-lg');
                iconElement.classList.add('bi-plus-lg');
            } else {
                iconElement.classList.remove('bi-plus-lg');
                iconElement.classList.add('bi-dash-lg');
            }
        });
    },
    
    // Cleanup method for better memory management
    cleanup: function() {
        this.animationQueue.forEach(animation => {
            if (animation.cancel) {
                animation.cancel();
            }
        });
        this.animationQueue.clear();
    }
};

// Enhanced state management with debouncing and validation
const StateManager = {
    saveDebounceTime: 150,
    saveTimeout: null,
    
    saveState() {
        // Debounce saves to prevent excessive localStorage writes
        clearTimeout(this.saveTimeout);
        this.saveTimeout = setTimeout(() => {
            const legend = ElementCache.get('legend');
            const stats = ElementCache.get('stats');
            
            if (!legend || !stats) return;
            
            const state = {
                legendHidden: legend.classList.contains('d-none'),
                legendMinimized: legend.classList.contains('minimized'),
                statsHidden: stats.classList.contains('d-none'),
                statsMinimized: stats.classList.contains('minimized'),
                timestamp: Date.now(),
                version: '1.1' // For future compatibility
            };
            
            try {
                localStorage.setItem('cemeterease-ui-state', JSON.stringify(state));
            } catch (error) {
                console.warn('Failed to save UI state to localStorage:', error);
            }
        }, this.saveDebounceTime);
    },
    
    loadState() {
        try {
            const savedState = localStorage.getItem('cemeterease-ui-state');
            if (!savedState) return;
            
            const state = JSON.parse(savedState);
            
            // Validate state structure and age (24 hours max)
            if (!state.version || (Date.now() - (state.timestamp || 0)) > 24 * 60 * 60 * 1000) {
                localStorage.removeItem('cemeterease-ui-state');
                return;
            }
            
            const legend = ElementCache.get('legend');
            const stats = ElementCache.get('stats');
            
            if (!legend || !stats) return;
            
            // Batch DOM updates for better performance
            requestAnimationFrame(() => {
                // Temporarily disable transitions for initial load
                const originalLegendTransition = legend.style.transition;
                const originalStatsTransition = stats.style.transition;
                
                legend.style.transition = 'none';
                stats.style.transition = 'none';
                
                // Apply state changes
                legend.classList.toggle('d-none', state.legendHidden);
                stats.classList.toggle('d-none', state.statsHidden);
                legend.classList.toggle('minimized', state.legendMinimized);
                stats.classList.toggle('minimized', state.statsMinimized);
                
                // Force reflow
                legend.offsetHeight;
                stats.offsetHeight;
                
                // Re-enable transitions
                legend.style.transition = originalLegendTransition;
                stats.style.transition = originalStatsTransition;
                
                // Update UI elements
                updateMinimizeIcons();
                updateLegendButtonState();
            });
            
        } catch (error) {
            console.warn('Failed to load UI state from localStorage:', error);
            localStorage.removeItem('cemeterease-ui-state');
        }
    }
};

// Legacy functions for backward compatibility
function saveState() {
    StateManager.saveState();
}

function loadState() {
    StateManager.loadState();
}

// Optimized icon update function with caching
function updateMinimizeIcons() {
    const legend = ElementCache.get('legend');
    const stats = ElementCache.get('stats');
    
    if (!legend || !stats) return;
    
    // Cache icon elements for better performance
    if (!updateMinimizeIcons.iconCache) {
        updateMinimizeIcons.iconCache = {
            legendIcon: legend.querySelector('#minimizeLegend i'),
            statsIcon: stats.querySelector('#minimizeStats i')
        };
    }
    
    const { legendIcon, statsIcon } = updateMinimizeIcons.iconCache;
    
    // Batch icon updates
    AnimationUtils.scheduler.add(() => {
        if (legendIcon) {
            const isMinimized = legend.classList.contains('minimized');
            AnimationUtils.rotateIcon(legendIcon, isMinimized);
        }
        
        if (statsIcon) {
            const isMinimized = stats.classList.contains('minimized');
            AnimationUtils.rotateIcon(statsIcon, isMinimized);
        }
    });
}

// Function to update button appearance based on legend visibility
function updateLegendButtonState() {
    const showLegendBtn = ElementCache.get('showLegendBtn');
    const legend = ElementCache.get('legend');
    
    if (!showLegendBtn || !legend) return;
    
    const isHidden = legend.classList.contains('d-none');
    const icon = showLegendBtn.querySelector('i');
    const textSpan = showLegendBtn.querySelector('span');
    
    AnimationUtils.scheduler.add(() => {
        if (isHidden) {
            // Legends are hidden - show eye icon and "Show" text
            if (icon) icon.className = 'bi bi-eye me-1';
            if (textSpan) textSpan.textContent = 'Show ';
        } else {
            // Legends are visible - show eye-slash icon and "Hide" text
            if (icon) icon.className = 'bi bi-eye-slash me-1';
            if (textSpan) textSpan.textContent = 'Hide ';
        }
    });
}

// Performance optimization - throttled resize handler with passive events
const ResizeHandler = {
    timeout: null,
    isHandling: false,
    
    init() {
        // Use passive listener for better scroll performance
        window.addEventListener('resize', this.handleResize.bind(this), { passive: true });
    },
    
    handleResize() {
        if (this.isHandling) return;
        
        this.isHandling = true;
        clearTimeout(this.timeout);
        
        this.timeout = setTimeout(() => {
            const legend = ElementCache.get('legend');
            const stats = ElementCache.get('stats');
            
            if (legend && !legend.classList.contains('d-none')) {
                // Use transform3d for hardware acceleration
                legend.style.transform = 'translate3d(0, 0, 0)';
                stats.style.transform = 'translate3d(0, 0, 0)';
                
                // Reset after a short delay
                setTimeout(() => {
                    legend.style.transform = '';
                    stats.style.transform = '';
                }, 50);
            }
            
            this.isHandling = false;
        }, 250);
    }
};

// Enhanced intersection observer for better performance and memory management
const PerformanceObserver = {
    observer: null,
    observedElements: new WeakSet(),
    
    init() {
        const observerOptions = {
            root: null,
            rootMargin: '10px',
            threshold: [0, 0.1, 1]
        };
        
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Element is visible, enable optimizations
                    entry.target.style.willChange = 'transform, opacity';
                    entry.target.style.contain = 'layout style paint';
                } else {
                    // Element not visible, disable expensive properties
                    entry.target.style.willChange = 'auto';
                    entry.target.style.contain = '';
                }
            });
        }, observerOptions);
    },
    
    observe(element) {
        if (!this.observer || this.observedElements.has(element)) return;
        
        this.observer.observe(element);
        this.observedElements.add(element);
    },
    
    unobserve(element) {
        if (!this.observer || !this.observedElements.has(element)) return;
        
        this.observer.unobserve(element);
        this.observedElements.delete(element);
    },
    
    cleanup() {
        if (this.observer) {
            this.observer.disconnect();
            this.observedElements = new WeakSet();
        }
    }
};

// Optimized smooth scroll function with better performance
function smoothScrollToLegendItem(item) {
    if (!item) return;
    
    const container = item.closest('.card-body');
    if (!container) return;
    
    // Use smooth scrolling with intersection observer for better performance
    const targetPosition = item.offsetTop - container.offsetTop;
    
    // Use modern scrollTo API with better easing
    container.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
    });
    
    // Optional: Use custom scroll animation for better control
    // const startPosition = container.scrollTop;
    // const distance = targetPosition - startPosition;
    // const duration = 300;
    // let start = null;
    
    // function animation(currentTime) {
    //     if (start === null) start = currentTime;
    //     const timeElapsed = currentTime - start;
    //     const run = easeInOutCubic(timeElapsed, startPosition, distance, duration);
    //     container.scrollTop = run;
    //     if (timeElapsed < duration) requestAnimationFrame(animation);
    // }
    
    // function easeInOutCubic(t, b, c, d) {
    //     t /= d/2;
    //     if (t < 1) return c/2*t*t*t + b;
    //     t -= 2;
    //     return c/2*(t*t*t + 2) + b;
    // }
    
    // requestAnimationFrame(animation);
}

// Enhanced event listeners with optimized event handling
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all systems
    ElementCache.init();
    PerformanceObserver.init();
    ResizeHandler.init();
    
    // Observe elements for performance optimization
    const legend = ElementCache.get('legend');
    const stats = ElementCache.get('stats');
    
    if (legend) PerformanceObserver.observe(legend);
    if (stats) PerformanceObserver.observe(stats);
    
    // Initialize state after DOM is ready
    requestAnimationFrame(() => {
        StateManager.loadState();
    });

    // Enhanced legend minimize/expand functionality with better performance
    const minimizeLegendBtn = ElementCache.get('minimizeLegendBtn');
    if (minimizeLegendBtn) {
        minimizeLegendBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Prevent multiple rapid clicks
            if (this.dataset.animating === 'true') return;
            this.dataset.animating = 'true';
            
            const legend = ElementCache.get('legend');
            const icon = this.querySelector('i');
            
            try {
                // Perform animation with better error handling
                await AnimationUtils.slideToggle(legend, 300);
                
                // Update icon state
                const isMinimized = legend.classList.contains('minimized');
                AnimationUtils.rotateIcon(icon, isMinimized);
                
                // Save state
                StateManager.saveState();
                
            } catch (error) {
                console.error('Legend minimize animation failed:', error);
            } finally {
                this.dataset.animating = 'false';
            }
        }, { passive: false });
    }

    // Enhanced stats minimize/expand functionality
    const minimizeStatsBtn = ElementCache.get('minimizeStatsBtn');
    if (minimizeStatsBtn) {
        minimizeStatsBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Prevent multiple rapid clicks
            if (this.dataset.animating === 'true') return;
            this.dataset.animating = 'true';
            
            const stats = ElementCache.get('stats');
            const icon = this.querySelector('i');
            
            try {
                // Perform animation with better error handling
                await AnimationUtils.slideToggle(stats, 300);
                
                // Update icon state
                const isMinimized = stats.classList.contains('minimized');
                AnimationUtils.rotateIcon(icon, isMinimized);
                
                // Save state
                StateManager.saveState();
                
            } catch (error) {
                console.error('Stats minimize animation failed:', error);
            } finally {
                this.dataset.animating = 'false';
            }
        }, { passive: false });
    }

    // Enhanced legend toggle button functionality with improved performance
    const showLegendBtn = ElementCache.get('showLegendBtn');
    if (showLegendBtn) {
        // Make the function accessible globally for state management
        showLegendBtn.updateButtonState = updateLegendButtonState;
        
        // Initialize button state
        updateLegendButtonState();
        
        showLegendBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            // Prevent multiple clicks during animation
            if (this.dataset.animating === 'true') return;
            this.dataset.animating = 'true';
            this.disabled = true;
            
            const legend = ElementCache.get('legend');
            const stats = ElementCache.get('stats');
            const isCurrentlyHidden = legend.classList.contains('d-none');
            
            try {
                if (isCurrentlyHidden) {
                    // Show both elements with optimized staggered animation
                    const showPromises = [
                        AnimationUtils.toggleVisibility(legend, true, 500),
                        new Promise(resolve => {
                            setTimeout(() => {
                                AnimationUtils.toggleVisibility(stats, true, 500).then(resolve);
                            }, 150); // Stagger delay
                        })
                    ];
                    
                    await Promise.all(showPromises);
                    
                    // Ensure they're expanded when showing
                    legend.classList.remove('minimized');
                    stats.classList.remove('minimized');
                    
                    // Update minimize icons
                    updateMinimizeIcons();
                    
                } else {
                    // Hide both elements with optimized staggered animation
                    const hidePromises = [
                        AnimationUtils.toggleVisibility(stats, false, 500),
                        new Promise(resolve => {
                            setTimeout(() => {
                                AnimationUtils.toggleVisibility(legend, false, 500).then(resolve);
                            }, 150); // Stagger delay
                        })
                    ];
                    
                    await Promise.all(hidePromises);
                }
                
                // Update button appearance after animation
                updateLegendButtonState();
                
            } catch (error) {
                console.error('Legend toggle animation failed:', error);
            } finally {
                // Restore button state
                this.dataset.animating = 'false';
                this.disabled = false;
                
                StateManager.saveState();
            }
        }, { passive: false });
    }

    // Optimized hover effects with better performance and memory management
    const HoverEffects = {
        activeCards: new WeakSet(),
        
        init() {
            // Use event delegation for better performance
            document.addEventListener('mouseenter', this.handleMouseEnter.bind(this), true);
            document.addEventListener('mouseleave', this.handleMouseLeave.bind(this), true);
        },
        
        handleMouseEnter(e) {
            const card = e.target.closest('.legend-card');
            if (!card || this.activeCards.has(card)) return;
            
            if (!card.closest('.minimized')) {
                this.activeCards.add(card);
                AnimationUtils.scheduler.add(() => {
                    card.style.transform = 'translateY(-2px)';
                    card.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
                    card.style.transition = 'transform 0.2s ease, box-shadow 0.2s ease';
                });
            }
        },
        
        handleMouseLeave(e) {
            const card = e.target.closest('.legend-card');
            if (!card || !this.activeCards.has(card)) return;
            
            this.activeCards.delete(card);
            AnimationUtils.scheduler.add(() => {
                card.style.transform = '';
                card.style.boxShadow = '';
            });
        }
    };
    
    HoverEffects.init();

    // Enhanced keyboard accessibility with better event handling
    const KeyboardHandler = {
        init() {
            document.addEventListener('keydown', this.handleKeyDown.bind(this), { passive: false });
        },
        
        handleKeyDown(e) {
            // Only handle if no input is focused
            if (document.activeElement.tagName === 'INPUT' || 
                document.activeElement.tagName === 'TEXTAREA' ||
                document.activeElement.isContentEditable) return;
            
            // Toggle legend with 'L' key (Ctrl+L)
            if (e.key.toLowerCase() === 'l' && e.ctrlKey) {
                e.preventDefault();
                const showLegendBtn = ElementCache.get('showLegendBtn');
                showLegendBtn?.click();
            }
            
            // Minimize legend with 'M' key (Ctrl+M)
            if (e.key.toLowerCase() === 'm' && e.ctrlKey) {
                e.preventDefault();
                const minimizeLegendBtn = ElementCache.get('minimizeLegendBtn');
                minimizeLegendBtn?.click();
            }
            
            // Escape key to hide legends
            if (e.key === 'Escape') {
                const legend = ElementCache.get('legend');
                const showLegendBtn = ElementCache.get('showLegendBtn');
                if (legend && !legend.classList.contains('d-none')) {
                    showLegendBtn?.click();
                }
            }
        }
    };
    
    KeyboardHandler.init();

    // Enhanced mobile interaction handling with improved touch support
    const MobileHandler = {
        isMobile: window.innerWidth <= 768,
        touchStartTime: 0,
        
        init() {
            this.updateMobileState();
            window.addEventListener('resize', this.updateMobileState.bind(this), { passive: true });
            
            if (this.isMobile) {
                this.setupMobileEvents();
            }
        },
        
        updateMobileState() {
            this.isMobile = window.innerWidth <= 768;
        },
        
        setupMobileEvents() {
            // Use passive touch events for better performance
            document.addEventListener('touchstart', this.handleTouchStart.bind(this), { passive: true });
            document.addEventListener('click', this.handleClick.bind(this), { passive: false });
        },
        
        handleTouchStart(e) {
            this.touchStartTime = Date.now();
        },
        
        handleClick(e) {
            // Only handle if this is a real click (not a touch that became a click)
            const timeSinceTouchStart = Date.now() - this.touchStartTime;
            if (timeSinceTouchStart < 100) return; // Likely a touch event
            
            const legend = ElementCache.get('legend');
            const stats = ElementCache.get('stats');
            const minimizeLegendBtn = ElementCache.get('minimizeLegendBtn');
            const minimizeStatsBtn = ElementCache.get('minimizeStatsBtn');
            
            const isClickInsideLegend = legend?.contains(e.target);
            const isClickInsideStats = stats?.contains(e.target);
            const isToggleButton = e.target.closest('#showLegendBtn');
            
            if (!isClickInsideLegend && !isClickInsideStats && !isToggleButton) {
                if (legend && !legend.classList.contains('d-none')) {
                    // Auto-minimize on mobile when clicking outside
                    if (!legend.classList.contains('minimized')) {
                        minimizeLegendBtn?.click();
                    }
                    if (stats && !stats.classList.contains('minimized')) {
                        minimizeStatsBtn?.click();
                    }
                }
            }
        }
    };
    
    MobileHandler.init();
});

// Enhanced cleanup and memory management
window.addEventListener('beforeunload', function() {
    // Cleanup animations and observers
    AnimationUtils.cleanup();
    PerformanceObserver.cleanup();
    
    // Clear any pending timeouts
    if (StateManager.saveTimeout) {
        clearTimeout(StateManager.saveTimeout);
    }
});

// Enhanced export with better performance monitoring
window.CemetereaseAnimations = {
    AnimationUtils,
    StateManager,
    ElementCache,
    PerformanceObserver,
    saveState,
    loadState,
    updateMinimizeIcons,
    updateLegendButtonState,
    smoothScrollToLegendItem,
    
    // Performance monitoring utilities
    getPerformanceMetrics() {
        return {
            animationQueueSize: AnimationUtils.animationQueue.size,
            observedElementsCount: PerformanceObserver.observedElements.size || 0,
            cachedElementsCount: Object.keys(ElementCache).filter(key => 
                typeof ElementCache[key] !== 'function' && ElementCache[key] !== null
            ).length
        };
    },
    
    // Cleanup method for programmatic cleanup
    cleanup() {
        AnimationUtils.cleanup();
        PerformanceObserver.cleanup();
        if (StateManager.saveTimeout) {
            clearTimeout(StateManager.saveTimeout);
        }
    }
};