<template>
    <main class="auth-page">
        <section class="auth-shell">
            <div class="auth-brand-wrap">
                <a href="/" class="brand-link">
                    <span class="brand-mark">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path
                                d="M12 3L3 8l9 5 9-5-9-5Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M5 10.5V16c0 1.657 3.134 3 7 3s7-1.343 7-3v-5.5"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>
                    </span>
                    <span>Student Management</span>
                </a>
            </div>

            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-icon">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path
                                d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                            <path
                                d="M9.5 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />
                            <path
                                d="M19 8v6"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                            <path
                                d="M22 11h-6"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <p class="eyebrow">Student Management</p>
                    <h1>Create account</h1>
                    <p class="subtitle">
                        Register to access student records, departments,
                        courses, and analytics.
                    </p>
                </div>

                <form method="POST" :action="registerUrl" class="auth-form">
                    <input type="hidden" name="_token" :value="csrfToken" />

                    <label class="field">
                        <span>Full name</span>
                        <div class="field-control">
                            <svg
                                class="field-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path
                                    d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />
                                <path
                                    d="M4 21a8 8 0 0 1 16 0"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                :value="old.name"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Juan Dela Cruz"
                            />
                        </div>
                        <p
                            v-for="message in errors.name || []"
                            :key="message"
                            class="field-error"
                        >
                            {{ message }}
                        </p>
                    </label>

                    <label class="field">
                        <span>Email address</span>
                        <div class="field-control">
                            <svg
                                class="field-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path
                                    d="M4 6.5h16v11H4v-11Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="m4 7 8 6 8-6"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                :value="old.email"
                                required
                                autocomplete="username"
                                placeholder="name@example.com"
                            />
                        </div>
                        <p
                            v-for="message in errors.email || []"
                            :key="message"
                            class="field-error"
                        >
                            {{ message }}
                        </p>
                    </label>

                    <label class="field">
                        <span>Password</span>
                        <div class="field-control">
                            <svg
                                class="field-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path
                                    d="M7 10V8a5 5 0 0 1 10 0v2"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M6 10h12v10H6V10Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Create a secure password"
                            />
                        </div>
                        <p
                            v-for="message in errors.password || []"
                            :key="message"
                            class="field-error"
                        >
                            {{ message }}
                        </p>
                    </label>

                    <label class="field">
                        <span>Confirm password</span>
                        <div class="field-control">
                            <svg
                                class="field-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path
                                    d="M9 12.5 11 14.5 15.5 10"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M6 10h12v10H6V10Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M8 10V8a4 4 0 0 1 8 0v2"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Repeat your password"
                            />
                        </div>
                        <p
                            v-for="message in errors.password_confirmation ||
                            []"
                            :key="message"
                            class="field-error"
                        >
                            {{ message }}
                        </p>
                    </label>

                    <button type="submit" class="button primary submit-button">
                        <span>Register</span>
                        <svg
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </form>

                <div class="auth-footer">
                    Already have an account?
                    <a :href="loginUrl">Log in</a>
                </div>
            </div>
        </section>
    </main>
</template>

<script setup>
defineProps({
    csrfToken: {
        type: String,
        required: true,
    },
    registerUrl: {
        type: String,
        required: true,
    },
    loginUrl: {
        type: String,
        required: true,
    },
    old: {
        type: Object,
        default: () => ({ name: "", email: "" }),
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<style scoped src="../../css/vue/RegisterPage.css"></style>

