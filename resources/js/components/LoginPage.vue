<template>
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 px-4 py-10">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(79,70,229,0.35),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.28),_transparent_34%)]">
        </div>
        <div class="absolute left-10 top-10 h-28 w-28 rounded-full bg-indigo-500/20 blur-3xl"></div>
        <div class="absolute bottom-10 right-10 h-36 w-36 rounded-full bg-sky-400/20 blur-3xl"></div>

        <div class="relative w-full max-w-md">
            <div class="mb-6 flex justify-center">
                <a href="/"
                    class="inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/10 px-5 py-3 text-sm font-semibold text-white shadow-2xl backdrop-blur transition hover:bg-white/15">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-sky-500">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3L3 8l9 5 9-5-9-5Z" stroke="currentColor" stroke-width="1.8"
                                stroke-linejoin="round" />
                            <path d="M5 10.5V16c0 1.657 3.134 3 7 3s7-1.343 7-3v-5.5"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </span>
                    <span>Student Management</span>
                </a>
            </div>

            <div
                class="overflow-hidden rounded-[2rem] border border-white/20 bg-white/95 px-6 py-8 text-slate-900 shadow-2xl shadow-indigo-950/40 backdrop-blur sm:px-8">
                <div class="space-y-8">
                    <div class="text-center">
                        <div
                            class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-sky-500 text-white shadow-lg shadow-indigo-200">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 3L3 8l9 5 9-5-9-5Z" stroke="currentColor" stroke-width="1.8"
                                    stroke-linejoin="round" />
                                <path d="M5 10.5V16c0 1.657 3.134 3 7 3s7-1.343 7-3v-5.5"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                        </div>

                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-indigo-600">Student Management</p>
                        <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Welcome back</h1>
                        <p class="mt-2 text-sm text-slate-500">
                            Sign in to manage students, departments, courses, and reports.
                        </p>
                    </div>

                    <div v-if="status"
                        class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ status }}
                    </div>

                    <form method="POST" :action="loginUrl" class="space-y-5">
                        <input type="hidden" name="_token" :value="csrfToken">

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email address</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M4 6.5h16v11H4v-11Z" stroke="currentColor" stroke-width="1.8"
                                            stroke-linejoin="round" />
                                        <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <input id="email" type="email" name="email" :value="old.email" required autofocus
                                    autocomplete="username" placeholder="admin@example.com"
                                    class="block w-full rounded-2xl border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500" />
                            </div>
                            <p v-for="message in errors.email || []" :key="message" class="mt-2 text-sm text-red-600">
                                {{ message }}
                            </p>
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M7 10V8a5 5 0 0 1 10 0v2" stroke="currentColor" stroke-width="1.8"
                                            stroke-linecap="round" />
                                        <path d="M6 10h12v10H6V10Z" stroke="currentColor" stroke-width="1.8"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <input id="password" type="password" name="password" required
                                    autocomplete="current-password" placeholder="Enter your password"
                                    class="block w-full rounded-2xl border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-indigo-500" />
                            </div>
                            <p v-for="message in errors.password || []" :key="message" class="mt-2 text-sm text-red-600">
                                {{ message }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                                <input id="remember_me" type="checkbox"
                                    class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    name="remember">
                                <span>Remember me</span>
                            </label>

                            <a v-if="passwordRequestUrl" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800"
                                :href="passwordRequestUrl">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit"
                            class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-sky-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Log in
                            <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </form>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-center text-sm text-slate-600">
                        New to the system?
                        <a :href="registerUrl" class="font-bold text-indigo-600 transition hover:text-indigo-800">
                            Create an account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    csrfToken: {
        type: String,
        required: true,
    },
    loginUrl: {
        type: String,
        required: true,
    },
    registerUrl: {
        type: String,
        required: true,
    },
    passwordRequestUrl: {
        type: String,
        default: '',
    },
    status: {
        type: String,
        default: '',
    },
    old: {
        type: Object,
        default: () => ({ email: '' }),
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});
</script>