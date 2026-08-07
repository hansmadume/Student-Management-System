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
                    <p class="subtitle">A modern overview of students, courses, departments, and enrollment distribution.</p>
                </div>

                <div class="header-actions">
                    <button class="theme-toggle" type="button" :aria-label="`Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`" @click="toggleTheme">
                        <span class="theme-toggle-track">
                            <span class="theme-toggle-thumb">
                                <span v-if="theme === 'dark'">☾</span>
                                <span v-else>☀</span>
                            </span>
                        </span>
                        <span class="theme-toggle-label">{{ theme === 'dark' ? 'Dark' : 'Light' }}</span>
                    </button>

                    <a class="button secondary" :href="departmentsUrl">
                        <span>Manage Departments</span>
                    </a>
                    <a class="button primary" :href="studentsUrl">
                        <span>Manage Students</span>
                    </a>
                </div>
            </header>

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
                    <span class="card-badge">{{ recentStudents.length }} latest</span>
                </div>

                <div v-if="recentStudents.length" class="recent-list">
                    <div v-for="student in recentStudents" :key="student.id" class="recent-item">
                        <div class="student-avatar">
                            {{ initials(student.fullName) }}
                        </div>
                        <div class="student-meta">
                            <strong>{{ student.fullName }}</strong>
                            <p>{{ student.student_id }} · {{ student.courseName }}</p>
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
import { computed, onMounted, ref, watch } from 'vue';

defineProps({
    stats: {
        type: Array,
        default: () => [],
    },
    charts: {
        type: Object,
        required: true,
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

function getInitialTheme() {
    const savedTheme = localStorage.getItem('dashboard-theme');

    if (savedTheme === 'light' || savedTheme === 'dark') {
        return savedTheme;
    }

    return window.matchMedia?.('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

const theme = ref(getInitialTheme());

const themeClass = computed(() => `theme-${theme.value}`);

onMounted(() => {
    document.documentElement.classList.toggle('dashboard-dark', theme.value === 'dark');
    document.documentElement.classList.toggle('dashboard-light', theme.value === 'light');
});

watch(theme, (value) => {
    localStorage.setItem('dashboard-theme', value);
    document.documentElement.classList.toggle('dashboard-dark', value === 'dark');
    document.documentElement.classList.toggle('dashboard-light', value === 'light');
});

function toggleTheme() {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
}

function initials(name) {
    return String(name || 'ST')
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part.charAt(0).toUpperCase())
        .join('');
}

const BarChart = {
    props: {
        items: {
            type: Array,
            default: () => [],
        },
    },
    setup(props) {
        const maxValue = computed(() => Math.max(...props.items.map((item) => Number(item.value)), 1));

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
        const colors = ['#2563eb', '#18181b', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4'];

        const total = computed(() => props.items.reduce((sum, item) => sum + Number(item.value), 0));

        const gradient = computed(() => {
            if (!total.value) {
                return 'rgba(113, 113, 122, .18) 0deg 360deg';
            }

            let current = 0;

            return props.items.map((item, index) => {
                const start = current;
                const degrees = (Number(item.value) / total.value) * 360;
                current += degrees;

                return `${colors[index % colors.length]} ${start}deg ${current}deg`;
            }).join(', ');
        });

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
            gradient,
            percentage,
            total,
        };
    },
    template: `
        <div v-if="items.length" class="donut-wrap">
            <div class="donut" :style="{ background: 'conic-gradient(' + gradient + ')' }">
                <div class="donut-center">
                    <strong>{{ total }}</strong>
                    <span>Students</span>
                </div>
            </div>

            <div class="legend">
                <div v-for="(item, index) in items" :key="item.label" class="legend-item">
                    <span class="legend-dot" :style="{ backgroundColor: color(index) }"></span>
                    <span>{{ item.label }}</span>
                    <strong>{{ item.value }} ({{ percentage(item.value) }}%)</strong>
                </div>
            </div>
        </div>
        <p v-else class="empty">No chart data available.</p>
    `,
};
</script>

<style scoped>
.dashboard-page {
    --background: #ffffff;
    --card: rgba(250, 250, 250, .82);
    --card-solid: #fafafa;
    --primary: #18181b;
    --accent: #2563eb;
    --muted: #71717a;
    --border: rgba(24, 24, 27, .10);
    --ring: rgba(37, 99, 235, .22);
    --shadow: 0 24px 80px rgba(24, 24, 27, .10);
    --soft-shadow: 0 16px 40px rgba(24, 24, 27, .08);
    --track: rgba(24, 24, 27, .08);

    min-height: calc(100vh - 64px);
    margin: -3rem 0 0;
    padding: 32px 18px 56px;
    color: var(--primary);
    background:
        radial-gradient(circle at 12% 8%, rgba(37, 99, 235, .14), transparent 30%),
        radial-gradient(circle at 88% 0%, rgba(59, 130, 246, .10), transparent 28%),
        linear-gradient(180deg, var(--background), var(--background));
    font-feature-settings: "cv02", "cv03", "cv04", "cv11";
    transition: background .28s ease, color .28s ease;
}

.dashboard-page.theme-dark {
    --background: #09090b;
    --card: rgba(24, 24, 27, .76);
    --card-solid: #18181b;
    --primary: #fafafa;
    --accent: #3b82f6;
    --muted: #a1a1aa;
    --border: rgba(250, 250, 250, .10);
    --ring: rgba(59, 130, 246, .32);
    --shadow: 0 24px 90px rgba(0, 0, 0, .34);
    --soft-shadow: 0 18px 52px rgba(0, 0, 0, .28);
    --track: rgba(250, 250, 250, .08);

    background:
        radial-gradient(circle at 12% 8%, rgba(59, 130, 246, .17), transparent 30%),
        radial-gradient(circle at 88% 0%, rgba(59, 130, 246, .11), transparent 28%),
        linear-gradient(180deg, var(--background), var(--background));
}

.dashboard-shell {
    width: min(1240px, 100%);
    margin: 0 auto;
}

.glass-panel,
.glass-card {
    background: var(--card);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    backdrop-filter: blur(22px) saturate(180%);
    -webkit-backdrop-filter: blur(22px) saturate(180%);
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 22px;
    padding: 28px;
    border-radius: 28px;
    overflow: hidden;
    position: relative;
}

.dashboard-header::after {
    content: "";
    position: absolute;
    inset: auto -12% -54% 48%;
    height: 180px;
    background: radial-gradient(circle, rgba(37, 99, 235, .22), transparent 65%);
    pointer-events: none;
}

.header-copy {
    position: relative;
    z-index: 1;
}

.eyebrow-pill {
    display: inline-flex;
    gap: 9px;
    align-items: center;
    margin-bottom: 12px;
    padding: 7px 11px;
    color: var(--accent);
    background: rgba(37, 99, 235, .10);
    border: 1px solid rgba(37, 99, 235, .18);
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    background: var(--accent);
    border-radius: 999px;
    box-shadow: 0 0 0 6px rgba(37, 99, 235, .14);
}

h1,
h2,
p {
    margin: 0;
}

h1 {
    color: var(--primary);
    font-size: clamp(34px, 5vw, 56px);
    font-weight: 800;
    letter-spacing: -.055em;
    line-height: .95;
}

h2 {
    color: var(--primary);
    font-size: 18px;
    font-weight: 750;
    letter-spacing: -.025em;
}

.subtitle {
    max-width: 650px;
    margin-top: 12px;
    color: var(--muted);
    font-size: 15px;
    line-height: 1.7;
}

.header-actions {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 44px;
    padding: 10px 16px;
    text-decoration: none;
    border: 1px solid transparent;
    border-radius: 14px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 700;
    transition: transform .22s ease, box-shadow .22s ease, background .22s ease, border-color .22s ease;
}

.button:hover {
    transform: translateY(-2px);
}

.primary {
    color: #ffffff;
    background: var(--accent);
    box-shadow: 0 14px 28px rgba(37, 99, 235, .24);
}

.secondary {
    color: var(--primary);
    background: rgba(255, 255, 255, .58);
    border-color: var(--border);
}

.theme-toggle {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-height: 44px;
    padding: 7px 12px 7px 8px;
    color: var(--primary);
    background: rgba(255, 255, 255, .58);
    border: 1px solid var(--border);
    border-radius: 999px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 750;
    box-shadow: 0 12px 26px rgba(24, 24, 27, .08);
    transition: transform .22s ease, background .22s ease, border-color .22s ease, box-shadow .22s ease;
}

.theme-toggle:hover {
    transform: translateY(-2px);
    border-color: var(--ring);
    box-shadow: 0 18px 38px rgba(37, 99, 235, .14);
}

.theme-toggle-track {
    position: relative;
    display: inline-flex;
    width: 48px;
    height: 28px;
    padding: 3px;
    background: var(--primary);
    border-radius: 999px;
    transition: background .24s ease;
}

.theme-toggle-thumb {
    display: grid;
    place-items: center;
    width: 22px;
    height: 22px;
    color: var(--primary);
    background: var(--card-solid);
    border-radius: 999px;
    font-size: 12px;
    line-height: 1;
    transform: translateX(0);
    transition: transform .24s ease, background .24s ease, color .24s ease;
}

.theme-dark .theme-toggle-thumb {
    transform: translateX(20px);
}

.theme-toggle-label {
    min-width: 34px;
    text-align: left;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.stat-card {
    position: relative;
    display: flex;
    gap: 16px;
    align-items: flex-start;
    min-height: 142px;
    overflow: hidden;
    padding: 20px;
    border-radius: 24px;
    box-shadow: var(--soft-shadow);
    transition: transform .24s ease, border-color .24s ease, box-shadow .24s ease;
}

.stat-card:hover,
.chart-card:hover,
.recent-card:hover {
    transform: translateY(-3px);
    border-color: rgba(37, 99, 235, .28);
    box-shadow: 0 24px 70px rgba(24, 24, 27, .13);
}

.stat-card::after {
    content: "";
    position: absolute;
    right: -38px;
    bottom: -46px;
    width: 132px;
    height: 132px;
    background: var(--accent-color, var(--accent));
    border-radius: 50%;
    opacity: .11;
}

.stat-icon {
    display: grid;
    place-items: center;
    width: 42px;
    height: 42px;
    flex: 0 0 auto;
    background: color-mix(in srgb, var(--accent-color, var(--accent)) 14%, transparent);
    border: 1px solid color-mix(in srgb, var(--accent-color, var(--accent)) 24%, transparent);
    border-radius: 16px;
}

.stat-icon span {
    width: 16px;
    height: 16px;
    background: var(--accent-color, var(--accent));
    border-radius: 6px;
    box-shadow: 0 0 0 6px color-mix(in srgb, var(--accent-color, var(--accent)) 12%, transparent);
}

.stat-label {
    color: var(--muted);
    font-size: 12px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .06em;
}

.stat-value {
    margin-top: 8px;
    color: var(--primary);
    font-size: clamp(32px, 4vw, 44px);
    font-weight: 850;
    letter-spacing: -.055em;
    line-height: 1;
}

.accent-blue {
    --accent-color: #2563eb;
}

.accent-indigo {
    --accent-color: #4f46e5;
}

.accent-pink {
    --accent-color: #ec4899;
}

.accent-emerald {
    --accent-color: #10b981;
}

.accent-amber {
    --accent-color: #f59e0b;
}

.accent-cyan {
    --accent-color: #06b6d4;
}

.accent-purple {
    --accent-color: #8b5cf6;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.chart-card,
.recent-card {
    padding: 22px;
    border-radius: 24px;
    box-shadow: var(--soft-shadow);
    transition: transform .24s ease, border-color .24s ease, box-shadow .24s ease;
}

.wide {
    grid-column: 1 / -1;
}

.card-header {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: center;
    margin-bottom: 18px;
}

.card-badge {
    padding: 6px 10px;
    color: var(--accent);
    background: rgba(37, 99, 235, .10);
    border: 1px solid rgba(37, 99, 235, .16);
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.bar-chart {
    display: grid;
    gap: 15px;
}

.bar-meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 8px;
    color: var(--primary);
    font-size: 14px;
}

.bar-meta span {
    color: var(--muted);
}

.bar-track {
    height: 12px;
    overflow: hidden;
    background: var(--track);
    border: 1px solid var(--border);
    border-radius: 999px;
}

.bar-fill {
    height: 100%;
    min-width: 8px;
    background: linear-gradient(90deg, var(--accent), #60a5fa);
    border-radius: inherit;
    box-shadow: 0 0 24px rgba(37, 99, 235, .32);
    transition: width .5s ease;
}

.donut-wrap {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 22px;
    align-items: center;
}

.donut {
    display: grid;
    place-items: center;
    width: 220px;
    height: 220px;
    border: 1px solid var(--border);
    border-radius: 50%;
    box-shadow: inset 0 0 32px rgba(37, 99, 235, .08);
}

.donut-center {
    display: grid;
    place-items: center;
    width: 128px;
    height: 128px;
    background: var(--card-solid);
    border: 1px solid var(--border);
    border-radius: 50%;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .10), 0 18px 40px rgba(24, 24, 27, .10);
}

.donut-center strong {
    color: var(--primary);
    font-size: 32px;
    font-weight: 850;
    letter-spacing: -.04em;
}

.donut-center span {
    color: var(--muted);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
}

.legend {
    display: grid;
    gap: 10px;
}

.legend-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 10px;
    align-items: center;
    padding: 10px;
    color: var(--primary);
    background: rgba(255, 255, 255, .46);
    border: 1px solid var(--border);
    border-radius: 14px;
}

.legend-item span:not(.legend-dot) {
    color: var(--muted);
}

.legend-dot {
    width: 11px;
    height: 11px;
    border-radius: 999px;
}

.recent-list {
    display: grid;
    gap: 10px;
}

.recent-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 12px;
    align-items: center;
    padding: 14px;
    background: rgba(255, 255, 255, .50);
    border: 1px solid var(--border);
    border-radius: 18px;
    transition: transform .2s ease, background .2s ease, border-color .2s ease;
}

.recent-item:hover {
    transform: translateX(3px);
    background: rgba(255, 255, 255, .72);
    border-color: var(--ring);
}

.student-avatar {
    display: grid;
    place-items: center;
    width: 42px;
    height: 42px;
    color: #ffffff;
    background: var(--primary);
    border-radius: 15px;
    font-size: 13px;
    font-weight: 800;
}

.student-meta {
    min-width: 0;
}

.student-meta strong {
    display: block;
    color: var(--primary);
    font-weight: 750;
}

.student-meta p,
.recent-date,
.empty {
    color: var(--muted);
}

.student-meta p {
    margin-top: 3px;
    font-size: 13px;
}

.recent-date {
    font-size: 13px;
    font-weight: 650;
    white-space: nowrap;
}

.empty {
    padding: 18px;
    background: rgba(255, 255, 255, .48);
    border: 1px dashed var(--border);
    border-radius: 18px;
    text-align: center;
}

.theme-dark .secondary,
.theme-dark .theme-toggle,
.theme-dark .legend-item,
.theme-dark .recent-item,
.theme-dark .empty {
    background: rgba(24, 24, 27, .58);
}

.theme-dark .recent-item:hover {
    background: rgba(39, 39, 42, .76);
}

.theme-dark .student-avatar {
    color: #18181b;
    background: #fafafa;
}

@media (max-width: 1024px) {
    .stats-grid,
    .charts-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .donut-wrap {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 680px) {
    .dashboard-page {
        margin-top: -2rem;
        padding: 20px 12px 38px;
    }

    .dashboard-header,
    .chart-card,
    .recent-card,
    .stat-card {
        border-radius: 20px;
    }

    .stats-grid,
    .charts-grid {
        grid-template-columns: 1fr;
    }

    .header-actions,
    .button {
        width: 100%;
    }

    .donut {
        width: 180px;
        height: 180px;
    }

    .recent-item {
        grid-template-columns: auto 1fr;
    }

    .recent-date {
        grid-column: 2;
    }
}
</style>