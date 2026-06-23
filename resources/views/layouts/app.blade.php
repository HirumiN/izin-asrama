<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="E-Izin">
    <title>@yield('title', 'E-Izin Asrama') - Sistem Izin Asrama</title>

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f1f5f9;
            min-height: 100vh;
            color: #1e293b;
        }
        
        /* Premium Light Glassmorphism card */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        
        .glow-blue {
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.08);
        }

        .glow-green {
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.05);
        }

        .glow-red {
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.05);
        }

        /* Force cursor pointer on all interactive elements */
        a, button, select, input[type="checkbox"], input[type="radio"], input[type="submit"], input[type="button"], label[for], [onclick], [role="button"], .cursor-pointer {
            cursor: pointer !important;
        }
    </style>
</head>
<body class="antialiased selection:bg-blue-600 selection:text-white flex flex-col min-h-screen">

    @auth
    <!-- Layout dengan Sidebar untuk User Terautentikasi -->
    <div class="flex flex-col md:flex-row min-h-screen">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="w-full md:w-64 shrink-0 bg-white border-b md:border-b-0 md:border-r border-slate-200 flex flex-col z-40 transition-all duration-300 shadow-sm md:sticky md:top-0 md:h-screen">
            <!-- Sidebar Header / Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-blue-600 rounded-lg text-white font-bold shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0l-3-3m3 3h-7.5" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-slate-800">
                        E-Izin Asrama
                    </span>
                </div>
            </div>

            <!-- Profile Info di Sidebar (Hanya Desktop) -->
            <div class="px-6 py-5 border-b border-slate-100 hidden md:block">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 border border-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</span>
                        <span class="text-xs text-blue-600 capitalize font-medium">{{ Auth::user()->role }}</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav id="sidebar-menu-items" class="flex-1 px-4 py-6 space-y-1.5 hidden md:block">
                
                @if(Auth::user()->role === 'pengelola')
                    <!-- Menu Pengelola -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition duration-150 {{ Route::is('admin.dashboard') ? 'bg-blue-600 text-white shadow-sm glow-blue' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 17.75 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                        </svg>
                        Dashboard
                    </a>
                    
                     <a href="{{ route('admin.students.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition duration-150 {{ Route::is('admin.students.index') ? 'bg-blue-600 text-white shadow-sm glow-blue' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 15 19.128ZM8.625 11.25a3.375 3.375 0 1 1 0-6.75 3.375 3.375 0 0 1 0 6.75ZM15.375 12a3.375 3.375 0 1 0 0-6.75 3.375 3.375 0 0 0 0 6.75Z" />
                        </svg>
                        Daftar Mahasiswa
                    </a>
                @else
                    <!-- Menu Mahasiswa -->
                    <a href="{{ route('student.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition duration-150 {{ Route::is('student.dashboard') ? 'bg-blue-600 text-white shadow-sm glow-blue' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                @endif
                
            </nav>

            <!-- Sidebar Footer / Keluar -->
            <div class="p-4 border-t border-slate-100 hidden md:block">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200/80 hover:border-slate-300 rounded-xl transition shadow-sm font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Area Konten Utama -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Global Top Bar / Navbar (Visible on both Desktop and Mobile) -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-30 shadow-sm shrink-0">
                <div class="flex items-center gap-4">
                    <!-- Mobile Sidebar Toggle -->
                    <button type="button" onclick="toggleSidebarMenu()" class="md:hidden p-2 text-slate-500 hover:text-slate-800 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6" id="sidebar-toggle-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <!-- Page Title / Section Title -->
                    <h1 class="text-md md:text-lg font-bold text-slate-800">
                        @yield('page_title', 'Dashboard')
                    </h1>
                </div>

                <!-- Right Side: User Profile / Info -->
                <div class="flex items-center gap-3">
                    @if(Auth::user()->role === 'mahasiswa' && Auth::user()->student)
                        <!-- Notification Enable Button -->
                        <button type="button" id="notif-bell-btn" class="p-2 text-slate-400 hover:text-blue-600 focus:outline-none transition-colors duration-150 relative cursor-pointer" onclick="toggleWebNotifications()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5.5 h-5.5" id="notif-bell-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                            <span id="notif-badge" class="absolute top-1.5 right-1.5 w-2 h-2 bg-amber-500 rounded-full hidden"></span>
                        </button>

                        <div class="hidden sm:flex flex-col text-right text-xs">
                            <span class="font-bold text-slate-900">{{ Auth::user()->name }}</span>
                            <span class="text-slate-500 text-[10px]">
                                NIM: <strong class="text-slate-700">{{ Auth::user()->student->nim }}</strong> 
                                • Kamar: <strong class="text-slate-700">{{ Auth::user()->student->dorm_room }}</strong>
                            </span>
                        </div>
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs shadow shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @else
                        <div class="hidden sm:flex flex-col text-right text-xs">
                            <span class="font-bold text-slate-900">{{ Auth::user()->name }}</span>
                            <span class="text-blue-650 font-bold capitalize text-[10px]">Pengelola Asrama</span>
                        </div>
                        <div class="w-8 h-8 bg-blue-50 border border-blue-100 text-blue-650 rounded-lg flex items-center justify-center font-bold text-xs shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
            </header>

            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8 overflow-y-auto">
                <!-- Alerts -->
                @if(session('success'))
                <div class="mb-6 flex items-center gap-3 p-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl glow-green transition duration-300" id="success-alert">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 shrink-0 text-emerald-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 flex items-center gap-3 p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl glow-red transition duration-300" id="error-alert">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 shrink-0 text-rose-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>
    
    <script>
        function toggleSidebarMenu() {
            const menuItems = document.getElementById('sidebar-menu-items');
            const footer = menuItems.nextElementSibling;
            const profile = menuItems.previousElementSibling;
            const toggleIcon = document.getElementById('sidebar-toggle-icon');
            
            const isHidden = menuItems.classList.contains('hidden');
            
            if (isHidden) {
                menuItems.classList.remove('hidden');
                footer.classList.remove('hidden');
                profile.classList.remove('hidden');
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />';
            } else {
                menuItems.classList.add('hidden');
                footer.classList.add('hidden');
                profile.classList.add('hidden');
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />';
            }
        }
    </script>
    
    @else
    <!-- Layout untuk Tamu (Halaman Login) -->
    <main class="flex-1 flex items-center justify-center p-4">
        @yield('content')
    </main>
    @endauth

    <script>
        // Auto-fadeout untuk alert sukses/error setelah 5 detik
        setTimeout(() => {
            ['success-alert', 'error-alert'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.style.opacity = '0';
                    el.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => el.remove(), 500);
                }
            });
        }, 5000);

        // Handler AJAX untuk pagination agar tidak reload penuh
        document.addEventListener('click', function(e) {
            const link = e.target.closest('nav[role="navigation"] a, .pagination a');
            if (!link) return;

            const url = link.getAttribute('href');
            if (!url || url === '#' || url.startsWith('javascript:')) return;

            const container = link.closest('[id^="container-"]');
            if (!container) return;

            e.preventDefault();

            container.style.opacity = '0.5';
            container.style.transition = 'opacity 0.15s ease';

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContainer = doc.getElementById(container.id);

                    if (newContainer) {
                        container.innerHTML = newContainer.innerHTML;
                        window.history.pushState({}, '', url);
                        document.dispatchEvent(new CustomEvent('container-loaded', {
                            detail: { containerId: container.id }
                        }));
                    }
                    container.style.opacity = '1';
                })
                .catch(err => {
                    console.error('AJAX pagination failed:', err);
                    container.style.opacity = '1';
                    window.location.href = url;
                });
        });
    </script>

    <script>
        @if(Auth::check() && Auth::user()->role === 'mahasiswa')
        function initNotifications() {
            console.log('Notification: Initializing...');
            updateBellUI();

            if (!('Notification' in window)) {
                return;
            }

            // Auto-start polling if permission is already granted
            if (Notification.permission === 'granted') {
                console.log('Notification: Permission already granted on load.');
                startPollingStatus();
            } else if (Notification.permission === 'default') {
                console.log('Notification: Requesting permission automatically on load...');
                // Coba minta izin secara otomatis saat pertama kali masuk dashboard
                Notification.requestPermission().then(permission => {
                    updateBellUI();
                    if (permission === 'granted') {
                        console.log('Notification: Permission granted automatically on load.');
                        startPollingStatus();
                        new Notification("Notifikasi Aktif!", {
                            body: "Anda akan menerima pemberitahuan ketika izin disetujui atau ditolak.",
                            icon: "https://cdn-icons-png.flaticon.com/512/1827/1827349.png"
                        });
                    }
                });
            } else {
                console.log('Notification: Permission is currently:', Notification.permission);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNotifications);
        } else {
            initNotifications();
        }

        function updateBellUI() {
            const bellBtn = document.getElementById('notif-bell-btn');
            const bellIcon = document.getElementById('notif-bell-icon');
            const badge = document.getElementById('notif-badge');

            if (!bellBtn || !bellIcon) return;

            if (!('Notification' in window)) {
                console.log('Notification: Web notifications not supported in this browser.');
                bellBtn.style.display = 'none';
                return;
            }

            if (Notification.permission === 'granted') {
                bellIcon.classList.remove('text-slate-400', 'text-rose-500');
                bellIcon.classList.add('text-blue-600');
                bellIcon.setAttribute('title', 'Notifikasi Aktif');
                badge.classList.add('hidden');
            } else if (Notification.permission === 'denied') {
                bellIcon.classList.remove('text-blue-600', 'text-slate-400');
                bellIcon.classList.add('text-rose-500');
                bellIcon.setAttribute('title', 'Notifikasi Diblokir oleh Browser');
                badge.classList.remove('hidden');
                badge.className = 'absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full';
            } else {
                // default (prompt)
                bellIcon.classList.remove('text-blue-600', 'text-rose-500');
                bellIcon.classList.add('text-slate-400');
                bellIcon.setAttribute('title', 'Aktifkan Notifikasi Popup');
                badge.classList.remove('hidden');
                badge.className = 'absolute top-1.5 right-1.5 w-2 h-2 bg-amber-500 rounded-full animate-ping';
            }
        }

        function toggleWebNotifications() {
            if (!('Notification' in window)) {
                alert('Browser Anda tidak mendukung notifikasi desktop.');
                return;
            }

            if (Notification.permission === 'granted') {
                alert('Notifikasi sudah aktif!');
                return;
            }

            Notification.requestPermission().then(permission => {
                updateBellUI();
                if (permission === 'granted') {
                    console.log('Notification: Permission granted by user click.');
                    startPollingStatus();
                    new Notification("Notifikasi Aktif!", {
                        body: "Anda akan menerima pemberitahuan ketika izin disetujui atau ditolak.",
                        icon: "https://cdn-icons-png.flaticon.com/512/1827/1827349.png"
                    });
                }
            });
        }

        let pollInterval = null;
        function startPollingStatus() {
            console.log('Notification: Starting status polling...');
            if (pollInterval) clearInterval(pollInterval);

            // Poll immediately on start
            checkLatestPermitStatus();

            // Set interval to poll every 5 seconds (more responsive)
            pollInterval = setInterval(checkLatestPermitStatus, 5000);
        }

        function checkLatestPermitStatus() {
            console.log('Notification: Fetching latest permit status...');
            fetch("{{ route('student.permits.latest-status') }}")
                .then(response => {
                    if (!response.ok) throw new Error('Failed to fetch status');
                    return response.json();
                })
                .then(data => {
                    console.log('Notification: Fetched data:', data);
                    if (!data.latest) {
                        console.log('Notification: No permit records found.');
                        localStorage.removeItem('last_permit_state');
                        return;
                    }

                    const current = data.latest;
                    const cachedStr = localStorage.getItem('last_permit_state');
                    console.log('Notification: Current permit:', current, 'Cached permit:', cachedStr);
                    
                    if (cachedStr) {
                        const cached = JSON.parse(cachedStr);
                        // Jika ID sama tetapi status berubah
                        if (cached.id === current.id && cached.status !== current.status) {
                            console.log('Notification: Status changed from', cached.status, 'to', current.status);
                            
                            let statusText = current.status === 'approved' ? 'DISETUJUI' : 
                                             (current.status === 'rejected' ? 'DITOLAK' : current.status);
                            
                            let bodyText = `Izin ke "${current.destination}" telah ${statusText}.`;
                            if (current.admin_note) {
                                bodyText += ` Catatan: "${current.admin_note}"`;
                            }

                            if (Notification.permission === 'granted') {
                                try {
                                    new Notification("Pembaruan Izin Asrama", {
                                        body: bodyText,
                                        icon: "https://cdn-icons-png.flaticon.com/512/1827/1827349.png",
                                        tag: 'permit-update-' + current.id
                                    });
                                    console.log('Notification: Notification shown successfully.');
                                } catch (e) {
                                    console.error('Notification: Error showing notification:', e);
                                }
                            }

                            // Reload halaman agar tampilan dashboard terupdate langsung
                            console.log('Notification: Reloading page in 1.5 seconds...');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        }
                    }

                    // Simpan state terakhir
                    localStorage.setItem('last_permit_state', JSON.stringify({
                        id: current.id,
                        status: current.status
                    }));
                })
                .catch(err => console.error('Notification: Gagal mengecek status izin:', err));
        }
        @endif
    </script>
    @stack('scripts')

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW registered:', reg.scope))
                    .catch(err => console.error('SW registration failed:', err));
            });
        }
    </script>
</body>
</html>
