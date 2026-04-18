/**
 * Mentorship core logic: config, countdown lifecycle, time calculations.
 */
(function () {
    'use strict';

    const MentorshipSystem = {
        config: {
            joinWindowMinutes: 15,
            sessionEndWindowMinutes: 30,
            countdownUpdateInterval: 1000,
            baseUrl: window.BASE_URL || ''
        },

        countdownIntervals: {},

        init: function () {
            this.initCountdowns();
            this.initNotificationPolling();
        },

        initCountdowns: function () {
            const sessionCards = document.querySelectorAll('[data-session-datetime]');

            sessionCards.forEach((card) => {
                const sessionId = card.dataset.sessionId;
                const sessionDatetime = card.dataset.sessionDatetime;
                const meetingLink = card.dataset.meetingLink;

                if (sessionId && sessionDatetime) {
                    this.startCountdown(sessionId, sessionDatetime, meetingLink, card);
                }
            });
        },

        startCountdown: function (sessionId, sessionDatetime, meetingLink, cardElement) {
            const countdownElement = cardElement.querySelector('.countdown-timer, .countdown, .ms-countdown');
            const joinButton = cardElement.querySelector('.join-meeting-btn');
            const statusBadge = cardElement.querySelector('.session-status-badge, .ms-badge');

            if (this.countdownIntervals[sessionId]) {
                clearInterval(this.countdownIntervals[sessionId]);
            }

            const updateCountdown = () => {
                const status = this.calculateTimeStatus(sessionDatetime);

                if (countdownElement) {
                    countdownElement.innerHTML = this.formatCountdownDisplay(status);
                }

                if (statusBadge) {
                    this.updateStatusBadge(statusBadge, status);
                }

                if (joinButton) {
                    this.updateJoinButton(joinButton, status, meetingLink);
                }

                if (status.hasEnded) {
                    clearInterval(this.countdownIntervals[sessionId]);
                    delete this.countdownIntervals[sessionId];
                }
            };

            updateCountdown();
            this.countdownIntervals[sessionId] = setInterval(updateCountdown, this.config.countdownUpdateInterval);
        },

        calculateTimeStatus: function (sessionDatetime) {
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

        formatCountdownDisplay: function (status) {
            if (status.hasEnded) {
                return '<span class="countdown-ended">Session Ended</span>';
            }

            if (status.isActive) {
                return '<span class="countdown-active">Session In Progress</span>';
            }

            if (status.isStartingSoon) {
                return `<span class="countdown-soon">Starting in: <strong>${status.minutes}m ${status.seconds}s</strong></span>`;
            }

            let timeString = '';
            if (status.days > 0) {
                timeString = `${status.days}d ${status.hours}h ${status.minutes}m`;
            } else if (status.hours > 0) {
                timeString = `${status.hours}h ${status.minutes}m ${status.seconds}s`;
            } else {
                timeString = `${status.minutes}m ${status.seconds}s`;
            }

            return `<span class="countdown-upcoming">Starts in: <strong>${timeString}</strong></span>`;
        },

        updateStatusBadge: function (badgeElement, status) {
            badgeElement.classList.remove('badge-upcoming', 'badge-soon', 'badge-active', 'badge-ended', 'ms-badge-upcoming', 'ms-badge-soon', 'ms-badge-active');

            if (status.hasEnded) {
                badgeElement.className = badgeElement.className.includes('ms-badge') ? 'ms-badge badge-ended' : 'session-status-badge badge-ended';
                badgeElement.textContent = 'Ended';
            } else if (status.isActive) {
                badgeElement.className = badgeElement.className.includes('ms-badge') ? 'ms-badge ms-badge-active' : 'session-status-badge badge-active';
                badgeElement.textContent = 'LIVE';
            } else if (status.isStartingSoon) {
                badgeElement.className = badgeElement.className.includes('ms-badge') ? 'ms-badge ms-badge-soon' : 'session-status-badge badge-soon';
                badgeElement.textContent = 'Starting Soon';
            } else {
                badgeElement.className = badgeElement.className.includes('ms-badge') ? 'ms-badge ms-badge-upcoming' : 'session-status-badge badge-upcoming';
                badgeElement.textContent = 'Upcoming';
            }
        },

        updateJoinButton: function (buttonElement, status, meetingLink) {
            if (status.canJoin && meetingLink) {
                buttonElement.disabled = false;
                buttonElement.classList.add('btn-join-active');
                buttonElement.classList.remove('btn-join-disabled');
                buttonElement.innerHTML = 'Join Meeting Now';
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
                buttonElement.innerHTML = 'Wait for Session Time';
                buttonElement.onclick = null;
            }
        },

        joinMeeting: function (meetingLink) {
            if (meetingLink) {
                window.open(meetingLink, '_blank', 'noopener,noreferrer');
            } else {
                this.showNotification('Meeting link not available', 'error');
            }
        },

        formatDate: function (dateString) {
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
        }
    };

    window.MentorshipSystem = MentorshipSystem;
})();
