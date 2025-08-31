<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="/UniVerse/public/assets/images/logo.png" alt="UniVerse Logo" class="logo-img">
            </div>
            
            <ul class="footer-links">
                <li><a href="#">Blog Posts</a></li>
                <li><a href="#">Career Tips</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
            
            <div class="social-icons">
                <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>© 2023 UniVerse. All rights reserved</p>
            <ul class="footer-bottom-links">
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Use</a></li>
                <li><a href="#">Cookie Settings</a></li>
            </ul>
        </div>
    </div>
</footer>
<!-- Font Awesome for social icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Footer */
    .footer {
        background: var(--white);
        padding: 3rem 0 2rem 0;
        border-top: 1px solid var(--border-color);
    }
    
    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .footer-logo {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-purple);
        text-decoration: none;
    }
    
    .logo-img {
        max-height: 40px;
        width: auto;
    }
    
    .footer-links {
        display: flex;
        list-style: none;
        gap: 2rem;
    }
    
    .footer-links a {
        color: var(--text-light);
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }
    
    .footer-links a:hover {
        color: var(--primary-purple);
    }
    
    .social-icons {
        display: flex;
        gap: 1rem;
    }
    
    .social-icons a {
        width: 32px;
        height: 32px;
        background: var(--text-light);
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: background 0.3s ease;
    }
    
    .social-icons a:hover {
        background: var(--primary-purple);
    }
    
    .footer-bottom {
        text-align: center;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }
    
    .footer-bottom p {
        color: var(--text-light);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .footer-bottom-links {
        display: flex;
        justify-content: center;
        gap: 2rem;
        list-style: none;
    }
    
    .footer-bottom-links a {
        color: var(--text-light);
        text-decoration: none;
        font-size: 0.85rem;
    }
    
    .footer-bottom-links a:hover {
        color: var(--primary-purple);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            gap: 2rem;
            text-align: center;
        }
        
        .footer-links {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .footer-bottom-links {
            flex-wrap: wrap;
            gap: 1rem;
        }
    }
</style>
