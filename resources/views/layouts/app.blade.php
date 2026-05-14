<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Saathi - Wellness Community</title>
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

    @auth
        {{-- ── Saathi AI Companion Widget ── --}}
        <div id="saathi-chat-widget" class="d-none d-md-block">
            <button id="saathi-chat-toggle" class="saathi-bubble ai-glow">
                <i class="bi bi-robot"></i>
                <span class="bubble-ping"></span>
            </button>

            <div id="saathi-chat-panel" class="saathi-panel d-none">
                <div class="saathi-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-robot text-brand"></i>
                        <div>
                            <div class="fw-700 small">Saathi AI Companion</div>
                            <div class="x-small text-muted">Online & ready to help</div>
                        </div>
                    </div>
                    <button id="saathi-panel-close" class="btn-close btn-close-white ms-auto" style="font-size:0.7rem"></button>
                </div>

                <div class="saathi-persona-strip">
                    <select id="saathi-persona-select" class="form-select form-select-sm">
                        <option value="mitra">Mitra (Empathetic Friend)</option>
                        <option value="yogi">Yogi (Zen Master)</option>
                        <option value="shakti">Shakti (Fitness Pro)</option>
                    </select>
                </div>

                <div id="saathi-chat-body" class="saathi-body">
                    <div class="ai-msg">
                        Hello {{ Auth::user()->name }}! I'm your Saathi companion. How are you feeling today?
                    </div>
                </div>

                <div class="saathi-footer">
                    <div id="saathi-voice-status" class="x-small text-brand mb-1 d-none">
                        <span class="loading-spinner" style="width:10px; height:10px;"></span> Listening...
                    </div>
                    <div class="input-group">
                        <input type="text" id="saathi-chat-input" class="form-control form-control-sm" placeholder="Ask Saathi anything...">
                        <button id="saathi-mic-btn" class="btn btn-outline-brand btn-sm" title="Speak">
                            <i class="bi bi-mic"></i>
                        </button>
                        <button id="saathi-send-btn" class="btn btn-brand btn-sm">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .saathi-bubble {
                position: fixed; bottom: 30px; right: 30px;
                width: 60px; height: 60px;
                border-radius: 50%;
                background: var(--gradient-brand);
                color: white; border: none;
                font-size: 1.8rem;
                display: flex; align-items: center; justify-content: center;
                z-index: 1050; transition: var(--transition);
                box-shadow: 0 8px 32px rgba(45,170,111,0.4);
            }
            .saathi-bubble:hover { transform: scale(1.1) rotate(5deg); }
            .bubble-ping {
                position: absolute; top: 0; right: 0;
                width: 15px; height: 15px;
                background: #ffcf00; border: 3px solid var(--bg-dark);
                border-radius: 50%; animation: pulsePing 2s infinite;
            }
            @keyframes pulsePing {
                0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 207, 0, 0.7); }
                70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 207, 0, 0); }
                100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 207, 0, 0); }
            }

            .saathi-panel {
                position: fixed; bottom: 100px; right: 30px;
                width: 350px; height: 500px;
                background: var(--bg-card);
                border: 1px solid var(--border-color);
                border-radius: 20px;
                display: flex; flex-direction: column;
                z-index: 1051; overflow: hidden;
                box-shadow: 0 15px 50px rgba(0,0,0,0.5);
                backdrop-filter: blur(20px);
                animation: slideUpIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            @keyframes slideUpIn {
                from { opacity: 0; transform: translateY(20px) scale(0.95); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }

            .saathi-header { background: var(--bg-surface); padding: 15px; display: flex; align-items: center; border-bottom: 1px solid var(--border-color); }
            .saathi-persona-strip { padding: 8px 15px; background: rgba(255,255,255,0.03); border-bottom: 1px solid var(--border-color); }
            .saathi-body { flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; scroll-behavior: smooth; }
            
            .ai-msg, .user-msg {
                max-width: 85%; padding: 10px 14px; border-radius: 15px; font-size: 0.85rem; line-height: 1.5;
                position: relative;
            }
            .ai-msg { background: var(--bg-surface); color: var(--text-primary); align-self: flex-start; border-bottom-left-radius: 4px; border: 1px solid var(--border-color); }
            .user-msg { background: var(--brand-green); color: white; align-self: flex-end; border-bottom-right-radius: 4px; }
            
            .saathi-footer { padding: 15px; background: var(--bg-surface); border-top: 1px solid var(--border-color); }
            .x-small { font-size: 0.7rem; }
            .btn-brand { background: var(--brand-green); color: white; border: none; }
            .btn-brand:hover { background: var(--brand-green-d); color: white; }
            .btn-outline-brand { border: 1px solid var(--brand-green); color: var(--brand-green); }
            .btn-outline-brand:hover { background: var(--brand-green); color: white; }

            .speak-btn { position: absolute; right: -30px; bottom: 0; color: var(--brand-green); cursor: pointer; opacity: 0; transition: 0.2s; }
            .ai-msg:hover .speak-btn { opacity: 1; }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chatToggle = document.getElementById('saathi-chat-toggle');
                const chatPanel = document.getElementById('saathi-chat-panel');
                const chatClose = document.getElementById('saathi-panel-close');
                const chatBody = document.getElementById('saathi-chat-body');
                const chatInput = document.getElementById('saathi-chat-input');
                const sendBtn = document.getElementById('saathi-send-btn');
                const personaSelect = document.getElementById('saathi-persona-select');
                const micBtn = document.getElementById('saathi-mic-btn');
                const voiceStatus = document.getElementById('saathi-voice-status');

                // Toggle Panel
                chatToggle.addEventListener('click', () => {
                    chatPanel.classList.toggle('d-none');
                    if (!chatPanel.classList.contains('d-none')) {
                        loadHistory();
                        chatInput.focus();
                    }
                });
                chatClose.addEventListener('click', () => chatPanel.classList.add('d-none'));

                // Send Message
                async function sendMessage() {
                    const msg = chatInput.value.trim();
                    if (!msg) return;

                    appendMessage('user', msg);
                    chatInput.value = '';
                    
                    const loadingMsg = appendMessage('ai', '<span class="loading-spinner"></span> Saathi is thinking...');

                    try {
                        const response = await fetch('{{ route("ai.chat.send") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ message: msg, persona: personaSelect.value })
                        });
                        const data = await response.json();
                        
                        chatBody.removeChild(loadingMsg);
                        if (data.reply) {
                            appendMessage('ai', data.reply);
                            // Auto-speak if it's a short reply (Optional UX)
                            // speakText(data.reply);
                        }
                    } catch (e) {
                        chatBody.removeChild(loadingMsg);
                        appendMessage('ai', 'Sorry, I am having trouble connecting right now.');
                    }
                }

                sendBtn.addEventListener('click', sendMessage);
                chatInput.addEventListener('keypress', (e) => { if(e.key === 'Enter') sendMessage(); });

                function appendMessage(role, content) {
                    const div = document.createElement('div');
                    div.className = role === 'user' ? 'user-msg' : 'ai-msg';
                    div.innerHTML = content;
                    
                    if (role === 'ai') {
                        const speaker = document.createElement('i');
                        speaker.className = 'bi bi-volume-up-fill speak-btn';
                        speaker.onclick = () => speakText(content);
                        div.appendChild(speaker);
                    }

                    chatBody.appendChild(div);
                    chatBody.scrollTop = chatBody.scrollHeight;
                    return div;
                }

                async function loadHistory() {
                    if (chatBody.children.length > 1) return; // Already loaded
                    const res = await fetch('{{ route("ai.chat.history") }}');
                    const history = await res.json();
                    chatBody.innerHTML = '';
                    history.forEach(m => appendMessage(m.role, m.content));
                }

                // Text to Speech
                function speakText(text) {
                    if (!window.speechSynthesis) return;
                    window.speechSynthesis.cancel();
                    const utterance = new SpeechSynthesisUtterance(text.replace(/<[^>]*>?/gm, ''));
                    utterance.rate = 1;
                    utterance.pitch = 1;
                    window.speechSynthesis.speak(utterance);
                }

                // Voice to Text
                if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    const recognition = new SpeechRecognition();
                    recognition.lang = 'en-US';

                    micBtn.addEventListener('click', () => {
                        recognition.start();
                        micBtn.classList.add('btn-danger');
                        voiceStatus.classList.remove('d-none');
                    });

                    recognition.onresult = (event) => {
                        const transcript = event.results[0][0].transcript;
                        chatInput.value = transcript;
                        sendMessage();
                    };

                    recognition.onend = () => {
                        micBtn.classList.remove('btn-danger');
                        voiceStatus.classList.add('d-none');
                    };
                } else {
                    micBtn.classList.add('d-none');
                }
            });
        </script>
    @endauth

    @stack('scripts')
</body>

</html>