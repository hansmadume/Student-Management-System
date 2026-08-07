<template>
    <section class="dashboard-page" :class="themeClass">
        <div class="dashboard-shell">
            <header class="dashboard-header glass-panel">
                <div class="header-copy">
                    <div class="eyebrow-pill">
                        <span class="pulse-dot"></span>
                        Student Management
                    </div>
                    <h1>Dashboard</h1>
                    <p class="subtitle">
                        A modern overview of students, courses, departments, and
                        enrollment distribution.
                    </p>
                </div>

                <div class="header-actions">
                    <button
                        class="theme-toggle"
                        type="button"
                        :aria-label="`Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`"
                        @click="toggleTheme"
                    >
                        <span class="theme-toggle-track">
                            <span class="theme-toggle-thumb">
                                <span v-if="theme === 'dark'">â˜¾</span>
                                <span v-else>â˜€</span>
                            </span>
                        </span>
                        <span class="theme-toggle-label">{{
                            theme === "dark" ? "Dark" : "Light"
                        }}</span>
                    </button>

                    <a class="button secondary" :href="departmentsUrl">
                        <span>Manage Departments</span>
                    </a>
                    <a class="button primary" :href="studentsUrl">
                        <span>Manage Students</span>
                    </a>
                </div>
            </header>

            <section class="overview-grid">
                <article class="overview-card hero-card glass-card">
                    <div class="overview-copy">
                        <p class="overview-kicker">Campus snapshot</p>
                        <h2>Quick overview</h2>
                        <p>
                            Key enrollment and activity totals at a glance, so
                            the dashboard feels like a real control room instead
                            of a blank shell.
                        </p>
                    </div>

                    <div class="overview-primary-metric">
                        <strong>{{ getStatValue("Total Students") }}</strong>
                        <span>Total students</span>
                    </div>
                </article>

                <article class="overview-card metric-card glass-card">
                    <p class="overview-kicker">Gender split</p>
                    <div class="overview-metric-row">
                        <strong>{{ getStatValue("Male Students") }}</strong>
                        <span>Male</span>
                    </div>
                    <div class="overview-metric-row">
                        <strong>{{ getStatValue("Female Students") }}</strong>
                        <span>Female</span>
                    </div>
                </article>

                <article class="overview-card metric-card glass-card">
                    <p class="overview-kicker">Activity</p>
                    <div class="overview-metric-row">
                        <strong>{{
                            getStatValue("New Students This Month")
                        }}</strong>
                        <span>New this month</span>
                    </div>
                    <div class="overview-metric-row">
                        <strong>{{
                            getStatValue("Graduated Students")
                        }}</strong>
                        <span>Graduated</span>
                    </div>
                </article>
            </section>

            <div class="stats-grid">
                <article
                    v-for="stat in stats"
                    :key="stat.label"
                    class="stat-card glass-card"
                    :class="`accent-${stat.accent}`"
                >
                    <div class="stat-icon">
                        <span></span>
                    </div>
                    <div>
                        <div class="stat-label">{{ stat.label }}</div>
                        <div class="stat-value">{{ stat.value }}</div>
                    </div>
                </article>
            </div>

            <div class="charts-grid">
                <article class="chart-card glass-card">
                    <div class="card-header">
                        <h2>{{ charts.studentsPerCourse.title }}</h2>
                        <span class="card-badge">Courses</span>
                    </div>
                    <BarChart :items="charts.studentsPerCourse.items" />
                </article>

                <article class="chart-card glass-card">
                    <div class="card-header">
                        <h2>{{ charts.studentsByGender.title }}</h2>
                        <span class="card-badge">Gender</span>
                    </div>
                    <DonutChart :items="charts.studentsByGender.items" />
                </article>

                <article class="chart-card wide glass-card">
                    <div class="card-header">
                        <h2>{{ charts.studentsPerDepartment.title }}</h2>
                        <span class="card-badge">Departments</span>
                    </div>
                    <BarChart :items="charts.studentsPerDepartment.items" />
                </article>
            </div>

            <article class="recent-card glass-card">
                <div class="card-header">
                    <h2>Recently Added Students</h2>
                    <span class="card-badge"
                        >{{ recentStudents.length }} latest</span
                    >
                </div>

                <div v-if="recentStudents.length" class="recent-list">
                    <div
                        v-for="student in recentStudents"
                        :key="student.id"
                        class="recent-item"
                    >
                        <div class="student-avatar">
                            {{ initials(student.fullName) }}
                        </div>
                        <div class="student-meta">
                            <strong>{{ student.fullName }}</strong>
                            <p>
                                {{ student.student_id }} Â·
                                {{ student.courseName }}
                            </p>
                        </div>
                        <span class="recent-date">{{ student.createdAt }}</span>
                    </div>
                </div>

                <p v-else class="empty">No students found.</p>
            </article>
        </div>
    </section>
</template>

<script setup>
import { computed, onMounted, ref, watch } from "vue";

const props = defineProps({
    stats: {
        type: Array,
        default: () => [],
    },
    charts: {
        type: Object,
        default: () => ({
            studentsPerCourse: { title: "Students per Course", items: [] },
            studentsByGender: { title: "Students by Gender", items: [] },
            studentsPerDepartment: {
                title: "Students per Department",
                items: [],
            },
        }),
    },
    recentStudents: {
        type: Array,
        default: () => [],
    },
    studentsUrl: {
        type: String,
        required: true,
    },
    departmentsUrl: {
        type: String,
        required: true,
    },
});

function getStatValue(label) {
    const stat = props.stats.find((item) => item.label === label);

    return stat ? stat.value : 0;
}

function getInitialTheme() {
    const savedTheme = localStorage.getItem("dashboard-theme");

    if (savedTheme === "light" || savedTheme === "dark") {
        return savedTheme;
    }

    return window.matchMedia?.("(prefers-color-scheme: dark)").matches
        ? "dark"
        : "light";
}

const theme = ref(getInitialTheme());

const themeClass = computed(() => `theme-${theme.value}`);

onMounted(() => {
    document.documentElement.classList.toggle(
        "dashboard-dark",
        theme.value === "dark",
    );
    document.documentElement.classList.toggle(
        "dashboard-light",
        theme.value === "light",
    );
});

watch(theme, (value) => {
    localStorage.setItem("dashboard-theme", value);
    document.documentElement.classList.toggle(
        "dashboard-dark",
        value === "dark",
    );
    document.documentElement.classList.toggle(
        "dashboard-light",
        value === "light",
    );
});

function toggleTheme() {
    theme.value = theme.value === "dark" ? "light" : "dark";
}

function initials(name) {
    return String(name || "ST")
        .split(" ")
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part.charAt(0).toUpperCase())
        .join("");
}

const BarChart = {
    props: {
        items: {
            type: Array,
            default: () => [],
        },
    },
    setup(props) {
        const maxValue = computed(() =>
            Math.max(...props.items.map((item) => Number(item.value)), 1),
        );

        function width(value) {
            return `${(Number(value) / maxValue.value) * 100}%`;
        }

        return {
            width,
        };
    },
    template: `
        <div v-if="items.length" class="bar-chart">
            <div v-for="item in items" :key="item.label" class="bar-row">
                <div class="bar-meta">
                    <span>{{ item.label }}</span>
                    <strong>{{ item.value }}</strong>
                </div>
                <div class="bar-track">
                    <div class="bar-fill" :style="{ width: width(item.value) }"></div>
                </div>
            </div>
        </div>
        <p v-else class="empty">No chart data available.</p>
    `,
};

const DonutChart = {
    props: {
        items: {
            type: Array,
            default: () => [],
        },
    },
    setup(props) {
        const colors = [
            "#ef4444",
            "#b91c1c",
            "#f97316",
            "#7f1d1d",
            "#dc2626",
            "#fb7185",
        ];

        const total = computed(() =>
            props.items.reduce((sum, item) => sum + Number(item.value), 0),
        );

        function color(index) {
            return colors[index % colors.length];
        }

        function percentage(value) {
            if (!total.value) {
                return 0;
            }

            return Math.round((Number(value) / total.value) * 100);
        }

        return {
            color,
            percentage,
            total,
        };
    },
    template: `
        <div v-if="items.length" class="breakdown">
            <div class="breakdown-summary">
                <strong>{{ total }}</strong>
                <span>Total students</span>
            </div>

            <div class="legend">
                <div v-for="(item, index) in items" :key="item.label" class="legend-item">
                    <div class="legend-head">
                        <span class="legend-dot" :style="{ backgroundColor: color(index) }"></span>
                        <span>{{ item.label }}</span>
                    </div>
                    <div class="legend-track">
                        <div class="legend-fill" :style="{ width: percentage(item.value) + '%', backgroundColor: color(index) }"></div>
                    </div>
                    <strong>{{ item.value }} ({{ percentage(item.value) }}%)</strong>
                </div>
            </div>
        </div>
        <p v-else class="empty">No chart data available.</p>
    `,
};
</script>

<style scoped src="../../css/vue/Dashboard.css"></style>
