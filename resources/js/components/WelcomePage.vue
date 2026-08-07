<template>
    <main class="welcome-page">
        <section class="welcome-shell">
            <header class="welcome-header">
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
            </header>

            <div class="hero-grid">
                <div class="hero-copy">
                    <div class="eyebrow-pill">
                        <span class="pulse-dot"></span>
                        Smart school records platform
                    </div>

                    <h1>Manage students with clarity and speed.</h1>

                    <p class="hero-text">
                        A clean dashboard for tracking student profiles,
                        departments, courses, enrollments, and key academic
                        insights in one place.
                    </p>

                    <div class="hero-actions">
                        <a
                            v-if="isAuthenticated"
                            :href="dashboardUrl"
                            class="button primary"
                            >Go to dashboard</a
                        >

                        <template v-else>
                            <a :href="loginUrl" class="button primary"
                                >Sign in</a
                            >

                            <a
                                v-if="registerUrl"
                                :href="registerUrl"
                                class="button secondary"
                                >Create account</a
                            >
                        </template>
                    </div>

                    <div class="metric-grid">
                        <div class="metric-card">
                            <p class="metric-value">360Â°</p>
                            <p class="metric-label">Student records</p>
                        </div>
                        <div class="metric-card">
                            <p class="metric-value">Live</p>
                            <p class="metric-label">Dashboard data</p>
                        </div>
                        <div class="metric-card">
                            <p class="metric-value">Easy</p>
                            <p class="metric-label">Course tracking</p>
                        </div>
                    </div>
                </div>

                <div class="preview-card">
                    <div class="preview-header">
                        <div>
                            <p class="preview-kicker">Overview</p>
                            <h2>Student Dashboard</h2>
                        </div>
                        <div class="preview-icon">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path
                                    d="M4 13h6V5H4v8Zm10 6h6V5h-6v14ZM4 19h6v-3H4v3Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>
                    </div>

                    <div class="preview-stats">
                        <div class="preview-stat">
                            <p class="preview-label">Students</p>
                            <p class="preview-value">1,248</p>
                            <p class="preview-note">+12% this month</p>
                        </div>
                        <div class="preview-stat">
                            <p class="preview-label">Courses</p>
                            <p class="preview-value">32</p>
                            <p class="preview-note">Active programs</p>
                        </div>
                    </div>

                    <div class="recent-panel">
                        <div class="recent-panel-header">
                            <p>Recent students</p>
                            <span>Updated</span>
                        </div>

                        <div class="recent-list">
                            <div
                                v-for="student in recentStudents"
                                :key="student.initials"
                                class="recent-item"
                            >
                                <span
                                    :class="[
                                        'recent-avatar',
                                        student.avatarClass,
                                    ]"
                                    >{{ student.initials }}</span
                                >
                                <div class="recent-copy">
                                    <p>{{ student.name }}</p>
                                    <span>{{ student.course }}</span>
                                </div>
                                <span class="recent-status">{{
                                    student.status
                                }}</span>
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
        default: "",
    },
    dashboardUrl: {
        type: String,
        required: true,
    },
});

const recentStudents = [
    {
        initials: "JD",
        name: "Juan Dela Cruz",
        course: "Computer Science",
        status: "New",
        avatarClass: "avatar-red",
    },
    {
        initials: "MS",
        name: "Maria Santos",
        course: "Information Technology",
        status: "Today",
        avatarClass: "avatar-rose",
    },
    {
        initials: "AR",
        name: "Alex Reyes",
        course: "Business Administration",
        status: "Today",
        avatarClass: "avatar-dark",
    },
];
</script>

<style scoped src="../../css/vue/WelcomePage.css"></style>

