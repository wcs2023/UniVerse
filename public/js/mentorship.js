/**
 * UniVerse Mentorship System - JavaScript Module
 * Handles countdown timers, meeting join logic, AJAX interactions,
 * skeleton loaders, loading states, and UI utilities
 */

const MentorshipSystem = {
    // Configuration
    config: {
        joinWindowMinutes: 15, // Can join 15 minutes before session
        sessionEndWindowMinutes: 30, // Session considered ended after 30 minutes
        countdownUpdateInterval: 1000, // Update every second
        baseUrl: window.BASE_URL || ''
    },

    // Active countdown intervals
    countdownIntervals: {},

    /**
     * Initialize the mentorship system
     */
    init: function() {
        this.initCountdowns();
        this.initNotificationPolling();
    },

    /**
     * Initialize countdown timers for all session cards
     */
    initCountdowns: function() {
        const sessionCards = document.querySelectorAll('[data-session-datetime]');
        
        sessionCards.forEach(card => {
            const sessionId = card.dataset.sessionId;
            const sessionDatetime = card.dataset.sessionDatetime;
            const meetingLink = card.dataset.meetingLink;
            
            if (sessionId && sessionDatetime) {
                this.startCountdown(sessionId, sessionDatetime, meetingLink, card);
            }
        });
    },

    /**
     * Start countdown for a specific session
     */
    startCountdown: function(sessionId, sessionDatetime, meetingLink, cardElement) {
        const countdownElement = cardElement.querySelector('.countdown-timer');
        const joinButton = cardElement.querySelector('.join-meeting-btn');
        const statusBadge = cardElement.querySelector('.session-status-badge');
        
        // Clear any existing interval for this session
        if (this.countdownIntervals[sessionId]) {
            clearInterval(this.countdownIntervals[sessionId]);
        }

        const updateCountdown = () => {
            const status = this.calculateTimeStatus(sessionDatetime);
            
            // Update countdown display
            if (countdownElement) {
                countdownElement.innerHTML = this.formatCountdownDisplay(status);
            }
            
            // Update status badge
            if (statusBadge) {
                this.updateStatusBadge(statusBadge, status);
            }
            
            // Update join button
            if (joinButton) {
                this.updateJoinButton(joinButton, status, meetingLink);
            }

            // Stop countdown if session has ended
            if (status.hasEnded) {
                clearInterval(this.countdownIntervals[sessionId]);
                delete this.countdownIntervals[sessionId];
            }
        };

        // Initial update
        updateCountdown();
        
        // Set interval for updates
        this.countdownIntervals[sessionId] = setInterval(updateCountdown, this.config.countdownUpdateInterval);
    },

    /**
     * Calculate time status relative to session datetime
     */
    calculateTimeStatus: function(sessionDatetime) {
        const now = new Date();
        const sessionTime = new Date(sessionDatetime);
        const diffMs = sessionTime - now;
        const diffMinutes = diffMs / (1000 * 60);
        
        const days = Math.floor(Math.abs(diffMinutes) / (60 * 24));
        const hours = Math.floor((Math.abs(diffMinutes) % (60 * 24)) / 60);
        const minutes = Math.floor(Math.abs(diffMinutes) % 60);
        const seconds = Math.floor((Math.abs(diffMs) / 1000) % 60);

        return {
            diffMinutes: diffMinutes,
            days: days,
            hours: hours,
            minutes: minutes,
            seconds: seconds,
            canJoin: diffMinutes <= this.config.joinWindowMinutes && diffMinutes > -this.config.sessionEndWindowMinutes,
            isActive: diffMinutes <= 0 && diffMinutes > -this.config.sessionEndWindowMinutes,
            hasEnded: diffMinutes <= -this.config.sessionEndWindowMinutes,
            isUpcoming: diffMinutes > this.config.joinWindowMinutes,
            isStartingSoon: diffMinutes <= this.config.joinWindowMinutes && diffMinutes > 0,
            sessionTime: sessionTime
        };
    },

    /**
     * Format countdown display based on status
     */
    formatCountdownDisplay: function(status) {
        if (status.hasEnded) {
            return '<span class="countdown-ended">Session Ended</span>';
        }
        
        if (status.isActive) {
            return '<span class="countdown-active">🟢 Session In Progress</span>';
        }
        
        if (status.isStartingSoon) {
            return `<span class="countdown-soon">
                Starting in: <strong>${status.minutes}m ${status.seconds}s</strong>
            </span>`;
        }
        
        // Upcoming session
        let timeString = '';
        if (status.days > 0) {
            timeString = `${status.days}d ${status.hours}h ${status.minutes}m`;
        } else if (status.hours > 0) {
            timeString = `${status.hours}h ${status.minutes}m ${status.seconds}s`;
        } else {
            timeString = `${status.minutes}m ${status.seconds}s`;
        }
        
        return `<span class="countdown-upcoming">
            Starts in: <strong>${timeString}</strong>
        </span>`;
    },

    /**
     * Update status badge based on session status
     */
    updateStatusBadge: function(badgeElement, status) {
        badgeElement.classList.remove('badge-upcoming', 'badge-soon', 'badge-active', 'badge-ended');
        
        if (status.hasEnded) {
            badgeElement.className = 'session-status-badge badge-ended';
            badgeElement.textContent = 'Ended';
        } else if (status.isActive) {
            badgeElement.className = 'session-status-badge badge-active';
            badgeElement.textContent = '🔴 LIVE';
        } else if (status.isStartingSoon) {
            badgeElement.className = 'session-status-badge badge-soon';
            badgeElement.textContent = 'Starting Soon';
        } else {
            badgeElement.className = 'session-status-badge badge-upcoming';
            badgeElement.textContent = 'Upcoming';
        }
    },

    /**
     * Update join button based on session status
     */
    updateJoinButton: function(buttonElement, status, meetingLink) {
        if (status.canJoin && meetingLink) {
            buttonElement.disabled = false;
            buttonElement.classList.add('btn-join-active');
            buttonElement.classList.remove('btn-join-disabled');
            buttonElement.innerHTML = '🎥 Join Meeting Now';
            buttonElement.onclick = () => this.joinMeeting(meetingLink);
        } else if (status.hasEnded) {
            buttonElement.disabled = true;
            buttonElement.classList.add('btn-join-disabled');
            buttonElement.classList.remove('btn-join-active');
            buttonElement.innerHTML = 'Session Ended';
            buttonElement.onclick = null;
        } else {
            buttonElement.disabled = true;
            buttonElement.classList.add('btn-join-disabled');
            buttonElement.classList.remove('btn-join-active');
            buttonElement.innerHTML = '⏳ Wait for Session Time';
            buttonElement.onclick = null;
        }
    },

    /**
     * Join a meeting by opening Jitsi link in new tab
     */
    joinMeeting: function(meetingLink) {
        if (meetingLink) {
            window.open(meetingLink, '_blank', 'noopener,noreferrer');
        } else {
            this.showNotification('Meeting link not available', 'error');
        }
    },

    /**
     * Confirm time slot selection
     */
    confirmTimeSlot: async function(requestId, slotId, buttonElement) {
        if (!requestId || !slotId) {
            this.showNotification('Missing information', 'error');
            return;
        }

        // Show confirmation modal
        const confirmed = await this.showConfirmationModal(
            'Confirm Time Slot',
            'Are you sure you want to confirm this time slot? Once confirmed, the session will be locked.'
        );

        if (!confirmed) return;

        // Disable button and show loading
        if (buttonElement) {
            buttonElement.disabled = true;
            buttonElement.textContent = 'Confirming...';
        }

        try {
            const response = await fetch(`${this.config.baseUrl}/umentorships/confirmTimeSlot`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    request_id: requestId,
                    slot_id: slotId
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Session confirmed successfully! 🎉', 'success');
                setTimeout(() => {
                    window.location.href = `${this.config.baseUrl}/umentorships?success=scheduled`;
                }, 1500);
            } else {
                this.showNotification(data.message || 'Failed to confirm session', 'error');
                if (buttonElement) {
                    buttonElement.disabled = false;
                    buttonElement.textContent = '✓ Confirm This Time';
                }
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
            if (buttonElement) {
                buttonElement.disabled = false;
                buttonElement.textContent = '✓ Confirm This Time';
            }
        }
    },

    /**
     * Submit session feedback
     */
    submitFeedback: async function(sessionId, rating, reviewText) {
        if (!sessionId || !rating) {
            this.showNotification('Please provide a rating', 'error');
            return false;
        }

        try {
            const response = await fetch(`${this.config.baseUrl}/umentorships/submitFeedback`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    rating: rating,
                    review_text: reviewText || ''
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Thank you for your feedback! ⭐', 'success');
                return true;
            } else {
                this.showNotification(data.message || 'Failed to submit feedback', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
            return false;
        }
    },

    /**
     * Cancel a session
     */
    cancelSession: async function(sessionId, reason = '') {
        const confirmed = await this.showConfirmationModal(
            'Cancel Session',
            'Are you sure you want to cancel this session? This action cannot be undone.',
            true // Show danger styling
        );

        if (!confirmed) return false;

        try {
            const response = await fetch(`${this.config.baseUrl}/umentorships/cancelSession`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    reason: reason
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Session cancelled', 'success');
                setTimeout(() => window.location.reload(), 1500);
                return true;
            } else {
                this.showNotification(data.message || 'Failed to cancel session', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
            return false;
        }
    },

    /**
     * Show confirmation modal
     */
    showConfirmationModal: function(title, message, isDanger = false) {
        return new Promise((resolve) => {
            const modal = document.createElement('div');
            modal.className = 'ms-modal show';
            modal.innerHTML = `
                <div class="ms-modal-dialog">
                    <div class="ms-modal-header ${isDanger ? 'ms-danger' : ''}">
                        <h5 class="ms-modal-title">${title}</h5>
                        <button type="button" class="ms-close-modal">&times;</button>
                    </div>
                    <div class="ms-modal-body">
                        <p>${message}</p>
                    </div>
                    <div class="ms-modal-footer">
                        <button type="button" class="ms-btn ms-btn-secondary ms-cancel-btn">Cancel</button>
                        <button type="button" class="ms-btn ${isDanger ? 'ms-btn-danger' : 'ms-btn-primary'} ms-confirm-btn">Confirm</button>
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
            modal.onclick = (e) => {
                if (e.target === modal) closeModal(false);
            };
        });
    },

    /**
     * Show notification toast
     */
    showNotification: function(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `ms-toast ms-toast-${type}`;
        toast.innerHTML = `
            <span class="ms-toast-icon">${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}</span>
            <span class="ms-toast-message">${message}</span>
        `;

        // Find or create toast container
        let container = document.querySelector('.ms-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'ms-toast-container';
            document.body.appendChild(container);
        }

        container.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('ms-toast-fade');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    },

    /**
     * Initialize notification polling (check for new notifications periodically)
     */
    initNotificationPolling: function() {
        // Poll every 60 seconds
        setInterval(() => this.checkNotifications(), 60000);
    },

    /**
     * Check for new notifications
     */
    checkNotifications: async function() {
        try {
            const endpoint = window.USER_TYPE === 'alumni' 
                ? '/amentorships/getNotifications' 
                : '/umentorships/getNotifications';
            
            const response = await fetch(`${this.config.baseUrl}${endpoint}`);
            const data = await response.json();

            if (data.success && data.unread_count > 0) {
                this.updateNotificationBadge(data.unread_count);
            }
        } catch (error) {
            console.error('Error checking notifications:', error);
        }
    },

    /**
     * Update notification badge
     */
    updateNotificationBadge: function(count) {
        const badges = document.querySelectorAll('.notification-badge');
        badges.forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        });
    },

    /**
     * Mark notification as read
     */
    markNotificationRead: async function(notificationId) {
        try {
            const endpoint = window.USER_TYPE === 'alumni' 
                ? '/amentorships/markNotificationRead' 
                : '/umentorships/markNotificationRead';
            
            await fetch(`${this.config.baseUrl}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ notification_id: notificationId })
            });
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    },

    /**
     * Format date for display
     */
    formatDate: function(dateString) {
        const date = new Date(dateString);
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        };
        return date.toLocaleDateString('en-US', options);
    },

    // ===========================================
    // UI UTILITIES (Skeleton Loaders, Loading States)
    // ===========================================

    /**
     * Show a loading spinner inside an element
     * @param {HTMLElement|string} element - Element or selector
     * @param {string} size - 'sm', 'md', or 'lg'
     */
    showSpinner: function(element, size = 'md') {
        const el = typeof element === 'string' ? document.querySelector(element) : element;
        if (!el) return;

        const sizeClass = size === 'sm' ? 'loading-spinner--sm' : size === 'lg' ? 'loading-spinner--lg' : '';
        el.innerHTML = `<div class="loading-spinner ${sizeClass}" role="status" aria-label="Loading"></div>`;
    },

    /**
     * Show loading overlay on an element
     * @param {HTMLElement|string} element - Element or selector
     */
    showLoadingOverlay: function(element) {
        const el = typeof element === 'string' ? document.querySelector(element) : element;
        if (!el) return;

        el.classList.add('is-loading');
        const overlay = document.createElement('div');
        overlay.className = 'ms-loading-overlay';
        overlay.setAttribute('role', 'status');
        overlay.setAttribute('aria-label', 'Loading');
        overlay.innerHTML = '<div class="loading-spinner"></div>';
        el.style.position = 'relative';
        el.appendChild(overlay);
    },

    /**
     * Hide loading overlay from an element
     * @param {HTMLElement|string} element - Element or selector
     */
    hideLoadingOverlay: function(element) {
        const el = typeof element === 'string' ? document.querySelector(element) : element;
        if (!el) return;

        el.classList.remove('is-loading');
        const overlay = el.querySelector('.ms-loading-overlay');
        if (overlay) overlay.remove();
    },

    /**
     * Generate skeleton card HTML
     * @param {Object} options - Configuration options
     * @returns {string} HTML string
     */
    skeletonCard: function(options = {}) {
        const { 
            showAvatar = true, 
            showImage = false, 
            lines = 3,
            showButton = false 
        } = options;

        let html = '<div class="ms-skeleton-card">';
        
        if (showImage) {
            html += '<div class="ms-skeleton ms-skeleton-image"></div>';
        }
        
        html += '<div class="ms-skeleton-card-content">';
        
        if (showAvatar) {
            html += `
                <div class="ms-skeleton-card-header">
                    <div class="ms-skeleton ms-skeleton-avatar"></div>
                    <div style="flex: 1;">
                        <div class="ms-skeleton ms-skeleton-text" style="width: 60%;"></div>
                        <div class="ms-skeleton ms-skeleton-text--sm" style="width: 40%;"></div>
                    </div>
                </div>
            `;
        }
        
        html += '<div class="ms-skeleton ms-skeleton-title"></div>';
        
        for (let i = 0; i < lines; i++) {
            const width = i === lines - 1 ? '70%' : '100%';
            html += `<div class="ms-skeleton ms-skeleton-text" style="width: ${width};"></div>`;
        }
        
        if (showButton) {
            html += '<div class="ms-skeleton ms-skeleton-button" style="margin-top: 1rem;"></div>';
        }
        
        html += '</div></div>';
        
        return html;
    },

    /**
     * Generate multiple skeleton cards
     * @param {number} count - Number of cards
     * @param {Object} options - Configuration options
     * @returns {string} HTML string
     */
    skeletonCards: function(count, options = {}) {
        let html = '';
        for (let i = 0; i < count; i++) {
            html += this.skeletonCard(options);
        }
        return html;
    },

    /**
     * Show skeleton cards in a container
     * @param {HTMLElement|string} container - Container element or selector
     * @param {number} count - Number of skeleton cards
     * @param {Object} options - Configuration options
     */
    showSkeletonCards: function(container, count = 3, options = {}) {
        const el = typeof container === 'string' ? document.querySelector(container) : container;
        if (!el) return;

        el.innerHTML = this.skeletonCards(count, options);
    },

    /**
     * Generate skeleton list item HTML
     * @returns {string} HTML string
     */
    skeletonListItem: function() {
        return `
            <div class="ms-skeleton-list-item">
                <div class="ms-skeleton ms-skeleton-avatar"></div>
                <div style="flex: 1;">
                    <div class="ms-skeleton ms-skeleton-text" style="width: 80%;"></div>
                    <div class="ms-skeleton ms-skeleton-text--sm" style="width: 50%;"></div>
                </div>
            </div>
        `;
    },

    /**
     * Show skeleton list in a container
     * @param {HTMLElement|string} container - Container element or selector
     * @param {number} count - Number of items
     */
    showSkeletonList: function(container, count = 5) {
        const el = typeof container === 'string' ? document.querySelector(container) : container;
        if (!el) return;

        let html = '';
        for (let i = 0; i < count; i++) {
            html += this.skeletonListItem();
        }
        el.innerHTML = html;
    },

    /**
     * AJAX fetch with loading state
     * @param {string} url - URL to fetch
     * @param {Object} options - Fetch options
     * @param {HTMLElement|string} loadingElement - Element to show loading on
     * @returns {Promise} Fetch promise
     */
    fetchWithLoading: async function(url, options = {}, loadingElement = null) {
        if (loadingElement) {
            this.showLoadingOverlay(loadingElement);
        }

        try {
            const response = await fetch(url, options);
            return response;
        } finally {
            if (loadingElement) {
                this.hideLoadingOverlay(loadingElement);
            }
        }
    },

    /**
     * Show accessible confirm dialog (enhanced)
     * @param {string} message - Confirmation message
     * @param {Object} options - Options
     * @returns {Promise<boolean>}
     */
    confirm: async function(message, options = {}) {
        const { 
            title = 'Confirm', 
            confirmText = 'Confirm', 
            cancelText = 'Cancel',
            isDanger = false
        } = options;

        return this.showConfirmationModal(title, message, isDanger);
    },

    /**
     * Star rating component helper
     */
    createStarRating: function(containerId, initialRating = 0, onSelect = null) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = '';
        container.className = 'star-rating';
        
        for (let i = 1; i <= 5; i++) {
            const star = document.createElement('span');
            star.className = `star ${i <= initialRating ? 'star-filled' : 'star-empty'}`;
            star.innerHTML = i <= initialRating ? '★' : '☆';
            star.dataset.rating = i;
            
            star.onclick = () => {
                // Update all stars
                container.querySelectorAll('.star').forEach((s, idx) => {
                    const rating = idx + 1;
                    s.className = `star ${rating <= i ? 'star-filled' : 'star-empty'}`;
                    s.innerHTML = rating <= i ? '★' : '☆';
                });
                
                // Store selected rating
                container.dataset.selectedRating = i;
                
                // Callback
                if (onSelect) onSelect(i);
            };
            
            container.appendChild(star);
        }
        
        return container;
    }
};

// CSS styles to be injected
const mentorshipStyles = `
<style>
/* ===========================================
   SKELETON LOADERS & LOADING STATES
   =========================================== */

/* Base skeleton animation */
.ms-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: ms-skeleton-shimmer 1.5s infinite;
    border-radius: 4px;
}

@keyframes ms-skeleton-shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.ms-skeleton-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 1rem;
}

.ms-skeleton-card-content {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.ms-skeleton-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.ms-skeleton-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    flex-shrink: 0;
}

.ms-skeleton-image {
    width: 100%;
    height: 180px;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.ms-skeleton-title {
    height: 24px;
    width: 80%;
}

.ms-skeleton-text {
    height: 16px;
    width: 100%;
}

.ms-skeleton-text--sm {
    height: 12px;
    width: 60%;
    margin-top: 0.25rem;
}

.ms-skeleton-button {
    height: 40px;
    width: 120px;
    border-radius: 8px;
}

.ms-skeleton-list-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #e5e7eb;
}

/* Loading overlay */
.ms-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
    border-radius: inherit;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e5e7eb;
    border-top-color: #7c3aed;
    border-radius: 50%;
    animation: ms-spin 0.8s linear infinite;
}

.loading-spinner--sm {
    width: 24px;
    height: 24px;
    border-width: 2px;
}

.loading-spinner--lg {
    width: 56px;
    height: 56px;
    border-width: 4px;
}

@keyframes ms-spin {
    to { transform: rotate(360deg); }
}

.is-loading {
    pointer-events: none;
}

/* ===========================================
   TOAST NOTIFICATIONS
   =========================================== */

/* Toast notifications */
.ms-toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ms-toast {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideIn 0.3s ease;
    min-width: 280px;
}

.ms-toast-success {
    background: #10b981;
    color: white;
}

.ms-toast-error {
    background: #ef4444;
    color: white;
}

.ms-toast-info {
    background: #3b82f6;
    color: white;
}

.ms-toast-fade {
    animation: fadeOut 0.3s ease forwards;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Modal styles */
.ms-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.ms-modal.show {
    display: flex;
}

.ms-modal-dialog {
    background: white;
    border-radius: 12px;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.ms-modal-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
    color: white;
    border-radius: 12px 12px 0 0;
}

.ms-modal-header.ms-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.ms-modal-title {
    margin: 0;
    font-size: 1.1rem;
}

.ms-close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: white;
    line-height: 1;
}

.ms-modal-body {
    padding: 1.5rem;
}

.ms-modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}

.ms-btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}

.ms-btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

.ms-btn-primary {
    background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
    color: white;
}

.ms-btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

/* Countdown styles */
.countdown-timer {
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.countdown-upcoming {
    color: #6b7280;
}

.countdown-soon {
    color: #f59e0b;
    font-weight: 600;
}

.countdown-active {
    color: #10b981;
    font-weight: 700;
    animation: pulse 1.5s infinite;
}

.countdown-ended {
    color: #9ca3af;
}

/* Session status badges */
.session-status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-upcoming {
    background: #e0e7ff;
    color: #4338ca;
}

.badge-soon {
    background: #fef3c7;
    color: #b45309;
    animation: pulse 1.5s infinite;
}

.badge-active {
    background: #fee2e2;
    color: #dc2626;
    animation: pulse 1s infinite;
}

.badge-ended {
    background: #f3f4f6;
    color: #6b7280;
}

/* Join button states */
.btn-join-active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white !important;
    animation: pulseGlow 2s infinite;
    cursor: pointer !important;
}

.btn-join-disabled {
    background: #e5e7eb !important;
    color: #9ca3af !important;
    cursor: not-allowed !important;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 5px rgba(16, 185, 129, 0.5); }
    50% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.8); }
}

/* Star rating */
.star-rating {
    display: flex;
    gap: 5px;
    font-size: 1.5rem;
    cursor: pointer;
}

.star {
    transition: transform 0.2s;
}

.star:hover {
    transform: scale(1.2);
}

.star-filled {
    color: #fbbf24;
}

.star-empty {
    color: #d1d5db;
}
</style>
`;

// Inject styles when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Inject CSS
    document.head.insertAdjacentHTML('beforeend', mentorshipStyles);
    
    // Initialize the system
    MentorshipSystem.init();

    // Global: close any modal on outside click
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
                this.classList.remove('active');
            }
        });
    });

    // Global: close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.show, .modal.active').forEach(function(modal) {
                modal.classList.remove('show');
                modal.classList.remove('active');
            });
        }
    });
});

// Export for global access
window.MentorshipSystem = MentorshipSystem;
