@php
    $activeMenu = '';
    if (
        (
            request()->routeIs('my-payslips.*')
            || request()->routeIs('announcements.*')
            || request()->routeIs('documents.*')
            || request()->routeIs('attendance.*')
            || request()->routeIs('my-bonuses.*')
            || request()->routeIs('my-advances.*')
            || request()->routeIs('my-loans.*')
            || request()->routeIs('chatbot.*')
            || (request()->routeIs('leaves.*') && !request()->routeIs('leaves.approvals'))
            || (request()->routeIs('finance.*') && !request()->routeIs('finance.approvals') && !request()->routeIs('finance.approve.*') && !request()->routeIs('finance.reject') && !request()->routeIs('finance.mark-paid'))
        )
    ) {
        $activeMenu = 'workspace';
    } elseif (request()->routeIs('employees.*') || request()->routeIs('admin.applications.*') || request()->routeIs('admin.departments.*') || request()->routeIs('admin.designations.*') || request()->routeIs('admin.grade-levels.*') || request()->routeIs('jobs.*') || request()->routeIs('admin.jobs.*') || request()->routeIs('admin.employment-statuses.*')) {
        $activeMenu = 'people';
    } elseif (request()->routeIs('performance.goals.*') || request()->routeIs('performance.team-goals.*') || request()->routeIs('performance.reviews.*') || request()->routeIs('employee.peer-feedback.*') || request()->routeIs('admin.performance.objectives.*') || request()->routeIs('lms.*')) {
        $activeMenu = 'performance';
    } elseif (request()->routeIs('admin.ai.*') || request()->routeIs('admin.assets.*') || request()->routeIs('admin.resignations.*') || request()->routeIs('admin.offboarding.*') || request()->routeIs('admin.documents.*') || request()->routeIs('admin.audit-logs.*') || request()->routeIs('admin.approval-chains.*') || request()->routeIs('settings.*') || request()->routeIs('admin.leaves.*') || request()->routeIs('admin.attendance.*') || request()->routeIs('admin.leave-types.*') || request()->routeIs('admin.leave-balances.*')) {
        $activeMenu = 'admin';
    } elseif (request()->routeIs('admin.salary.*') || request()->routeIs('admin.payroll.*') || request()->routeIs('admin.bonuses.*') || request()->routeIs('admin.loans.*') || request()->routeIs('admin.advances.*') || request()->routeIs('admin.payroll-reports.*') || request()->routeIs('admin.tax-reliefs.*') || request()->routeIs('admin.overtime-policies.*') || request()->routeIs('admin.tax-brackets.*') || request()->routeIs('admin.penalties.*')) {
        $activeMenu = 'finance';
    }

    $pendingApprovals = 0;
    if (\Illuminate\Support\Facades\Schema::hasTable('approval_requests')) {
        $pendingApprovals = \App\Models\ApprovalRequest::where('status', 'pending')->count();
    }

    $authUser = Auth::user();
    $userRole = $authUser && method_exists($authUser, 'getRoleNames')
        ? ($authUser->getRoleNames()->first() ?? 'User')
        : 'User';
    $userEmail = $authUser?->email ?? '';
@endphp

<div class="flex flex-col h-full"
     x-data="{
        activeMenu: '{{ $activeMenu }}',
        flyoutKey: null,
        flyoutStyle: {},
        tip: { show: false, label: '', style: {} },
        init() {
            window.addEventListener('sidebar-collapse-changed', (e) => {
                if (!e.detail?.collapsed) {
                    this.flyoutKey = null;
                    this.flyoutStyle = {};
                    this.hideTip();
                }
            });
        },
        isCollapsed() {
            return document.documentElement.classList.contains('sidebar-collapsed');
        },
        getNavQuery() {
            try {
                return (window.Alpine && Alpine.$data(document.body)?.navQuery) || '';
            } catch (e) {
                return '';
            }
        },
        match(label) {
            const q = (this.getNavQuery() || '').trim().toLowerCase();
            return !q || String(label).toLowerCase().includes(q);
        },
        matchAny() {
            const q = (this.getNavQuery() || '').trim().toLowerCase();
            if (!q) return true;
            return Array.from(arguments).some(l => String(l).toLowerCase().includes(q));
        },
        sectionOpen(key) {
            if (this.isCollapsed()) return this.flyoutKey === key;
            return this.activeMenu === key || !!this.getNavQuery();
        },
        openSection(key, event) {
            if (this.isCollapsed()) {
                this.hideTip();
                if (this.flyoutKey === key) {
                    this.flyoutKey = null;
                    this.flyoutStyle = {};
                    return;
                }
                const el = event.currentTarget;
                const r = el.getBoundingClientRect();
                this._ignoreOutside = true;
                this.flyoutKey = key;
                this.flyoutStyle = {
                    position: 'fixed',
                    top: Math.max(8, r.top) + 'px',
                    left: (r.right + 8) + 'px',
                    zIndex: '80',
                };
                this.$nextTick(() => {
                    this.clampFlyout(key);
                    requestAnimationFrame(() => { this._ignoreOutside = false; });
                });
            } else {
                this.flyoutKey = null;
                this.flyoutStyle = {};
                this.activeMenu = this.activeMenu === key ? null : key;
            }
        },
        clampFlyout(key) {
            const panel = this.$refs['flyout_' + key];
            if (!panel || !this.flyoutStyle.top) return;
            const rect = panel.getBoundingClientRect();
            const pad = 8;
            let top = parseFloat(this.flyoutStyle.top);
            if (top + rect.height > window.innerHeight - pad) {
                top = Math.max(pad, window.innerHeight - rect.height - pad);
            }
            this.flyoutStyle = { ...this.flyoutStyle, top: top + 'px' };
        },
        closeFlyout() {
            if (this._ignoreOutside) return;
            this.flyoutKey = null;
            this.flyoutStyle = {};
        },
        showTip(event, label) {
            if (!this.isCollapsed() || this.flyoutKey) return;
            const r = event.currentTarget.getBoundingClientRect();
            this.tip = {
                show: true,
                label,
                style: {
                    position: 'fixed',
                    top: (r.top + r.height / 2) + 'px',
                    left: (r.right + 10) + 'px',
                    transform: 'translateY(-50%)',
                    zIndex: '80',
                },
            };
        },
        hideTip() {
            this.tip = { ...this.tip, show: false };
        },
     }"
     @keydown.escape.window="closeFlyout(); hideTip()">
    {{-- Brand --}}
    <div class="sidebar-brand-wrap flex items-center flex-shrink-0 pt-5 pb-4 px-3">
        <a href="{{ route('dashboard') }}" class="flex items-center min-w-0 gap-3">
            @if(!empty($companyLogo))
                <img src="{{ asset('storage/' . $companyLogo) }}"
                     alt="{{ $companyName ?? 'Logo' }}"
                     class="h-9 w-9 object-contain flex-shrink-0 rounded-xl">
            @else
                <div class="w-9 h-9 bg-neutral-900 dark:bg-white rounded-xl flex-shrink-0 flex items-center justify-center shadow-sm">
                    <span class="text-white dark:text-neutral-900 font-bold text-xs tracking-tight">AI</span>
                </div>
            @endif
            <div class="sidebar-brand-text min-w-0">
                <span class="block font-semibold text-[0.9375rem] tracking-tight text-neutral-900 dark:text-white truncate leading-tight">
                    {{ $companyName ?? config('app.name', 'YourDigitalHRM') }}
                </span>
                <span class="block text-[0.6875rem] text-neutral-500 dark:text-neutral-400 truncate leading-tight mt-0.5">
                    {{ $userEmail ?: $userRole }}
                </span>
            </div>
        </a>
    </div>

    <template x-teleport="body">
        <div x-show="tip.show"
             x-cloak
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 translate-x-1"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             :style="tip.style"
             class="sidebar-collapsed-tip"
             x-text="tip.label"></div>
    </template>

    {{-- Nav --}}
    <div class="flex-1 flex flex-col min-h-0"
         :class="flyoutKey ? 'overflow-visible' : 'overflow-y-auto overflow-x-hidden'"
         @scroll="closeFlyout(); hideTip()">

        <nav class="sidebar-nav-wrap space-y-1 flex-1 pb-2 px-3">

            <div x-show="!getNavQuery()" x-cloak class="sidebar-section-label">Main</div>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               x-show="match('Dashboard')"
               @mouseenter="showTip($event, 'Dashboard')"
               @mouseleave="hideTip()"
               class="sidebar-nav-link group flex items-center gap-3 px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard') ? 'sidebar-nav-link-active' : '' }}">
                <svg class="flex-shrink-0 h-5 w-5 {{ request()->routeIs('dashboard') ? '' : 'text-neutral-400 group-hover:text-neutral-700 dark:group-hover:text-neutral-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            {{-- Pending Approvals --}}
            <a href="{{ route('approvals.index') }}"
               x-show="match('Pending Approvals')"
               @mouseenter="showTip($event, 'Pending Approvals')"
               @mouseleave="hideTip()"
               class="sidebar-nav-link group flex items-center gap-3 px-3 py-2.5 text-sm font-medium {{ request()->routeIs('approvals.*') ? 'sidebar-nav-link-active' : '' }}">
                <svg class="flex-shrink-0 h-5 w-5 {{ request()->routeIs('approvals.*') ? '' : 'text-neutral-400 group-hover:text-neutral-700 dark:group-hover:text-neutral-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span class="flex-1">Pending Approvals</span>
                <span class="sidebar-badge {{ $pendingApprovals > 0 ? 'sidebar-badge-active' : '' }}">{{ $pendingApprovals }}</span>
            </a>

            {{-- My Workspace --}}
            <div class="relative"
                 x-show="matchAny('My Workspace', 'My Payslips', 'Announcements', 'My Documents', 'Attendance', 'Clock-in QR', 'Leaves', 'Relief Requests', 'Claims', 'My Bonuses', 'Salary Advances', 'Loans', 'AI Assistant')"
                 @click.outside="flyoutKey === 'workspace' && closeFlyout()">
                <button type="button"
                        @click="openSection('workspace', $event)"
                        @mouseenter="showTip($event, 'My Workspace')"
                        @mouseleave="hideTip()"
                        :class="{ 'sidebar-nav-parent-active': activeMenu === 'workspace' || flyoutKey === 'workspace' }"
                        class="sidebar-nav-link group w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium focus:outline-none">
                    <svg class="flex-shrink-0 h-5 w-5 text-neutral-400 group-hover:text-neutral-700 dark:group-hover:text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="flex-1 text-left">My Workspace</span>
                    <svg :class="{ 'rotate-90': activeMenu === 'workspace' }" class="flex-shrink-0 h-3.5 w-3.5 text-neutral-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="sectionOpen('workspace') && matchAny('My Workspace', 'My Payslips', 'Announcements', 'My Documents', 'Attendance', 'Clock-in QR', 'Leaves', 'Relief Requests', 'Claims', 'My Bonuses', 'Salary Advances', 'Loans', 'AI Assistant')"
                     x-ref="flyout_workspace"
                     x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     :class="isCollapsed() && flyoutKey === 'workspace' ? 'sidebar-submenu sidebar-flyout space-y-0.5' : 'sidebar-submenu space-y-0.5 mt-1 mb-2'"
                     :style="isCollapsed() && flyoutKey === 'workspace' ? flyoutStyle : {}">
                    <a href="{{ route('my-payslips.index') }}" x-show="match('My Payslips')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('my-payslips.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>My Payslips</span>
                    </a>
                    <a href="{{ route('announcements.index') }}" x-show="match('Announcements')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('announcements.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <span>Announcements</span>
                    </a>
                    <a href="{{ route('documents.index') }}" x-show="match('My Documents')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('documents.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        <span>My Documents</span>
                    </a>
                    <a href="{{ route('attendance.index') }}" x-show="match('Attendance')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('attendance.index') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Attendance</span>
                    </a>
                    <a href="{{ route('attendance.qr') }}" x-show="match('Clock-in QR')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('attendance.qr') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>Clock-in QR</span>
                    </a>
                    <a href="{{ route('leaves.index') }}" x-show="match('Leaves')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('leaves.index') || request()->routeIs('leaves.create') || request()->routeIs('leaves.show') || request()->routeIs('leaves.edit') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Leaves</span>
                    </a>
                    <a href="{{ route('leaves.relief-requests') }}" x-show="match('Relief Requests')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('leaves.relief-requests') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Relief Requests</span>
                    </a>
                    <a href="{{ route('finance.index') }}" x-show="match('Claims')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('finance.*') && !request()->routeIs('finance.approvals') && !request()->routeIs('finance.approve.*') && !request()->routeIs('finance.reject') && !request()->routeIs('finance.mark-paid') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                        <span>Claims</span>
                    </a>
                    <a href="{{ route('my-bonuses.index') }}" x-show="match('My Bonuses')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('my-bonuses.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        <span>My Bonuses</span>
                    </a>
                    <a href="{{ route('my-advances.index') }}" x-show="match('Salary Advances')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('my-advances.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span>Salary Advances</span>
                    </a>
                    <a href="{{ route('my-loans.index') }}" x-show="match('Loans')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('my-loans.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Loans</span>
                    </a>
                    <a href="{{ route('chatbot.index') }}" x-show="match('AI Assistant')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('chatbot.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        <span>AI Assistant</span>
                    </a>
                </div>
            </div>

            <div x-show="!getNavQuery()" x-cloak class="sidebar-section-label pt-2">Manage</div>

            {{-- People & Culture --}}
            @role('Admin|HR')
            <div class="relative"
                 x-show="matchAny('People & Culture', 'Employees', 'Jobs', 'Recruitment', 'Departments', 'Designations', 'Grade Levels', 'Employment Statuses')"
                 @click.outside="flyoutKey === 'people' && closeFlyout()">
                <button type="button"
                        @click="openSection('people', $event)"
                        @mouseenter="showTip($event, 'People & Culture')"
                        @mouseleave="hideTip()"
                        :class="{ 'sidebar-nav-parent-active': activeMenu === 'people' || flyoutKey === 'people' }"
                        class="sidebar-nav-link group w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium focus:outline-none">
                    <svg class="flex-shrink-0 h-5 w-5 text-neutral-400 group-hover:text-neutral-700 dark:group-hover:text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="flex-1 text-left">People & Culture</span>
                    <svg :class="{ 'rotate-90': activeMenu === 'people' }" class="flex-shrink-0 h-3.5 w-3.5 text-neutral-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="sectionOpen('people') && matchAny('People & Culture', 'Employees', 'Jobs', 'Recruitment', 'Departments', 'Designations', 'Grade Levels', 'Employment Statuses')"
                     x-ref="flyout_people"
                     x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     :class="isCollapsed() && flyoutKey === 'people' ? 'sidebar-submenu sidebar-flyout space-y-0.5' : 'sidebar-submenu space-y-0.5 mt-1 mb-2'"
                     :style="isCollapsed() && flyoutKey === 'people' ? flyoutStyle : {}">
                    @can('view employees')
                    <a href="{{ route('employees.index') }}" x-show="match('Employees')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('employees.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Employees</span>
                    </a>
                    <a href="{{ route('admin.jobs.index') }}" x-show="match('Jobs')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.jobs.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Jobs</span>
                    </a>
                    @endcan
                    <a href="{{ route('admin.applications.kanban') }}" x-show="match('Recruitment')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.applications.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <span>Recruitment</span>
                    </a>
                    <a href="{{ route('admin.departments.index') }}" x-show="match('Departments')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.departments.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Departments</span>
                    </a>
                    <a href="{{ route('admin.designations.index') }}" x-show="match('Designations')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.designations.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Designations</span>
                    </a>
                    <a href="{{ route('admin.grade-levels.index') }}" x-show="match('Grade Levels')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.grade-levels.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span>Grade Levels</span>
                    </a>
                    <a href="{{ route('admin.employment-statuses.index') }}" x-show="match('Employment Statuses')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.employment-statuses.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Employment Statuses</span>
                    </a>
                </div>
            </div>
            @endrole

            {{-- Performance & Learning --}}
            <div class="relative"
                 x-show="matchAny('Performance & Learning', 'Company Goals', 'Goals', 'Team Goals', 'Reviews', '360 Feedback', 'Courses')"
                 @click.outside="flyoutKey === 'performance' && closeFlyout()">
                <button type="button"
                        @click="openSection('performance', $event)"
                        @mouseenter="showTip($event, 'Performance & Learning')"
                        @mouseleave="hideTip()"
                        :class="{ 'sidebar-nav-parent-active': activeMenu === 'performance' || flyoutKey === 'performance' }"
                        class="sidebar-nav-link group w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium focus:outline-none">
                    <svg class="flex-shrink-0 h-5 w-5 text-neutral-400 group-hover:text-neutral-700 dark:group-hover:text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="flex-1 text-left">Performance & Learning</span>
                    <svg :class="{ 'rotate-90': activeMenu === 'performance' }" class="flex-shrink-0 h-3.5 w-3.5 text-neutral-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="sectionOpen('performance') && matchAny('Performance & Learning', 'Company Goals', 'Goals', 'Team Goals', 'Reviews', '360 Feedback', 'Courses')"
                     x-ref="flyout_performance"
                     x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     :class="isCollapsed() && flyoutKey === 'performance' ? 'sidebar-submenu sidebar-flyout space-y-0.5' : 'sidebar-submenu space-y-0.5 mt-1 mb-2'"
                     :style="isCollapsed() && flyoutKey === 'performance' ? flyoutStyle : {}">
                    @role('Admin|HR')
                    <a href="{{ route('admin.performance.objectives.index') }}" x-show="match('Company Goals')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.performance.objectives.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Company Goals</span>
                    </a>
                    @endrole
                    <a href="{{ route('performance.goals.index') }}" x-show="match('Goals')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('performance.goals.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Goals</span>
                    </a>
                    @if(Auth::user() && \App\Models\Employee::where('manager_id', Auth::id())->exists())
                    <a href="{{ route('performance.team-goals.index') }}" x-show="match('Team Goals')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('performance.team-goals.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Team Goals</span>
                    </a>
                    @endif
                    <a href="{{ route('performance.reviews.index') }}" x-show="match('Reviews')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('performance.reviews.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span>Reviews</span>
                    </a>
                    <a href="{{ route('employee.peer-feedback.index') }}" x-show="match('360 Feedback')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('employee.peer-feedback.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                        <span>360 Feedback</span>
                    </a>
                    <a href="{{ route('lms.index') }}" x-show="match('Courses')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('lms.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span>Courses</span>
                    </a>
                </div>
            </div>

            {{-- Administrative Tools --}}
            @role('Admin')
            <div class="relative"
                 x-show="matchAny('Administrative Tools', 'Asset Management', 'Leave Admin', 'Leave Types', 'Leave Balances', 'Gatekeeper Scanner', 'Resignations', 'Offboarding Tasks', 'Document Center', 'Audit Logs', 'Approval Workflows', 'AI Compliance', 'AI Performance', 'System Settings')"
                 @click.outside="flyoutKey === 'admin' && closeFlyout()">
                <button type="button"
                        @click="openSection('admin', $event)"
                        @mouseenter="showTip($event, 'Administrative Tools')"
                        @mouseleave="hideTip()"
                        :class="{ 'sidebar-nav-parent-active': activeMenu === 'admin' || flyoutKey === 'admin' }"
                        class="sidebar-nav-link group w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium focus:outline-none">
                    <svg class="flex-shrink-0 h-5 w-5 text-neutral-400 group-hover:text-neutral-700 dark:group-hover:text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="flex-1 text-left">Administrative Tools</span>
                    <svg :class="{ 'rotate-90': activeMenu === 'admin' }" class="flex-shrink-0 h-3.5 w-3.5 text-neutral-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="sectionOpen('admin') && matchAny('Administrative Tools', 'Asset Management', 'Leave Admin', 'Leave Types', 'Leave Balances', 'Gatekeeper Scanner', 'Resignations', 'Offboarding Tasks', 'Document Center', 'Audit Logs', 'Approval Workflows', 'AI Compliance', 'AI Performance', 'System Settings')"
                     x-ref="flyout_admin"
                     x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     :class="isCollapsed() && flyoutKey === 'admin' ? 'sidebar-submenu sidebar-flyout space-y-0.5' : 'sidebar-submenu space-y-0.5 mt-1 mb-2'"
                     :style="isCollapsed() && flyoutKey === 'admin' ? flyoutStyle : {}">
                    <a href="{{ route('admin.assets.index') }}" x-show="match('Asset Management')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.assets.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Asset Management</span>
                    </a>
                    <a href="{{ route('admin.leaves.index') }}" x-show="match('Leave Admin')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.leaves.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Leave Admin</span>
                    </a>
                    <a href="{{ route('admin.leave-types.index') }}" x-show="match('Leave Types')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.leave-types.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span>Leave Types</span>
                    </a>
                    <a href="{{ route('admin.leave-balances.index') }}" x-show="match('Leave Balances')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.leave-balances.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                        <span>Leave Balances</span>
                    </a>
                    <a href="{{ route('admin.attendance.scanner') }}" x-show="match('Gatekeeper Scanner')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.attendance.scanner') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>Gatekeeper Scanner</span>
                    </a>
                    <a href="{{ route('admin.resignations.index') }}" x-show="match('Resignations')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.resignations.*') && !request()->routeIs('admin.resignations.history') && !request()->routeIs('admin.offboarding.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Resignations</span>
                    </a>
                    <a href="{{ route('admin.offboarding.index') }}" x-show="match('Offboarding Tasks')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.offboarding.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span>Offboarding Tasks</span>
                    </a>
                    <a href="{{ route('admin.documents.index') }}" x-show="match('Document Center')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.documents.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                        <span>Document Center</span>
                    </a>
                    <a href="{{ route('admin.audit-logs.index') }}" x-show="match('Audit Logs')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.audit-logs.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Audit Logs</span>
                    </a>
                    <a href="{{ route('admin.approval-chains.index') }}" x-show="match('Approval Workflows')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.approval-chains.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span>Approval Workflows</span>
                    </a>
                    <a href="{{ route('admin.ai.compliance') }}" x-show="match('AI Compliance')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.ai.compliance*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>AI Compliance</span>
                    </a>
                    <a href="{{ route('admin.ai.performance') }}" x-show="match('AI Performance')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.ai.performance*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>AI Performance</span>
                    </a>
                    <a href="{{ route('settings.index') }}" x-show="match('System Settings')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('settings.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>System Settings</span>
                    </a>
                </div>
            </div>
            @endrole

            {{-- Finance & Payroll --}}
            @role('Admin|HR')
            <div class="relative"
                 x-show="matchAny('Finance & Payroll', 'Salary Structures', 'Tax Brackets', 'Tax Reliefs', 'Overtime Policies', 'Payroll Process', 'Bonuses', 'Penalties', 'Loans', 'Advances', 'Payroll Reports')"
                 @click.outside="flyoutKey === 'finance' && closeFlyout()">
                <button type="button"
                        @click="openSection('finance', $event)"
                        @mouseenter="showTip($event, 'Finance & Payroll')"
                        @mouseleave="hideTip()"
                        :class="{ 'sidebar-nav-parent-active': activeMenu === 'finance' || flyoutKey === 'finance' }"
                        class="sidebar-nav-link group w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium focus:outline-none">
                    <svg class="flex-shrink-0 h-5 w-5 text-neutral-400 group-hover:text-neutral-700 dark:group-hover:text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1 text-left">Finance & Payroll</span>
                    <svg :class="{ 'rotate-90': activeMenu === 'finance' }" class="flex-shrink-0 h-3.5 w-3.5 text-neutral-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="sectionOpen('finance') && matchAny('Finance & Payroll', 'Salary Structures', 'Tax Brackets', 'Tax Reliefs', 'Overtime Policies', 'Payroll Process', 'Bonuses', 'Penalties', 'Loans', 'Advances', 'Payroll Reports')"
                     x-ref="flyout_finance"
                     x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     :class="isCollapsed() && flyoutKey === 'finance' ? 'sidebar-submenu sidebar-flyout space-y-0.5' : 'sidebar-submenu space-y-0.5 mt-1 mb-2'"
                     :style="isCollapsed() && flyoutKey === 'finance' ? flyoutStyle : {}">
                    <a href="{{ route('admin.salary.index') }}" x-show="match('Salary Structures')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.salary.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Salary Structures</span>
                    </a>
                    <a href="{{ route('admin.tax-brackets.index') }}" x-show="match('Tax Brackets')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.tax-brackets.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span>Tax Brackets</span>
                    </a>
                    <a href="{{ route('admin.tax-reliefs.index') }}" x-show="match('Tax Reliefs')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.tax-reliefs.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        <span>Tax Reliefs</span>
                    </a>
                    <a href="{{ route('admin.overtime-policies.index') }}" x-show="match('Overtime Policies')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.overtime-policies.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Overtime Policies</span>
                    </a>
                    <a href="{{ route('admin.payroll.index') }}" x-show="match('Payroll Process')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.payroll.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Payroll Process</span>
                    </a>
                    <a href="{{ route('admin.bonuses.index') }}" x-show="match('Bonuses')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.bonuses.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        <span>Bonuses</span>
                    </a>
                    <a href="{{ route('admin.penalties.index') }}" x-show="match('Penalties')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.penalties.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Penalties</span>
                    </a>
                    <a href="{{ route('admin.loans.index') }}" x-show="match('Loans')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.loans.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Loans</span>
                    </a>
                    <a href="{{ route('admin.advances.index') }}" x-show="match('Advances')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.advances.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span>Advances</span>
                    </a>
                    <a href="{{ route('admin.payroll-reports.index') }}" x-show="match('Payroll Reports')" class="sidebar-sub-link group flex items-center gap-2.5 px-2.5 py-1.5 {{ request()->routeIs('admin.payroll-reports.*') ? 'sidebar-sub-link-active' : '' }}">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Payroll Reports</span>
                    </a>
                </div>
            </div>
            @endrole

        </nav>
    </div>

    {{-- Footer --}}
    @if($authUser)
    <div class="sidebar-footer flex-shrink-0 border-t border-neutral-100 dark:border-zinc-800 px-3 pt-4 pb-5">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="sidebar-nav-link group w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-neutral-600 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-400"
                    @mouseenter="showTip($event, 'Logout')"
                    @mouseleave="hideTip()">
                <svg class="flex-shrink-0 h-5 w-5 text-neutral-400 group-hover:text-red-600 dark:group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
    @endif
</div>
