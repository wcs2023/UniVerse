/**
 * Mentorship shared styles and page bootstrapping.
 */
(function () {
    'use strict';

    if (!window.MentorshipSystem) {
        return;
    }

    const mentorshipStyles = `
<style>
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

    document.addEventListener('DOMContentLoaded', function () {
        document.head.insertAdjacentHTML('beforeend', mentorshipStyles);

        window.MentorshipSystem.init();

        document.querySelectorAll('.modal, .ms-modal').forEach(function (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === this) {
                    this.classList.remove('show');
                    this.classList.remove('active');
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal.show, .modal.active, .ms-modal.show, .ms-modal.active').forEach(function (modal) {
                    modal.classList.remove('show');
                    modal.classList.remove('active');
                });
            }
        });
    });
})();
