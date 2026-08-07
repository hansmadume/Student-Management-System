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
                    </div>

                    <p class="eyebrow">Student Management</p>
                    <h1>Welcome back</h1>
                    <p class="subtitle">
                        Sign in to manage students, departments, courses, and
                        reports.
                    </p>
                </div>

                <div v-if="status" class="status-banner">{{ status }}</div>

                <form method="POST" :action="loginUrl" class="auth-form">
                    <input type="hidden" name="_token" :value="csrfToken" />

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
                                autofocus
                                autocomplete="username"
                                placeholder="admin@example.com"
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
                                autocomplete="current-password"
                                placeholder="Enter your password"
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

                    <div class="form-row">
                        <label for="remember_me" class="remember-row">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                            />
                            <span>Remember me</span>
                        </label>

                        <a
                            v-if="passwordRequestUrl"
                            class="text-link"
                            :href="passwordRequestUrl"
                            >Forgot password?</a
                        >
                    </div>

                    <button type="submit" class="button primary submit-button">
                        <span>Log in</span>
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
                    New to the system?
                    <a :href="registerUrl">Create an account</a>
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
        default: "",
    },
    status: {
        type: String,
        default: "",
    },
    old: {
        type: Object,
        default: () => ({ email: "" }),
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<style scoped src="../../css/vue/LoginPage.css"></style>

