<?php 
// Check if URLROOT is defined, if not define it
if (!defined('URLROOT')) {
    define('URLROOT', 'http://localhost/UniVerse/public');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        :root {
            --primary-purple: #7c3aed;
            --purple-hover: #6d28d9;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
        }

        .top-nav {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            margin-bottom: 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-nav .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-nav .nav-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-purple);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.3s ease;
        }

        .top-nav .nav-brand:hover {
            transform: scale(1.05);
        }

        .top-nav .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .top-nav .nav-links li {
            margin: 0;
        }

        .top-nav .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            padding: 0.5rem 0;
            position: relative;
        }

        .top-nav .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-purple);
            transition: width 0.3s ease;
        }

        .top-nav .nav-links a:hover::after,
        .top-nav .nav-links a.active::after {
            width: 100%;
        }

        .top-nav .nav-links a:hover,
        .top-nav .nav-links a.active {
            color: var(--primary-purple);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            object-fit: cover;
            border: 2px solid transparent;
            transition: border-color 0.3s;
        }

        .user-avatar:hover {
            border-color: var(--primary-purple);
        }

        .notification-icon {
            font-size: 1.5rem;
            color: var(--text-dark);
            cursor: pointer;
            position: relative;
            transition: transform 0.3s;
        }

        .notification-icon:hover {
            transform: scale(1.1);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .icon {
            display: inline-block;
            text-align: center;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-dark);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .top-nav .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 1rem 0;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                gap: 0;
            }

            .top-nav .nav-links.show {
                display: flex;
            }

            .top-nav .nav-links li {
                width: 100%;
                text-align: center;
                padding: 0.5rem 0;
            }

            .top-nav .nav-links a::after {
                display: none;
            }

            .top-nav .container-fluid {
                padding: 0 1rem;
                position: relative;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="top-nav">
        <div class="container-fluid">
            <a href="<?= URLROOT ?>" class="nav-brand">
                <span class="icon">🎓</span>
                <span>UniVerse</span>
            </a>
            
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <span>☰</span>
            </button>

            <ul class="nav-links" id="navLinks">
                <li><a href="<?= URLROOT ?>/uhome">Home</a></li>
                <li><a href="<?= URLROOT ?>/mentorships/exploreMentors">Explore Mentors</a></li>
                <li><a href="<?= URLROOT ?>/mentorships">My Mentorships</a></li>
                <li><a href="<?= URLROOT ?>/umyprofile">My Profile</a></li>
                <li><a href="<?= URLROOT ?>/resources">Resources</a></li>
            </ul>
            
            <div class="user-menu">
                <div class="notification-icon">
                    <span>🔔</span>
                    <span class="notification-badge">3</span>
                </div>
                <img src="https://i.pravatar.cc/150?img=1" alt="User" class="user-avatar" title="Profile">
            </div>
        </div>
    </nav>

    <script>
        // Set active link based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-links a');
            
            navLinks.forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (currentPath.includes(linkPath) && linkPath !== '<?= URLROOT ?>') {
                    link.classList.add('active');
                } else if (currentPath === '<?= URLROOT ?>' && linkPath === '<?= URLROOT ?>') {
                    link.classList.add('active');
                }
            });
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('show');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navLinks = document.getElementById('navLinks');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (!event.target.closest('.top-nav')) {
                navLinks.classList.remove('show');
            }
        });
    </script>
</body>
</html>
