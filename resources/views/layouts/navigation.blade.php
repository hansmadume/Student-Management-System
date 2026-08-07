<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-zinc-200/70 bg-white/80 shadow-sm shadow-zinc-950/5 backdrop-blur-xl supports-[backdrop-filter]:bg-white/70">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <!-- Logo / Brand -->
                <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-2xl bg-zinc-950 text-white shadow-lg shadow-zinc-950/15 ring-1 ring-zinc-900/10 transition duration-200 group-hover:-translate-y-0.5 group-hover:shadow-xl group-hover:shadow-blue-500/20">
                        <x-application-logo class="h-6 w-6 fill-current" />
                    </span>
                    <span class="hidden leading-tight sm:block">
                        <span class="block text-sm font-extrabold tracking-tight text-zinc-950">Student Management</span>
                        <span class="block text-xs font-medium text-zinc-500">Admin Portal</span>
                    </span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden items-center rounded-full border border-zinc-200 bg-zinc-50/80 p-1 shadow-inner shadow-zinc-950/5 sm:flex">
                    <a
                        href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'bg-white text-zinc-950 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-950' }} inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition duration-200"
                    >
                        Dashboard
                    </a>

                    <a
                        href="{{ route('students.index') }}"
                        class="{{ request()->routeIs('students.*') ? 'bg-white text-zinc-950 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-950' }} inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition duration-200"
                    >
                        Students
                    </a>

                    <a
                        href="{{ route('departments.index') }}"
                        class="{{ request()->routeIs('departments.*') ? 'bg-white text-zinc-950 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-950' }} inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition duration-200"
                    >
                        Departments
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden items-center gap-3 sm:flex">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 rounded-full border border-zinc-200 bg-white px-2 py-2 pr-3 text-sm font-semibold text-zinc-700 shadow-sm shadow-zinc-950/5 transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:text-zinc-950 hover:shadow-md hover:shadow-blue-500/10 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <span class="grid h-8 w-8 place-items-center rounded-full bg-gradient-to-br from-blue-600 to-zinc-950 text-xs font-extrabold text-white">
                                {{ collect(explode(' ', Auth::user()->name))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->join('') }}
                            </span>

                            <span class="max-w-40 truncate">{{ Auth::user()->name }}</span>

                            <svg class="h-4 w-4 text-zinc-400 transition duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-xl border border-zinc-200 bg-white p-2 text-zinc-500 shadow-sm transition duration-200 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden border-t border-zinc-200/70 bg-white/95 backdrop-blur-xl sm:hidden">
        <div class="space-y-1 px-4 py-3">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-950' }} block rounded-2xl px-4 py-3 text-sm font-semibold transition">
                Dashboard
            </a>

            <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-950' }} block rounded-2xl px-4 py-3 text-sm font-semibold transition">
                Students
            </a>

            <a href="{{ route('departments.index') }}" class="{{ request()->routeIs('departments.*') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-950' }} block rounded-2xl px-4 py-3 text-sm font-semibold transition">
                Departments
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-zinc-200 px-4 py-4">
            <div class="flex items-center gap-3 rounded-2xl bg-zinc-50 p-3 ring-1 ring-zinc-200">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-gradient-to-br from-blue-600 to-zinc-950 text-sm font-extrabold text-white">
                    {{ collect(explode(' ', Auth::user()->name))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->join('') }}
                </span>
                <div class="min-w-0">
                    <div class="truncate text-sm font-bold text-zinc-950">{{ Auth::user()->name }}</div>
                    <div class="truncate text-xs font-medium text-zinc-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold text-zinc-600 transition hover:bg-zinc-50 hover:text-zinc-950">
                    {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="block rounded-2xl px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>