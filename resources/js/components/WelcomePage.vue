<template>
    <main class="relative min-h-screen overflow-hidden bg-slate-950 text-white">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(79,70,229,0.35),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.28),_transparent_34%)]">
        </div>
        <div class="absolute left-8 top-10 h-40 w-40 rounded-full bg-indigo-500/20 blur-3xl"></div>
        <div class="absolute bottom-10 right-8 h-52 w-52 rounded-full bg-sky-400/20 blur-3xl"></div>

        <section class="relative mx-auto flex min-h-screen w-full max-w-7xl flex-col px-6 py-8 lg:px-8">
            <header class="flex items-center justify-between">
                <a href="/" class="inline-flex items-center gap-3">
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-sky-500 text-white shadow-lg shadow-indigo-950/30">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3L3 8l9 5 9-5-9-5Z" stroke="currentColor" stroke-width="1.8"
                                stroke-linejoin="round" />
                            <path d="M5 10.5V16c0 1.657 3.134 3 7 3s7-1.343 7-3v-5.5"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </span>
                    <span class="text-lg font-bold tracking-tight">Student Management</span>
                </a>
            </header>

            <div class="grid flex-1 items-center gap-12 py-16 lg:grid-cols-[1.05fr_0.95fr]">
                <div>
                    <div
                        class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm font-semibold text-sky-100 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Smart school records platform
                    </div>

                    <h1 class="max-w-3xl text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-7xl">
                        Manage students with clarity and speed.
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                        A clean dashboard for tracking student profiles, departments, courses, enrollments, and key
                        academic insights in one place.
                    </p>

                    <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                        <a v-if="isAuthenticated" :href="dashboardUrl"
                            class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-600 to-sky-500 px-7 py-4 text-sm font-bold text-white shadow-xl shadow-indigo-950/30 transition hover:-translate-y-0.5">
                            Go to dashboard
                        </a>

                        <template v-else>
                            <a :href="loginUrl"
                                class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-600 to-sky-500 px-7 py-4 text-sm font-bold text-white shadow-xl shadow-indigo-950/30 transition hover:-translate-y-0.5">
                                Sign in
                            </a>

                            <a v-if="registerUrl" :href="registerUrl"
                                class="inline-flex items-center justify-center rounded-2xl border border-white/15 bg-white/10 px-7 py-4 text-sm font-bold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/15">
                                Create account
                            </a>
                        </template>
                    </div>

                    <div class="mt-12 grid max-w-xl grid-cols-3 gap-4">
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-2xl font-extrabold">360°</p>
                            <p class="mt-1 text-xs font-medium text-slate-300">Student records</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-2xl font-extrabold">Live</p>
                            <p class="mt-1 text-xs font-medium text-slate-300">Dashboard data</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-2xl font-extrabold">Easy</p>
                            <p class="mt-1 text-xs font-medium text-slate-300">Course tracking</p>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -inset-6 rounded-[2.5rem] bg-gradient-to-br from-indigo-500/25 to-sky-400/25 blur-2xl">
                    </div>
                    <div
                        class="relative overflow-hidden rounded-[2rem] border border-white/15 bg-white/95 p-6 text-slate-900 shadow-2xl shadow-slate-950/40">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
                            <div>
                                <p class="text-sm font-semibold text-slate-500">Overview</p>
                                <h2 class="mt-1 text-2xl font-extrabold">Student Dashboard</h2>
                            </div>
                            <div class="rounded-2xl bg-indigo-50 p-3 text-indigo-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M4 13h6V5H4v8Zm10 6h6V5h-6v14ZM4 19h6v-3H4v3Z"
                                        stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm font-semibold text-slate-500">Students</p>
                                <p class="mt-3 text-3xl font-extrabold">1,248</p>
                                <p class="mt-2 text-sm font-semibold text-emerald-600">+12% this month</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-sm font-semibold text-slate-500">Courses</p>
                                <p class="mt-3 text-3xl font-extrabold">32</p>
                                <p class="mt-2 text-sm font-semibold text-indigo-600">Active programs</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl bg-slate-50 p-5">
                            <div class="flex items-center justify-between">
                                <p class="font-bold">Recent students</p>
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">Updated</span>
                            </div>

                            <div class="mt-5 space-y-4">
                                <div v-for="student in recentStudents" :key="student.initials" class="flex items-center gap-3">
                                    <span
                                        :class="['flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold', student.avatarClass]">
                                        {{ student.initials }}
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold">{{ student.name }}</p>
                                        <p class="text-xs text-slate-500">{{ student.course }}</p>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-400">{{ student.status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script setup>
defineProps({
    isAuthenticated: {
        type: Boolean,
        default: false,
    },
    loginUrl: {
        type: String,
        required: true,
    },
    registerUrl: {
        type: String,
        default: '',
    },
    dashboardUrl: {
        type: String,
        required: true,
    },
});

const recentStudents = [
    {
        initials: 'JD',
        name: 'Juan Dela Cruz',
        course: 'Computer Science',
        status: 'New',
        avatarClass: 'bg-indigo-100 text-indigo-700',
    },
    {
        initials: 'MS',
        name: 'Maria Santos',
        course: 'Information Technology',
        status: 'Today',
        avatarClass: 'bg-sky-100 text-sky-700',
    },
    {
        initials: 'AR',
        name: 'Alex Reyes',
        course: 'Business Administration',
        status: 'Today',
        avatarClass: 'bg-violet-100 text-violet-700',
    },
];
</script>