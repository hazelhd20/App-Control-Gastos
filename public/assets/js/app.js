document.addEventListener('DOMContentLoaded', () => {
    renderIcons();
    bindPasswordToggles();
    bindMobileNavigation();
    bindGoalDynamicFields();
    bindAutoLimitCalculation();
    bindTransactionCategoryFiltering();
    triggerLimitAlert();
    initThemeToggle();
    initSidebarToggle();
    highlightActiveNav();
    bindToastDismiss();
});

function bindPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-password-toggle');
            const input = document.getElementById(targetId);
            if (!input) {
                return;
            }

            if (input.type === 'password') {
                input.type = 'text';
                button.innerText = 'Ocultar';
            } else {
                input.type = 'password';
                button.innerText = 'Mostrar';
            }
        });
    });
}

function bindMobileNavigation() {
    const trigger = document.querySelector('[data-mobile-nav]');
    const menu = document.querySelector('[data-mobile-menu]');

    if (!trigger || !menu) {
        return;
    }

    const setMenuVisibility = (shouldShow, options = {}) => {
        const { skipFocusReset = false } = options;
        const isHidden = !shouldShow;
        menu.classList.toggle('hidden', isHidden);
        menu.setAttribute('aria-hidden', String(isHidden));
        trigger.setAttribute('aria-expanded', String(!isHidden));

        if (shouldShow) {
            requestAnimationFrame(() => {
                const focusable = menu.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                focusable?.focus();
            });
        } else if (!skipFocusReset) {
            trigger.focus();
        }
    };

    setMenuVisibility(false, { skipFocusReset: true });

    trigger.addEventListener('click', () => {
        const willOpen = menu.classList.contains('hidden');
        setMenuVisibility(willOpen);
    });

    menu.querySelectorAll('[data-mobile-close]').forEach((button) => {
        button.addEventListener('click', () => {
            setMenuVisibility(false);
        });
    });

    const overlay = menu.querySelector('[data-mobile-overlay]');
    if (overlay) {
        overlay.addEventListener('click', () => {
            setMenuVisibility(false);
        });
    }
}

function bindGoalDynamicFields() {
    const goalSelect = document.querySelector('[data-goal-select]');
    const containers = document.querySelectorAll('[data-goal-container]');

    if (!goalSelect || containers.length === 0) {
        return;
    }

    const toggleContainers = () => {
        const value = goalSelect.value;

        containers.forEach((container) => {
            const goal = container.getAttribute('data-goal-container');
            container.classList.toggle('hidden', goal !== value);
        });
    };

    goalSelect.addEventListener('change', toggleContainers);
    toggleContainers();
}

function bindAutoLimitCalculation() {
    const incomeInput = document.querySelector('[data-income-input]');
    const autoLimitBtn = document.querySelector('[data-auto-limit]');
    const limitInput = document.querySelector('[data-limit-input]');
    const ratioInput = document.querySelector('[data-limit-ratio]');

    if (!incomeInput || !autoLimitBtn || !limitInput) {
        return;
    }

    autoLimitBtn.addEventListener('click', () => {
        const income = parseFloat(incomeInput.value);
        const ratio = parseFloat(ratioInput?.value || 0.7);

        if (Number.isNaN(income) || income <= 0) {
            alert('Ingresa primero tu ingreso mensual.');
            return;
        }

        const limit = Math.max(Math.round((income * ratio) * 100) / 100, 0);
        limitInput.value = limit.toFixed(2);
        limitInput.dispatchEvent(new Event('input'));
    });
}

function bindTransactionCategoryFiltering() {
    const typeInputs = document.querySelectorAll('input[name="type"]');
    const select = document.querySelector('[data-category-select]');

    if (!typeInputs.length || !select) {
        return;
    }

    const filterOptions = () => {
        const activeType = document.querySelector('input[name="type"]:checked')?.value || 'expense';
        select.querySelectorAll('option[data-category-type]').forEach((option) => {
            const optionType = option.getAttribute('data-category-type');
            const shouldHide = optionType !== activeType;
            option.classList.toggle('hidden', shouldHide);
            option.hidden = shouldHide;
        });

        const currentOption = select.selectedOptions[0];
        if (currentOption && currentOption.classList.contains('hidden')) {
            select.value = '';
        }
    };

    typeInputs.forEach((input) => {
        input.addEventListener('change', filterOptions);
    });

    filterOptions();
}

function triggerLimitAlert() {
    const container = document.querySelector('[data-limit-alert="1"]');
    if (!container) {
        return;
    }

    container.classList.add('ring-2', 'ring-danger/40', 'ring-offset-2', 'rounded-3xl', 'ring-offset-slate-50');
    playLimitBeep();
}

function playLimitBeep() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.type = 'sine';
        oscillator.frequency.value = 880;
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        gainNode.gain.setValueAtTime(0.0001, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.4, audioContext.currentTime + 0.01);
        gainNode.gain.exponentialRampToValueAtTime(0.0001, audioContext.currentTime + 0.4);

        oscillator.start();
        oscillator.stop(audioContext.currentTime + 0.45);
    } catch (error) {
        console.warn('No fue posible reproducir la alerta sonora.', error);
    }
}

function initThemeToggle() {
    const toggle = document.querySelector('[data-theme-toggle]');
    if (!toggle) {
        return;
    }

    const root = document.documentElement;
    const storageKey = 'acg-theme';

    const applyTheme = (theme) => {
        const resolved = theme === 'dark' ? 'dark' : 'light';
        const nextLabel = resolved === 'dark' ? 'Modo claro' : 'Modo oscuro';

        root.dataset.theme = resolved;
        root.classList.toggle('dark', resolved === 'dark');
        toggle.setAttribute('data-theme-state', resolved);
        toggle.setAttribute('aria-pressed', resolved === 'dark' ? 'true' : 'false');
        toggle.setAttribute('aria-label', resolved === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro');
        toggle.querySelectorAll('[data-theme-toggle-label]').forEach((label) => {
            label.textContent = nextLabel;
        });

        const sunIcon = toggle.querySelector('.theme-icon--light');
        const moonIcon = toggle.querySelector('.theme-icon--dark');
        if (sunIcon && moonIcon) {
            sunIcon.classList.toggle('hidden', resolved === 'dark');
            moonIcon.classList.toggle('hidden', resolved !== 'dark');
        }
    };

    const storedTheme = localStorage.getItem(storageKey);
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(storedTheme ?? (prefersDark ? 'dark' : 'light'));

    toggle.addEventListener('click', () => {
        const current = root.dataset.theme === 'dark' ? 'dark' : 'light';
        const next = current === 'dark' ? 'light' : 'dark';
        localStorage.setItem(storageKey, next);
        applyTheme(next);
    });
}

function initSidebarToggle() {
    const toggle = document.querySelector('[data-sidebar-toggle]');
    const sidebar = document.querySelector('[data-app-sidebar]');
    const appGrid = document.querySelector('[data-app-grid]');

    if (!toggle || !sidebar || !appGrid) {
        return;
    }

    const storageKey = 'acg-sidebar';
    const applyState = (isCollapsed) => {
        sidebar.classList.toggle('is-collapsed', isCollapsed);
        appGrid.classList.toggle('app-grid--collapsed', isCollapsed);
        toggle.setAttribute('aria-expanded', String(!isCollapsed));
        toggle.querySelector('[data-sidebar-toggle-label]').textContent = isCollapsed ? 'Expandir menú' : 'Colapsar menú';
        // Corrige acento en menú
        toggle.querySelector('[data-sidebar-toggle-label]').textContent = isCollapsed ? 'Expandir menú' : 'Colapsar menú';
        toggle.querySelector('[data-sidebar-toggle-label]').textContent = isCollapsed ? 'Expandir menú' : 'Colapsar menú';
        const icon = toggle.querySelector('svg');
        if (icon) {
            icon.style.transform = isCollapsed ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    };

    const storedState = localStorage.getItem(storageKey) === 'collapsed';
    applyState(storedState);

    toggle.addEventListener('click', () => {
        const willCollapse = !sidebar.classList.contains('is-collapsed');
        localStorage.setItem(storageKey, willCollapse ? 'collapsed' : 'expanded');
        applyState(willCollapse);
    });
}

function highlightActiveNav() {
    const currentPath = window.location.pathname.replace(/\/+$/, '');
    document.querySelectorAll('[data-nav-link]').forEach((link) => {
        const href = link.getAttribute('href') ?? '';
        const normalized = href.replace(/\/+$/, '');
        if (normalized === currentPath) {
            link.classList.add('is-active');
            link.setAttribute('aria-current', 'page');
            const parent = link.closest('[data-nav-pill]');
            if (parent) {
                parent.classList.add('is-active');
            }
        } else {
            link.classList.remove('is-active');
            link.removeAttribute('aria-current');
            const parent = link.closest('[data-nav-pill]');
            if (parent) {
                parent.classList.remove('is-active');
            }
        }
    });
}

function bindToastDismiss() {
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-dismiss-toast]');
        if (!button) {
            return;
        }

        const toast = button.closest('[data-toast]');
        if (!toast) {
            return;
        }

        toast.classList.add('ui-toast--closing');
        setTimeout(() => {
            toast.remove();
        }, 180);
    });
}

function renderIcons() {
    if (typeof lucide === 'undefined' || typeof lucide.createIcons !== 'function') {
        return;
    }

    lucide.createIcons();
}
