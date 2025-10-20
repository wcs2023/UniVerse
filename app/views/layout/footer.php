<style>
/* Footer Styles */
.footer {
    background: #1a1d2e;
    color: #ffffff;
    padding: 3rem 0 2rem;
    margin-top: 0;
    border-top: 3px solid #8b5cf6;
}

.footer .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.footer-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.footer-brand {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.25rem;
    width: 100%;
}

.footer-logo {
    height: 60px;
    width: 60px;
    border-radius: 12px;
    background: #8b5cf6;
    padding: 10px;
    object-fit: contain;
}

.footer-brand p {
    color: #a0aec0;
    line-height: 1.6;
    font-size: 1rem;
    margin: 0;
    max-width: 600px;
}

.footer-brand ul {
    list-style: none;
    padding: 0;
    margin: 1rem 0 0 0;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 3rem;
    flex-wrap: wrap;
}

.footer-brand li {
    margin: 0;
}

.footer-brand a {
    color: #cbd5e0;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 400;
    transition: all 0.3s ease;
    position: relative;
    padding-bottom: 2px;
}

.footer-brand a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: #8b5cf6;
    transition: width 0.3s ease;
}

.footer-brand a:hover {
    color: #8b5cf6;
}

.footer-brand a:hover::after {
    width: 100%;
}

.footer-bottom {
    border-top: 1px solid rgba(139, 92, 246, 0.2);
    padding-top: 1.5rem;
    margin-top: 2rem;
    text-align: center;
}

.footer-bottom p {
    color: #718096;
    font-size: 0.875rem;
    margin: 0;
}

/* Responsive Footer Design */
@media (max-width: 768px) {
    .footer {
        padding: 2.5rem 0 1.5rem;
    }

    .footer .container {
        padding: 0 1.5rem;
    }

    .footer-brand ul {
        gap: 2rem;
    }

    .footer-logo {
        height: 50px;
        width: 50px;
    }

    .footer-brand p {
        font-size: 0.95rem;
    }
}

@media (max-width: 480px) {
    .footer {
        padding: 2rem 0 1rem;
    }

    .footer-brand ul {
        flex-direction: column;
        gap: 1rem;
    }

    .footer-logo {
        height: 45px;
        width: 45px;
    }

    .footer-brand p {
        font-size: 0.9rem;
    }

    .footer-brand a {
        font-size: 0.9rem;
    }
}
</style>

<!-- Footer -->
<footer class="footer" id="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <img src="<?= BASE_URL ?>/assets/images/U.png" alt="UniVerse Logo" class="footer-logo">
                <p>Bridging the gap between students and industry expectations in Sri Lanka.</p>
                <ul>
                    <li><a href="<?= BASE_URL ?>/privacypolicy">Privacy Policy</a></li>
                    <li><a href="<?= BASE_URL ?>/contact">Contact</a></li>
                    <li><a href="<?= BASE_URL ?>/termsofservice">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> UniVerse. All rights reserved.</p>
        </div>
    </div>
</footer>

