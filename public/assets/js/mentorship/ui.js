/**
 * Mentorship UI helpers: modal, toasts, loading states, skeletons.
 */
(function () {
    'use strict';

    if (!window.MentorshipSystem) {
        return;
    }

    Object.assign(window.MentorshipSystem, {
        showConfirmationModal: function (title, message, isDanger) {
            const dangerMode = !!isDanger;

            return new Promise((resolve) => {
                const modal = document.createElement('div');
                modal.className = 'ms-modal show';
                modal.innerHTML = `
                    <div class="ms-modal-dialog">
                        <div class="ms-modal-header ${dangerMode ? 'ms-danger' : ''}">
                            <h5 class="ms-modal-title">${title}</h5>
                            <button type="button" class="ms-close-modal">&times;</button>
                        </div>
                        <div class="ms-modal-body">
                            <p>${message}</p>
                        </div>
                        <div class="ms-modal-footer">
                            <button type="button" class="ms-btn ms-btn-secondary ms-cancel-btn">Cancel</button>
                            <button type="button" class="ms-btn ${dangerMode ? 'ms-btn-danger' : 'ms-btn-primary'} ms-confirm-btn">Confirm</button>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);

                const closeModal = (result) => {
                    modal.remove();
                    resolve(result);
                };

                modal.querySelector('.ms-close-modal').onclick = () => closeModal(false);
                modal.querySelector('.ms-cancel-btn').onclick = () => closeModal(false);
                modal.querySelector('.ms-confirm-btn').onclick = () => closeModal(true);
                modal.onclick = (event) => {
                    if (event.target === modal) {
                        closeModal(false);
                    }
                };
            });
        },

        showNotification: function (message, type) {
            const toastType = type || 'info';
            const toast = document.createElement('div');
            toast.className = `ms-toast ms-toast-${toastType}`;
            toast.innerHTML = `
                <span class="ms-toast-icon">${toastType === 'success' ? 'OK' : toastType === 'error' ? 'X' : 'i'}</span>
                <span class="ms-toast-message">${message}</span>
            `;

            let container = document.querySelector('.ms-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'ms-toast-container';
                document.body.appendChild(container);
            }

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('ms-toast-fade');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        },

        showSpinner: function (element, size) {
            const spinnerSize = size || 'md';
            const el = typeof element === 'string' ? document.querySelector(element) : element;
            if (!el) {
                return;
            }

            const sizeClass = spinnerSize === 'sm' ? 'loading-spinner--sm' : spinnerSize === 'lg' ? 'loading-spinner--lg' : '';
            el.innerHTML = `<div class="loading-spinner ${sizeClass}" role="status" aria-label="Loading"></div>`;
        },

        showLoadingOverlay: function (element) {
            const el = typeof element === 'string' ? document.querySelector(element) : element;
            if (!el) {
                return;
            }

            el.classList.add('is-loading');
            const overlay = document.createElement('div');
            overlay.className = 'ms-loading-overlay';
            overlay.setAttribute('role', 'status');
            overlay.setAttribute('aria-label', 'Loading');
            overlay.innerHTML = '<div class="loading-spinner"></div>';
            el.style.position = 'relative';
            el.appendChild(overlay);
        },

        hideLoadingOverlay: function (element) {
            const el = typeof element === 'string' ? document.querySelector(element) : element;
            if (!el) {
                return;
            }

            el.classList.remove('is-loading');
            const overlay = el.querySelector('.ms-loading-overlay');
            if (overlay) {
                overlay.remove();
            }
        },

        skeletonCard: function (options) {
            const config = options || {};
            const showAvatar = config.showAvatar !== false;
            const showImage = !!config.showImage;
            const lines = config.lines || 3;
            const showButton = !!config.showButton;

            let html = '<div class="ms-skeleton-card">';

            if (showImage) {
                html += '<div class="ms-skeleton ms-skeleton-image"></div>';
            }

            html += '<div class="ms-skeleton-card-content">';

            if (showAvatar) {
                html +=
                    '<div class="ms-skeleton-card-header">' +
                    '<div class="ms-skeleton ms-skeleton-avatar"></div>' +
                    '<div style="flex: 1;">' +
                    '<div class="ms-skeleton ms-skeleton-text" style="width: 60%;"></div>' +
                    '<div class="ms-skeleton ms-skeleton-text--sm" style="width: 40%;"></div>' +
                    '</div>' +
                    '</div>';
            }

            html += '<div class="ms-skeleton ms-skeleton-title"></div>';

            for (let i = 0; i < lines; i += 1) {
                const width = i === lines - 1 ? '70%' : '100%';
                html += `<div class="ms-skeleton ms-skeleton-text" style="width: ${width};"></div>`;
            }

            if (showButton) {
                html += '<div class="ms-skeleton ms-skeleton-button" style="margin-top: 1rem;"></div>';
            }

            html += '</div></div>';
            return html;
        },

        skeletonCards: function (count, options) {
            let html = '';
            for (let i = 0; i < count; i += 1) {
                html += this.skeletonCard(options);
            }
            return html;
        },

        showSkeletonCards: function (container, count, options) {
            const total = count || 3;
            const el = typeof container === 'string' ? document.querySelector(container) : container;
            if (!el) {
                return;
            }

            el.innerHTML = this.skeletonCards(total, options);
        },

        skeletonListItem: function () {
            return (
                '<div class="ms-skeleton-list-item">' +
                '<div class="ms-skeleton ms-skeleton-avatar"></div>' +
                '<div style="flex: 1;">' +
                '<div class="ms-skeleton ms-skeleton-text" style="width: 80%;"></div>' +
                '<div class="ms-skeleton ms-skeleton-text--sm" style="width: 50%;"></div>' +
                '</div>' +
                '</div>'
            );
        },

        showSkeletonList: function (container, count) {
            const total = count || 5;
            const el = typeof container === 'string' ? document.querySelector(container) : container;
            if (!el) {
                return;
            }

            let html = '';
            for (let i = 0; i < total; i += 1) {
                html += this.skeletonListItem();
            }
            el.innerHTML = html;
        },

        fetchWithLoading: async function (url, options, loadingElement) {
            const requestOptions = options || {};
            if (loadingElement) {
                this.showLoadingOverlay(loadingElement);
            }

            try {
                return await fetch(url, requestOptions);
            } finally {
                if (loadingElement) {
                    this.hideLoadingOverlay(loadingElement);
                }
            }
        },

        confirm: async function (message, options) {
            const opts = options || {};
            const title = opts.title || 'Confirm';
            const isDanger = !!opts.isDanger;
            return this.showConfirmationModal(title, message, isDanger);
        },

        createStarRating: function (containerId, initialRating, onSelect) {
            const defaultRating = initialRating || 0;
            const container = document.getElementById(containerId);
            if (!container) {
                return null;
            }

            container.innerHTML = '';
            container.className = 'star-rating';

            for (let i = 1; i <= 5; i += 1) {
                const star = document.createElement('span');
                star.className = `star ${i <= defaultRating ? 'star-filled' : 'star-empty'}`;
                star.innerHTML = i <= defaultRating ? '★' : '☆';
                star.dataset.rating = String(i);

                star.onclick = () => {
                    container.querySelectorAll('.star').forEach((s, idx) => {
                        const rating = idx + 1;
                        s.className = `star ${rating <= i ? 'star-filled' : 'star-empty'}`;
                        s.innerHTML = rating <= i ? '★' : '☆';
                    });

                    container.dataset.selectedRating = String(i);
                    if (typeof onSelect === 'function') {
                        onSelect(i);
                    }
                };

                container.appendChild(star);
            }

            return container;
        }
    });
})();
