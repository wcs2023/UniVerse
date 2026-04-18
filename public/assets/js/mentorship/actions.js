/**
 * Mentorship async actions: bookings, feedback, notifications.
 */
(function () {
    'use strict';

    if (!window.MentorshipSystem) {
        return;
    }

    Object.assign(window.MentorshipSystem, {
        confirmTimeSlot: async function (requestId, slotId, buttonElement) {
            if (!requestId || !slotId) {
                this.showNotification('Missing information', 'error');
                return;
            }

            const confirmed = await this.showConfirmationModal(
                'Confirm Time Slot',
                'Are you sure you want to confirm this time slot? Once confirmed, the session will be locked.'
            );

            if (!confirmed) {
                return;
            }

            if (buttonElement) {
                buttonElement.disabled = true;
                buttonElement.textContent = 'Confirming...';
            }

            try {
                const response = await fetch(`${this.config.baseUrl}/umentorships/confirmTimeSlot`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ request_id: requestId, slot_id: slotId })
                });

                const data = await response.json();

                if (data.success) {
                    this.showNotification('Session confirmed successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = `${this.config.baseUrl}/umentorships?success=scheduled`;
                    }, 1500);
                } else {
                    this.showNotification(data.message || 'Failed to confirm session', 'error');
                    if (buttonElement) {
                        buttonElement.disabled = false;
                        buttonElement.textContent = 'Confirm This Time';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                this.showNotification('An error occurred. Please try again.', 'error');
                if (buttonElement) {
                    buttonElement.disabled = false;
                    buttonElement.textContent = 'Confirm This Time';
                }
            }
        },

        submitFeedback: async function (sessionId, rating, reviewText) {
            if (!sessionId || !rating) {
                this.showNotification('Please provide a rating', 'error');
                return false;
            }

            try {
                const response = await fetch(`${this.config.baseUrl}/umentorships/submitFeedback`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        booking_id: sessionId,
                        rating: rating,
                        review_text: reviewText || ''
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.showNotification('Thank you for your feedback!', 'success');
                    return true;
                }

                this.showNotification(data.message || 'Failed to submit feedback', 'error');
                return false;
            } catch (error) {
                console.error('Error:', error);
                this.showNotification('An error occurred. Please try again.', 'error');
                return false;
            }
        },

        cancelSession: async function (sessionId, reason = '') {
            const confirmed = await this.showConfirmationModal(
                'Cancel Session',
                'Are you sure you want to cancel this session? This action cannot be undone.',
                true
            );

            if (!confirmed) {
                return false;
            }

            try {
                const response = await fetch(`${this.config.baseUrl}/umentorships/cancelBooking`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ booking_id: sessionId, reason: reason })
                });

                const data = await response.json();

                if (data.success) {
                    this.showNotification('Session cancelled', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                    return true;
                }

                this.showNotification(data.message || 'Failed to cancel session', 'error');
                return false;
            } catch (error) {
                console.error('Error:', error);
                this.showNotification('An error occurred. Please try again.', 'error');
                return false;
            }
        },

        initNotificationPolling: function () {
            setInterval(() => this.checkNotifications(), 60000);
        },

        checkNotifications: async function () {
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

        updateNotificationBadge: function (count) {
            const badges = document.querySelectorAll('.notification-badge');
            badges.forEach((badge) => {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            });
        },

        markNotificationRead: async function (notificationId) {
            try {
                const endpoint = window.USER_TYPE === 'alumni'
                    ? '/amentorships/markNotificationRead'
                    : '/umentorships/markNotificationRead';

                await fetch(`${this.config.baseUrl}${endpoint}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ notification_id: notificationId })
                });
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }
    });
})();
