<!DOCTYPE html>
<!-- resources/views/layouts/app.blade.php -->
<html lang="nl" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EventHub - Jouw platform voor evenementenbeheer en ticketverkoop">
    <meta name="keywords" content="evenementen, tickets, events, planning, beheer">
    <title>@yield('title', 'EventHub') - Evenementen Platform</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --success-color: #4bb543;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        /* Header & Navigation */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.15);
            padding: 1rem 0;
            transition: var(--transition);
        }

        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            font-size: 2rem;
            color: var(--accent-color);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .nav-link:hover,
        .nav-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .navbar-toggler {
            border: none;
            color: white;
            font-size: 1.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            padding: 0.5rem 0;
            animation: fadeIn 0.3s ease;
        }

        .dropdown-item {
            padding: 0.7rem 1.5rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dropdown-item:hover {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }

        /* Search Form */
        .search-form {
            position: relative;
            max-width: 400px;
        }

        .search-form input {
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transition: var(--transition);
        }

        .search-form input:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.3);
            color: white;
        }

        .search-form input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-form button {
            position: absolute;
            right: 5px;
            top: 5px;
            bottom: 5px;
            border-radius: 50%;
            width: 40px;
            background: var(--accent-color);
            border: none;
            color: white;
            transition: var(--transition);
        }

        .search-form button:hover {
            background: #3db8d8;
            transform: rotate(10deg);
        }

        /* Main Content */
        main {
            flex: 1;
            padding-top: 80px;
            padding-bottom: 2rem;
        }

        .container {
            max-width: 1200px;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 1.25rem 1.5rem;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Alerts */
        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid var(--danger-color);
        }

        /* Event Cards */
        .event-card {
            height: 100%;
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: var(--transition);
            position: relative;
        }

        .event-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            z-index: 1;
        }

        .event-card-img {
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .event-card:hover .event-card-img {
            transform: scale(1.05);
        }

        .event-card-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            z-index: 2;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: white;
            margin-top: auto;
        }

        .footer-content {
            padding: 3rem 0;
        }

        .footer-logo {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .footer-links h5 {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: var(--accent-color);
            transform: translateX(5px);
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 1.5rem;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .social-icon:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem 0;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }

            .nav-link {
                padding: 0.5rem 0 !important;
                margin: 0.2rem 0;
            }

            .search-form {
                margin: 1rem 0;
                max-width: 100%;
            }

            .footer-links {
                margin-bottom: 2rem;
            }

            .social-icons {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .btn {
                width: 100%;
                justify-content: center;
            }

            .card-header h4 {
                font-size: 1.2rem;
            }
        }

        /* Utility Classes */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .shadow-soft {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        /* Loading Spinner */
        .spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(67, 97, 238, 0.1);
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* =========================
   FIX: Trillende Bootstrap modal
   ========================= */

        /* Niets achter de modal mag reageren */
        body.modal-open main,
        body.modal-open nav,
        body.modal-open footer {
            pointer-events: none;
        }

        /* Alleen de modal zelf is interactief */
        body.modal-open .modal,
        body.modal-open .modal * {
            pointer-events: auto;
        }

        /* Schakel ALLE hover/transform/transition uit in modals */
        body.modal-open .modal *,
        body.modal-open .modal-dialog,
        body.modal-open .modal-content {
            transform: none !important;
            transition: none !important;
        }


    </style>

    @stack('styles')
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-calendar-event"></i>
            EventHub
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}"
                       href="{{ route('events.index') }}">
                        <i class="bi bi-calendar3"></i> Evenementen
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.calendar') ? 'active' : '' }}"
                       href="{{ route('events.calendar') }}">
                        <i class="bi bi-calendar-week"></i> Kalender
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('events.create') ? 'active' : '' }}"
                           href="{{ route('events.create') }}">
                            <i class="bi bi-plus-circle"></i> Nieuw Evenement
                        </a>
                    </li>
                @endauth
            </ul>

            <!-- Search Form -->
            <form class="search-form me-3" action="{{ route('events.search') }}" method="GET">
                <input class="form-control" type="search" name="q"
                       placeholder="Zoek evenementen..."
                       value="{{ request('q') }}"
                       aria-label="Zoek evenementen">
                <button class="btn" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <!-- Auth Links -->
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light btn-sm mx-1" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Inloggen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light btn-sm mx-1" href="{{ route('register') }}">
                            <i class="bi bi-person-plus"></i> Registreren
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                           role="button" data-bs-toggle="dropdown">
                            <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                 style="width: 32px; height: 32px; border-radius: 50%;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="bi bi-person-circle"></i> Mijn Profiel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('bookings.index') }}">
                                    <i class="bi bi-ticket-perforated"></i> Mijn Tickets
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Uitloggen
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="fade-in">
    <div class="container">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </div>
</main>

<!-- Footer -->
<footer class="mt-auto">
    <div class="footer-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="footer-logo">
                        <i class="bi bi-calendar-event"></i>
                        EventHub
                    </div>
                    <p class="opacity-75">
                        Het ultieme platform voor evenementenbeheer, ticketverkoop en community building.
                        Maak, beheer en ontdek de beste evenementen.
                    </p>
                    <div class="social-icons">
                        <a href="#" class="social-icon">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Platform</h5>
                        <ul>
                            <li><a href="{{ route('events.index') }}"><i class="bi bi-chevron-right"></i> Evenementen</a></li>
                            <li><a href="{{ route('events.calendar') }}"><i class="bi bi-chevron-right"></i> Kalender</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Over EventHub</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Contact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Voor Organisatoren</h5>
                        <ul>
                            <li><a href="{{ route('events.create') }}"><i class="bi bi-chevron-right"></i> Evenement Aanmaken</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Prijzen</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Handleiding</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> FAQ</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Legal</h5>
                        <ul>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Algemene Voorwaarden</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Privacybeleid</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Cookiebeleid</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Disclaimer</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4">
                    <div class="footer-links">
                        <h5>Contact</h5>
                        <ul>
                            <li><a href="#"><i class="bi bi-envelope"></i> info@eventhub.nl</a></li>
                            <li><a href="#"><i class="bi bi-telephone"></i> +31 20 123 4567</a></li>
                            <li><a href="#"><i class="bi bi-geo-alt"></i> Amsterdam, NL</a></li>
                            <li><a href="#"><i class="bi bi-clock"></i> 9:00 - 17:00</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-md-start text-center mb-2 mb-md-0">
                    &copy; {{ date('Y') }} EventHub. Alle rechten voorbehouden.
                </div>
                <div class="col-md-6 text-md-end text-center">
                    Ontwikkeld met <i class="bi bi-heart-fill text-danger"></i> voor de evenementen community
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Add active class to current page in navbar
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    });

    // Form validation enhancement
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verwerken...';
                submitBtn.disabled = true;
            }
        });
    });

    // Back to top button
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
    backToTopBtn.className = 'btn btn-primary back-to-top';
    backToTopBtn.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        `;
    document.body.appendChild(backToTopBtn);

    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopBtn.style.display = 'flex';
            backToTopBtn.style.alignItems = 'center';
            backToTopBtn.style.justifyContent = 'center';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });

    // Dark/Light mode toggle (optional)
    const themeToggle = document.createElement('button');
    themeToggle.innerHTML = '<i class="bi bi-moon"></i>';
    themeToggle.className = 'btn btn-outline-light theme-toggle';
    themeToggle.style.cssText = `
            position: fixed;
            bottom: 90px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            z-index: 10;
        `;
    document.body.appendChild(themeToggle);

    themeToggle.addEventListener('click', () => {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', newTheme);
        themeToggle.innerHTML = newTheme === 'dark' ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon"></i>';
        localStorage.setItem('theme', newTheme);
    });

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    themeToggle.innerHTML = savedTheme === 'dark' ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon"></i>';
</script>

@stack('scripts')
</body>
</html>
