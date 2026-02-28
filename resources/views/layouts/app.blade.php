<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Vehicle Inspection System</title>
    
    <!-- DNS prefetch / preconnect for CDN hosts -->
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    <!-- Font Awesome (CSS — load before body so icons render immediately) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS — deferred so it doesn't block page render -->
    <script defer src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js — deferred, must load after Tailwind -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link.active {
            background-color: #3b82f6;
            color: white;
        }

        /* Sidebar transition */
        aside {
            transition: width 0.25s ease, transform 0.25s ease;
        }
        /* Collapsed sidebar — icon-only strip */
        aside.collapsed {
            width: 4rem !important;
        }
        aside.collapsed .sidebar-label,
        aside.collapsed .sidebar-section-label {
            display: none;
        }
        aside.collapsed .sidebar-logo-text {
            display: none;
        }
        aside.collapsed nav a {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        aside.collapsed nav a i {
            margin: 0;
            width: auto;
        }

        body { overflow-x: hidden; }

        main {
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .overflow-x-auto {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }
        .overflow-x-auto::-webkit-scrollbar { height: 8px; width: 8px; }
        .overflow-x-auto::-webkit-scrollbar-track { background: #f7fafc; }
        .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #a0aec0; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div x-data="{
            sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            profileOpen: false,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
                localStorage.setItem('sidebarOpen', this.sidebarOpen);
            },
            toggleCollapse() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
            }
         }" class="flex h-screen overflow-hidden">

        <!-- Mobile overlay -->
        <div x-show="sidebarOpen && window.innerWidth < 1024"
             @click="toggleSidebar()"
             x-cloak
             class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="[
                    sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                    sidebarCollapsed ? 'w-16' : 'w-64'
               ]"
               class="fixed inset-y-0 left-0 z-50 bg-gray-900 text-white lg:static lg:inset-0 flex flex-col overflow-hidden"
               style="transition: width 0.25s ease, transform 0.25s ease;">

            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-4 bg-gray-800 flex-shrink-0">
                <div class="flex items-center min-w-0">
                    <i class="fas fa-car text-2xl text-blue-500 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 text-lg font-semibold whitespace-nowrap overflow-hidden sidebar-logo-text">VIS Nigeria</span>
                </div>
                <!-- Collapse toggle (desktop) -->
                <button @click="toggleCollapse()" class="hidden lg:flex items-center justify-center w-7 h-7 rounded hover:bg-gray-700 text-gray-400 hover:text-white flex-shrink-0 ml-1" :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
                    <i class="fas text-sm" :class="sidebarCollapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
                </button>
                <!-- Close on mobile -->
                <button @click="toggleSidebar()" class="lg:hidden flex items-center justify-center w-7 h-7 rounded hover:bg-gray-700 text-gray-400">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto mt-4 px-2 pb-4">
                @php
                $navLinks = [
                    ['route' => 'dashboard',         'match' => 'dashboard',       'icon' => 'fa-home',           'label' => 'Dashboard'],
                    ['route' => 'inspections.index',  'match' => 'inspections.*',   'icon' => 'fa-clipboard-check','label' => 'Inspections'],
                    ['route' => 'vehicles.index',     'match' => 'vehicles.*',      'icon' => 'fa-car',            'label' => 'Vehicles'],
                    ['route' => 'reports.index',      'match' => 'reports.*',       'icon' => 'fa-chart-bar',      'label' => 'Reports'],
                    ['route' => 'equipment.index',    'match' => 'equipment.*',     'icon' => 'fa-tools',          'label' => 'Equipment'],
                    ['route' => 'personnel.index',    'match' => 'personnel.*',     'icon' => 'fa-users',          'label' => 'Personnel'],
                    ['route' => 'activity-log',       'match' => 'activity-log',    'icon' => 'fa-history',        'label' => 'Activity Log'],
                ];
                @endphp

                @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="sidebar-link flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs($link['match']) ? 'active' : 'text-gray-300' }}"
                   :title="sidebarCollapsed ? '{{ $link['label'] }}' : ''"
                   x-data="{}" x-tooltip.raw="{{ $link['label'] }}">
                    <i class="fas {{ $link['icon'] }} text-base flex-shrink-0" style="width:1.25rem; text-align:center;"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 text-sm font-medium whitespace-nowrap sidebar-label">{{ $link['label'] }}</span>
                </a>
                @endforeach

                @can('manage-departments')
                <a href="{{ route('departments.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('departments.*') ? 'active' : 'text-gray-300' }}"
                   :title="sidebarCollapsed ? 'Departments' : ''">
                    <i class="fas fa-building text-base flex-shrink-0" style="width:1.25rem; text-align:center;"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 text-sm font-medium whitespace-nowrap sidebar-label">Departments</span>
                </a>
                @endcan

                @can('manage-users')
                <a href="{{ route('users.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('users.*') ? 'active' : 'text-gray-300' }}"
                   :title="sidebarCollapsed ? 'Users' : ''">
                    <i class="fas fa-user-cog text-base flex-shrink-0" style="width:1.25rem; text-align:center;"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 text-sm font-medium whitespace-nowrap sidebar-label">Users</span>
                </a>
                @endcan
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            <!-- Top Navigation -->
            <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200 flex-shrink-0">
                <div class="flex items-center">
                    <!-- Mobile hamburger -->
                    <button @click="toggleSidebar()" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden mr-3">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Search -->
                    <div class="relative ml-4" x-data="{ searchOpen: false, searchQuery: '', results: {} }">
                        <input 
                            type="text" 
                            x-model="searchQuery"
                            @focus="searchOpen = true"
                            @input.debounce.500ms="
                                if (searchQuery.length > 2) {
                                    fetch(`{{ route('search') }}?q=${searchQuery}`)
                                        .then(r => r.json())
                                        .then(data => results = data);
                                }
                            "
                            placeholder="Search vehicles, inspections..." 
                            class="w-64 px-4 py-2 pl-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        
                        <!-- Search Results Dropdown -->
                        <div x-show="searchOpen && searchQuery.length > 2" 
                             @click.away="searchOpen = false"
                             x-cloak
                             class="absolute z-50 w-96 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 max-h-96 overflow-y-auto">
                            
                            <template x-if="results.vehicles && results.vehicles.length > 0">
                                <div class="p-3">
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Vehicles</h4>
                                    <template x-for="vehicle in results.vehicles" :key="vehicle.id">
                                        <a :href="`/vehicles/${vehicle.id}`" class="block p-2 hover:bg-gray-50 rounded">
                                            <div class="font-medium" x-text="vehicle.plateno"></div>
                                            <div class="text-sm text-gray-600" x-text="vehicle.owner"></div>
                                        </a>
                                    </template>
                                </div>
                            </template>
                            
                            <template x-if="results.inspections && results.inspections.length > 0">
                                <div class="p-3 border-t">
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Inspections</h4>
                                    <template x-for="inspection in results.inspections" :key="inspection.id">
                                        <a :href="`/inspections/${inspection.id}`" class="block p-2 hover:bg-gray-50 rounded">
                                            <div class="font-medium" x-text="inspection.seriesno"></div>
                                            <div class="text-sm text-gray-600" x-text="inspection.plateno"></div>
                                        </a>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="relative text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr(auth()->user()->nickname, 0, 1) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <div class="text-sm font-medium text-gray-700">{{ auth()->user()->nickname }}</div>
                                <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user w-5"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt w-5"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
                @endif
                
                @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
