<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Saathi Wellness Community') | Saathi</title>
    <meta name="description"
        content="@yield('meta_description', 'A supportive online community focused on health, wellness, fitness, and mental well-being.')">

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Custom Styles --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')

    {{-- Prevent theme flash --}}
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'dark';
            if (theme === 'light') {
                document.documentElement.classList.add('light-mode-active'); // temporary class
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.classList.add('light-mode');
                });
            }
        })();
    </script>
</head>

<body>

    {{-- ═══════════════ NAVBAR ═══════════════ --}}
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            {{-- Brand --}}
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('posts.index') }}">
                <span class="brand-icon"><i class="bi bi-flower1"></i></span>
                <span class="brand-text">Saathi</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                {{-- Center links --}}
                <ul class="navbar-nav mx-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}"
                            href="{{ route('posts.index') }}">
                            <i class="bi bi-grid-fill me-1"></i>Community
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('resources.*') ? 'active' : '' }}"
                            href="{{ route('resources.index') }}">
                            <i class="bi bi-heart-pulse-fill me-1"></i>Wellness Hub
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bookmarks.*') ? 'active' : '' }}"
                                href="{{ route('bookmarks.index') }}">
                                <i class="bi bi-bookmark-heart-fill me-1"></i>Saved
                            </a>
                        </li>
                    @endauth
                </ul>

                {{-- Right section --}}
                <div class="d-flex align-items-center gap-3">
                    {{-- Theme Toggle --}}
                    <button id="themeToggle" class="theme-toggle" title="Toggle Light/Dark Mode">
                        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    </button>

                    <div class="d-flex align-items-center gap-2">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Sign In</a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Join Free</a>
                        @else
                            <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-lg me-1"></i>New Post
                            </a>
                            {{-- User dropdown --}}
                            <div class="dropdown">
                                <button class="btn btn-link nav-avatar p-0 border-0" data-bs-toggle="dropdown"
                                    style="border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ Auth::user()->profile_photo }}" class="avatar-sm"
                                            style="object-fit: cover; border: 2px solid var(--bg-card);">
                                    @else
                                        <div class="avatar-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                                    @endif
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                    <li class="px-3 py-2">
                                        <div class="fw-600 text-dark">{{ Auth::user()->name }}</div>
                                        <small class="text-muted">{{ Auth::user()->email }}</small>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show', Auth::user()->_id) }}">
                                            <i class="bi bi-person-circle me-2"></i>My Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="bi bi-gear me-2"></i>Settings
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
    </nav>

    {{-- ═══════════════ FLASH MESSAGES ═══════════════ --}}
    @if(session('success'))
        <div class="alert-banner alert-success-banner animate-slide-down">
            <div class="container d-flex align-items-center justify-content-between">
                <span><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="alert-banner alert-error-banner animate-slide-down">
            <div class="container d-flex align-items-center justify-content-between">
                <span>
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    @if(session('error'))
                        {{ session('error') }}
                    @else
                        Please fix the errors below.
                    @endif
                </span>
                <button type="button" class="btn-close btn-close-white btn-sm"></button>
            </div>
        </div>
    @endif

    {{-- ═══════════════ MAIN CONTENT ═══════════════ --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- ═══════════════ FOOTER ═══════════════ --}}
    @if(!request()->routeIs('bookmarks.index', 'resources.*', 'dashboard'))
    <footer class="site-footer mt-auto">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="text-brand" style="font-size:1.5rem"><i class="bi bi-flower1"></i></span>
                        <span class="fw-700 text-white" style="font-size:1.2rem">Saathi</span>
                    </div>
                    <p class="text-muted small">A community built on compassion, support, and the shared journey toward
                        health and wellness.</p>
                </div>
                <div class="col-md-2">
                    <h6 class="footer-heading">Community</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('posts.index') }}">All Posts</a></li>
                        <li><a href="{{ route('posts.index') }}?category=fitness">Fitness</a></li>
                        <li><a href="{{ route('posts.index') }}?category=mental-health">Mental Health</a></li>
                        <li><a href="{{ route('posts.index') }}?category=nutrition">Nutrition</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="footer-heading">Resources</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('resources.health-tips') }}">Health Tips</a></li>
                        <li><a href="{{ route('resources.meditation') }}">Meditation</a></li>
                        <li><a href="{{ route('resources.fitness') }}">Fitness Guide</a></li>
                        <li><a href="{{ route('resources.nutrition') }}">Nutrition</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="footer-heading">Daily Wellness Tip</h6>
                    <div class="wellness-tip-card">
                        <p class="mb-0 small">"Take care of your body. It's the only place you have to live." — Jim Rohn
                        </p>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <p class="text-muted small mb-0">© {{ date('Y') }} Saathi Wellness Community.</p>
                <div class="d-flex gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="footer-link">Join the Community</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a>
                        <a href="{{ route('profile.show', Auth::user()->_id) }}" class="footer-link">My Profile</a>
                    @endguest
                </div>
            </div>
        </div>
    </footer>
    @endif

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Global JS: auto-dismiss flash banners --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Flash banners auto-dismiss
            const banners = document.querySelectorAll('.alert-banner');
            banners.forEach(b => {
                setTimeout(() => {
                    b.style.opacity = '0';
                    b.style.transform = 'translateY(-20px)';
                    setTimeout(() => b.remove(), 400);
                }, 4000);
                const closeBtn = b.querySelector('.btn-close');
                if (closeBtn) closeBtn.addEventListener('click', () => b.remove());
            });

            // Theme Toggle Logic
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const body = document.body;

            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'dark';
            if (savedTheme === 'light') {
                body.classList.add('light-mode');
                themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
            }

            themeToggle.addEventListener('click', () => {
                body.classList.toggle('light-mode');
                const isLight = body.classList.contains('light-mode');

                if (isLight) {
                    themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
                    localStorage.setItem('theme', 'light');
                } else {
                    themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
                    localStorage.setItem('theme', 'dark');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>