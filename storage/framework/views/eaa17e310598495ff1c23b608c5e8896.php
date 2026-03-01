<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - Vehicle Inspection System</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('logo.png')); ?>">

    <!-- DNS prefetch / preconnect for CDN hosts -->
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    <!-- Font Awesome (CSS — load before body so icons render immediately) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tom Select — searchable dropdowns -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css">

    <!-- Tailwind CSS — deferred so it doesn't block page render -->
    <script defer src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js — deferred, must load after Tailwind -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    
    <style>
        /* ── Page-load progress bar ── */
        #nprogress-bar {
            position: fixed;
            top: 0; left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, #3b82f6, #60a5fa, #93c5fd);
            z-index: 9999;
            transition: width .25s ease, opacity .4s ease;
            box-shadow: 0 0 8px rgba(59,130,246,.6);
        }
        #nprogress-bar.done { width: 100% !important; opacity: 0; }

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

        /* ── Tom Select overrides — match Tailwind form style ── */
        .ts-wrapper { width: 100%; }
        .ts-wrapper.single .ts-control,
        .ts-wrapper.multi .ts-control {
            border: 1px solid #d1d5db; /* gray-300 */
            border-radius: 0.5rem;     /* rounded-lg */
            padding: 0.4rem 0.75rem;
            min-height: 2.4rem;
            font-size: 0.875rem;
            box-shadow: none;
            background: #fff;
            cursor: pointer;
        }
        .ts-wrapper.single.focus .ts-control,
        .ts-wrapper.multi.focus .ts-control {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59,130,246,.25);
        }
        .ts-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,.1);
            font-size: 0.875rem;
        }
        .ts-dropdown .option {
            padding: 0.45rem 0.75rem;
        }
        .ts-dropdown .option:hover,
        .ts-dropdown .option.active {
            background: #eff6ff;
            color: #1d4ed8;
        }
        .ts-dropdown .option-group-header {
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            padding: 0.4rem 0.75rem 0.2rem;
            background: #f9fafb;
        }
        /* Keep multi-select (dept report) looking clean */
        .ts-wrapper.multi .ts-control .item {
            background: #dbeafe;
            color: #1e40af;
            border-radius: 0.25rem;
            padding: 0.1rem 0.4rem;
            font-size: 0.75rem;
        }
        /* Search input inside the control */
        .ts-control input {
            font-size: 0.875rem !important;
        }
        /* State-level options get subtle background */
        .ts-dropdown .ts-opt-state {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        .ts-dropdown .ts-opt-state:first-child {
            border-top: none;
        }
        /* Center options slightly indented */
        .ts-dropdown .ts-opt-center {
            background: #fff;
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
    
    <?php echo $__env->yieldPushContent('styles'); ?>
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
                    <img src="<?php echo e(asset('logo.png')); ?>" alt="Logo" class="w-8 h-8 object-contain flex-shrink-0">
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
                <?php
                $navLinks = [
                    ['route' => 'dashboard',         'match' => 'dashboard',       'icon' => 'fa-home',           'label' => 'Dashboard'],
                    ['route' => 'inspections.index',  'match' => 'inspections.*',   'icon' => 'fa-clipboard-check','label' => 'Inspections'],
                    ['route' => 'vehicles.index',     'match' => 'vehicles.*',      'icon' => 'fa-car',            'label' => 'Vehicles'],
                    ['route' => 'reports.index',      'match' => 'reports.*',       'icon' => 'fa-chart-bar',      'label' => 'Reports'],
                    ['route' => 'equipment.index',    'match' => 'equipment.*',     'icon' => 'fa-tools',          'label' => 'Equipment'],
                    ['route' => 'personnel.index',    'match' => 'personnel.*',     'icon' => 'fa-users',          'label' => 'Personnel'],
                    ['route' => 'activity-log',       'match' => 'activity-log',    'icon' => 'fa-history',        'label' => 'Activity Log'],
                ];
                ?>

                <?php $__currentLoopData = $navLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route($link['route'])); ?>"
                   class="sidebar-link flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-700 transition-colors <?php echo e(request()->routeIs($link['match']) ? 'active' : 'text-gray-300'); ?>"
                   :title="sidebarCollapsed ? '<?php echo e($link['label']); ?>' : ''"
                   x-data="{}" x-tooltip.raw="<?php echo e($link['label']); ?>">
                    <i class="fas <?php echo e($link['icon']); ?> text-base flex-shrink-0" style="width:1.25rem; text-align:center;"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 text-sm font-medium whitespace-nowrap sidebar-label"><?php echo e($link['label']); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-departments')): ?>
                <a href="<?php echo e(route('departments.index')); ?>"
                   class="sidebar-link flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-700 transition-colors <?php echo e(request()->routeIs('departments.*') ? 'active' : 'text-gray-300'); ?>"
                   :title="sidebarCollapsed ? 'Departments' : ''">
                    <i class="fas fa-building text-base flex-shrink-0" style="width:1.25rem; text-align:center;"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 text-sm font-medium whitespace-nowrap sidebar-label">Departments</span>
                </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users')): ?>
                <a href="<?php echo e(route('users.index')); ?>"
                   class="sidebar-link flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-700 transition-colors <?php echo e(request()->routeIs('users.*') ? 'active' : 'text-gray-300'); ?>"
                   :title="sidebarCollapsed ? 'Users' : ''">
                    <i class="fas fa-user-cog text-base flex-shrink-0" style="width:1.25rem; text-align:center;"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 text-sm font-medium whitespace-nowrap sidebar-label">Users</span>
                </a>
                <?php endif; ?>
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
                                    fetch(`<?php echo e(route('search')); ?>?q=${searchQuery}`)
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
                                <?php echo e(substr(auth()->user()->nickname, 0, 1)); ?>

                            </div>
                            <div class="hidden md:block text-left">
                                <div class="text-sm font-medium text-gray-700"><?php echo e(auth()->user()->nickname); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e(auth()->user()->email); ?></div>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="<?php echo e(route('profile')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user w-5"></i> Profile
                            </a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
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
                <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo e(session('success')); ?>

                </div>
                <?php endif; ?>
                
                <?php if(session('error')): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo e(session('error')); ?>

                </div>
                <?php endif; ?>
                
                <?php if($errors->any()): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <!-- Tom Select JS + init -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
    (function () {
        /**
         * Initialise Tom Select on every <select> with class "ts-select".
         * Safe to call multiple times — skips already-initialised elements.
         */
        function initTomSelects() {
            document.querySelectorAll('select.ts-select').forEach(function (el) {
                if (el.tomselect) return; // already initialised

                var isMulti = el.multiple;

                new TomSelect(el, {
                    plugins: isMulti ? ['remove_button', 'checkbox_options'] : [],
                    maxOptions: 500,
                    create: false,
                    allowEmptyOption: !isMulti,
                    maxItems: isMulti ? null : 1,
                    selectOnTab: true,
                    closeAfterSelect: !isMulti,
                    render: {
                        option: function (data, escape) {
                            var isState = data.element && data.element.dataset.deptType === 'state';
                            if (isState) {
                                return '<div class="option ts-opt-state">'
                                    + '<span style="font-weight:700;color:#374151;">&#9658; ' + escape(data.text) + '</span>'
                                    + '</div>';
                            }
                            var isCenter = data.element && data.element.dataset.deptType === 'center';
                            if (isCenter) {
                                return '<div class="option ts-opt-center" style="padding-left:1.5rem;">'
                                    + '<span style="color:#4b5563;">&#8627; ' + escape(data.text) + '</span>'
                                    + '</div>';
                            }
                            return '<div class="option">' + escape(data.text) + '</div>';
                        },
                        item: function (data, escape) {
                            var isState = data.element && data.element.dataset.deptType === 'state';
                            var prefix = isState ? '&#9658; ' : '';
                            return '<div class="item">' + prefix + escape(data.text) + '</div>';
                        }
                    }
                });
            });
        }

        // Run on initial load — this script is at end of <body>, DOM is already ready
        initTomSelects();

        // Re-run when Alpine reveals hidden panels (x-show / x-collapse mutates the DOM)
        var _tsObserver = new MutationObserver(function () {
            var unInit = document.querySelectorAll('select.ts-select:not(.tomselected)');
            if (unInit.length) initTomSelects();
        });
        _tsObserver.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['style', 'class'] });
    })();
    </script>

    <!-- Page-load progress bar -->
    <div id="nprogress-bar"></div>
    <script>
    (function () {
        var bar = document.getElementById('nprogress-bar');
        var trickle, width = 1;

        function start() {
            width = 1;
            bar.style.opacity = '1';
            bar.style.width = width + '%';
            trickle = setInterval(function () {
                // slow down as it approaches 90%
                var inc = width < 30 ? 4 : width < 60 ? 2 : width < 80 ? 1 : 0.4;
                width = Math.min(width + inc, 90);
                bar.style.width = width + '%';
            }, 200);
        }

        function done() {
            clearInterval(trickle);
            bar.style.width = '100%';
            setTimeout(function () { bar.classList.add('done'); }, 250);
            setTimeout(function () {
                bar.classList.remove('done');
                bar.style.width = '0%';
            }, 700);
        }

        // Start immediately (page just loaded into JS)
        start();
        // Finish when page is fully loaded
        window.addEventListener('load', done);

        // Also hook anchor/form navigation (non-AJAX)
        document.addEventListener('click', function (e) {
            var a = e.target.closest('a[href]');
            if (!a) return;
            var href = a.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript') || a.target === '_blank') return;
            start();
        });
        document.addEventListener('submit', function (e) {
            if (e.target.tagName === 'FORM') start();
        });
    })();
    </script>
</body>
</html>
<?php /**PATH C:\Users\talk2\OneDrive\Desktop\inspection\resources\views/layouts/app.blade.php ENDPATH**/ ?>