<template>
    <section class="vue-page" :class="themeClass">
        <header class="page-header">
            <div>
                <p class="eyebrow">{{ isTrashMode ? 'Recycle Bin' : 'Student Management' }}</p>
                <h1>{{ isTrashMode ? 'Deleted Students' : 'Student Management' }}</h1>
                <p class="subtitle">
                    {{ isTrashMode
                        ? 'Restore deleted students or permanently remove them.'
                        : 'Search, filter, view, edit, and manage student records.'
                    }}
                </p>
            </div>

            <div class="header-actions">
                <a class="button secondary" :href="dashboardUrl">Back</a>
                <a v-if="!isTrashMode" class="button primary" :href="createUrl">Add New Student</a>
                <a v-if="!isTrashMode" class="button warning" :href="trashUrl">Recycle Bin</a>
                <a v-else class="button primary" :href="indexUrl">Active Students</a>
            </div>
        </header>

        <div v-if="success" class="alert success">{{ success }}</div>

        <form class="filter-card" :action="currentListUrl" method="GET">
            <div class="filter-grid">
                <label>
                    <span>Search</span>
                    <input
                        v-model="searchText"
                        type="text"
                        name="search"
                        placeholder="Search by name, ID, or email"
                    >
                </label>

                <label>
                    <span>Gender</span>
                    <select v-model="filterState.gender" name="gender">
                        <option value="">All Genders</option>
                        <option v-for="gender in filters.genders" :key="gender" :value="gender">
                            {{ gender }}
                        </option>
                    </select>
                </label>

                <label>
                    <span>Year Level</span>
                    <select v-model="filterState.year_level" name="year_level">
                        <option value="">All Year Levels</option>
                        <option v-for="yearLevel in filters.yearLevels" :key="yearLevel" :value="yearLevel">
                            {{ yearLevel }}
                        </option>
                    </select>
                </label>

                <label>
                    <span>Course</span>
                    <select v-model="filterState.course_id" name="course_id">
                        <option value="">All Courses</option>
                        <option v-for="course in filters.courses" :key="course.id" :value="String(course.id)">
                            {{ course.name }}{{ course.department ? ` - ${course.department}` : '' }}
                        </option>
                    </select>
                </label>
            </div>

            <div class="filter-actions">
                <button class="button primary" type="submit">Apply Filters</button>
                <a class="button secondary" :href="currentListUrl">Clear Filters</a>
            </div>
        </form>

        <div class="toolbar">
            <span class="result-count">
                Showing {{ filteredStudents.length }} of {{ students.length }} students on this page
            </span>
            <span v-if="isTrashMode" class="badge danger-badge">Recycle Bin Mode</span>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Department</th>
                        <th>Gender</th>
                        <th>Year Level</th>
                        <th v-if="isTrashMode">Deleted At</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="student in filteredStudents" :key="student.id">
                        <td>
                            <img
                                v-if="student.photoUrl"
                                class="student-photo"
                                :src="student.photoUrl"
                                :alt="student.fullName"
                            >
                            <span v-else class="muted">No photo</span>
                        </td>
                        <td>{{ student.student_id }}</td>
                        <td>{{ student.fullName }}</td>
                        <td>{{ student.email }}</td>
                        <td>{{ student.courseName }}</td>
                        <td>{{ student.departmentName }}</td>
                        <td>{{ student.gender }}</td>
                        <td>{{ student.year_level }}</td>
                        <td v-if="isTrashMode">{{ student.deletedAt }}</td>
                        <td>
                            <div v-if="!isTrashMode" class="actions">
                                <a class="button secondary" :href="student.showUrl">View Profile</a>
                                <a class="button primary" :href="student.editUrl">Edit</a>

                                <form
                                    :action="student.deleteUrl"
                                    method="POST"
                                    @submit="confirmSoftDelete"
                                >
                                    <input type="hidden" name="_token" :value="csrf">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="button danger" type="submit">Delete</button>
                                </form>
                            </div>

                            <div v-else class="actions">
                                <form
                                    :action="student.restoreUrl"
                                    method="POST"
                                    @submit="confirmRestore"
                                >
                                    <input type="hidden" name="_token" :value="csrf">
                                    <input type="hidden" name="_method" value="PATCH">
                                    <button class="button success-button" type="submit">Restore</button>
                                </form>

                                <form
                                    :action="student.forceDeleteUrl"
                                    method="POST"
                                    @submit="confirmPermanentDelete"
                                >
                                    <input type="hidden" name="_token" :value="csrf">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="button danger" type="submit">Permanent Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="filteredStudents.length === 0">
                        <td :colspan="isTrashMode ? 10 : 9" class="empty">
                            {{ isTrashMode ? 'No deleted students found.' : 'No students found.' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav v-if="pagination" class="pagination" v-html="pagination"></nav>
    </section>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';

const props = defineProps({
    students: {
        type: Array,
        default: () => [],
    },
    createUrl: {
        type: String,
        required: true,
    },
    indexUrl: {
        type: String,
        required: true,
    },
    trashUrl: {
        type: String,
        required: true,
    },
    dashboardUrl: {
        type: String,
        required: true,
    },
    csrf: {
        type: String,
        required: true,
    },
    initialSearch: {
        type: String,
        default: '',
    },
    success: {
        type: String,
        default: '',
    },
    pagination: {
        type: String,
        default: '',
    },
    filters: {
        type: Object,
        default: () => ({
            courses: [],
            genders: [],
            yearLevels: [],
        }),
    },
    currentFilters: {
        type: Object,
        default: () => ({
            search: '',
            gender: '',
            year_level: '',
            course_id: '',
        }),
    },
    mode: {
        type: String,
        default: 'active',
    },
});

function readTheme() {
    const storedTheme = localStorage.getItem('dashboard-theme');

    if (storedTheme === 'dark' || document.documentElement.classList.contains('dashboard-dark')) {
        return 'dark';
    }

    return 'light';
}

const theme = ref(readTheme());
const themeClass = computed(() => `theme-${theme.value}`);

let themeObserver;

onMounted(() => {
    theme.value = readTheme();

    themeObserver = new MutationObserver(() => {
        theme.value = readTheme();
    });

    themeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
});

onBeforeUnmount(() => {
    themeObserver?.disconnect();
});

const searchText = ref(props.initialSearch);

const filterState = reactive({
    gender: props.currentFilters.gender || '',
    year_level: props.currentFilters.year_level || '',
    course_id: props.currentFilters.course_id || '',
});

const isTrashMode = computed(() => props.mode === 'trash');
const currentListUrl = computed(() => (isTrashMode.value ? props.trashUrl : props.indexUrl));

const filteredStudents = computed(() => {
    const keyword = searchText.value.trim().toLowerCase();

    return props.students.filter((student) => {
        const matchesKeyword = !keyword || [
            student.student_id,
            student.fullName,
            student.email,
            student.courseName,
            student.departmentName,
            student.gender,
            student.year_level,
        ]
            .filter(Boolean)
            .some((value) => String(value).toLowerCase().includes(keyword));

        const matchesGender = !filterState.gender || student.gender === filterState.gender;
        const matchesYearLevel = !filterState.year_level || student.year_level === filterState.year_level;
        const matchesCourse = !filterState.course_id || String(student.course_id || '') === filterState.course_id;

        return matchesKeyword && matchesGender && matchesYearLevel && matchesCourse;
    });
});

function confirmSoftDelete(event) {
    if (!window.confirm('Move this student to the recycle bin?')) {
        event.preventDefault();
    }
}

function confirmRestore(event) {
    if (!window.confirm('Restore this student?')) {
        event.preventDefault();
    }
}

function confirmPermanentDelete(event) {
    if (!window.confirm('Permanently delete this student? This cannot be undone.')) {
        event.preventDefault();
    }
}
</script>

<style scoped>
.vue-page {
    --background: #ffffff;
    --card: #fafafa;
    --card-elevated: rgba(250, 250, 250, .88);
    --primary-text: #18181b;
    --muted-text: #71717a;
    --border: rgba(24, 24, 27, .10);
    --accent: #2563eb;
    --accent-hover: #1d4ed8;
    --ring: rgba(37, 99, 235, .22);
    --danger: #dc2626;
    --warning: #d97706;
    --success: #059669;
    --shadow: 0 22px 70px rgba(24, 24, 27, .10);
    --soft-shadow: 0 12px 34px rgba(24, 24, 27, .08);

    max-width: 1320px;
    min-height: calc(100vh - 64px);
    margin: -24px auto 0;
    padding: 32px 24px 56px;
    color: var(--primary-text);
    background:
        radial-gradient(circle at 12% 0%, rgba(37, 99, 235, .10), transparent 32%),
        radial-gradient(circle at 88% 8%, rgba(37, 99, 235, .07), transparent 30%),
        var(--background);
    border-radius: 0 0 32px 32px;
    font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    transition: background .25s ease, color .25s ease;
}

.vue-page.theme-dark,
:global(html.dashboard-dark) .vue-page,
:global(.dashboard-dark) .vue-page {
    --background: #09090b;
    --card: #18181b;
    --card-elevated: rgba(24, 24, 27, .88);
    --primary-text: #fafafa;
    --muted-text: #a1a1aa;
    --border: rgba(250, 250, 250, .10);
    --accent: #3b82f6;
    --accent-hover: #60a5fa;
    --ring: rgba(59, 130, 246, .30);
    --shadow: 0 22px 76px rgba(0, 0, 0, .34);
    --soft-shadow: 0 14px 40px rgba(0, 0, 0, .28);
}

.page-header,
.toolbar {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.page-header,
.filter-card,
.table-card {
    background: var(--card-elevated);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    backdrop-filter: blur(20px) saturate(170%);
    -webkit-backdrop-filter: blur(20px) saturate(170%);
}

.page-header {
    position: relative;
    overflow: hidden;
    padding: 28px;
    border-radius: 24px;
}

.page-header::after {
    content: "";
    position: absolute;
    right: -90px;
    bottom: -120px;
    width: 280px;
    height: 280px;
    background: radial-gradient(circle, color-mix(in srgb, var(--accent) 22%, transparent), transparent 68%);
    pointer-events: none;
}

.page-header > * {
    position: relative;
    z-index: 1;
}

.eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 10px;
    padding: 7px 11px;
    color: var(--accent);
    background: color-mix(in srgb, var(--accent) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--accent) 18%, transparent);
    border-radius: 999px;
    font-size: 12px;
    font-weight: 850;
    letter-spacing: .08em;
    text-transform: uppercase;
}

h1 {
    margin: 0;
    color: var(--primary-text);
    font-size: clamp(34px, 5vw, 52px);
    font-weight: 900;
    letter-spacing: -.055em;
    line-height: .95;
}

.subtitle {
    max-width: 650px;
    margin: 12px 0 0;
    color: var(--muted-text);
    font-size: 15px;
    line-height: 1.7;
}

.alert {
    padding: 14px 16px;
    margin-bottom: 16px;
    border-radius: 16px;
    font-weight: 750;
    box-shadow: var(--soft-shadow);
}

.success {
    color: #065f46;
    background: rgba(209, 250, 229, .92);
    border: 1px solid #a7f3d0;
}

.vue-page.theme-dark .success,
:global(html.dashboard-dark) .success,
:global(.dashboard-dark) .success {
    color: #bbf7d0;
    background: rgba(20, 83, 45, .38);
    border-color: rgba(74, 222, 128, .28);
}

.header-actions,
.filter-actions,
.actions {
    display: flex;
    gap: 9px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-card {
    padding: 20px;
    margin-bottom: 18px;
    border-radius: 22px;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 16px;
}

.filter-grid label {
    display: grid;
    gap: 8px;
}

.filter-grid label span {
    color: var(--muted-text);
    font-size: 12px;
    font-weight: 850;
    text-transform: uppercase;
    letter-spacing: .06em;
}

.filter-grid input,
.filter-grid select {
    width: 100%;
    min-height: 44px;
    padding: 10px 13px;
    color: var(--primary-text);
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.filter-grid input::placeholder {
    color: var(--muted-text);
}

.filter-grid input:focus,
.filter-grid select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 4px var(--ring);
}

.result-count,
.muted {
    color: var(--muted-text);
}

.result-count,
.badge {
    display: inline-flex;
    align-items: center;
    min-height: 34px;
    padding: 7px 12px;
    background: var(--card-elevated);
    border: 1px solid var(--border);
    border-radius: 999px;
    font-size: 13px;
    font-weight: 800;
}

.danger-badge {
    color: #991b1b;
    background: #fee2e2;
    border-color: #fecaca;
}

.vue-page.theme-dark .danger-badge,
:global(html.dashboard-dark) .danger-badge,
:global(.dashboard-dark) .danger-badge {
    color: #fecaca;
    background: rgba(127, 29, 29, .35);
    border-color: rgba(248, 113, 113, .28);
}

.table-card {
    overflow-x: auto;
    border-radius: 22px;
}

table {
    width: 100%;
    min-width: 1060px;
    border-collapse: collapse;
}

th,
td {
    padding: 15px 16px;
    border-bottom: 1px solid var(--border);
    text-align: left;
    vertical-align: middle;
}

th {
    color: var(--muted-text);
    background: color-mix(in srgb, var(--card) 88%, var(--accent) 4%);
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .07em;
}

td {
    color: var(--primary-text);
    font-size: 14px;
}

tbody tr {
    transition: background .18s ease;
}

tbody tr:hover {
    background: color-mix(in srgb, var(--accent) 7%, transparent);
}

.student-photo {
    width: 58px;
    height: 58px;
    object-fit: cover;
    border: 2px solid var(--border);
    border-radius: 16px;
    box-shadow: var(--soft-shadow);
}

.button {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    min-height: 38px;
    padding: 9px 13px;
    color: #ffffff;
    text-decoration: none;
    border: 1px solid transparent;
    border-radius: 12px;
    cursor: pointer;
    font: inherit;
    font-size: 13px;
    font-weight: 800;
    white-space: nowrap;
    box-shadow: 0 10px 22px rgba(24, 24, 27, .10);
    transition: transform .2s ease, box-shadow .2s ease, filter .2s ease, border-color .2s ease;
}

.button:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 32px rgba(24, 24, 27, .16);
    filter: brightness(1.03);
}

.primary {
    background: var(--accent);
}

.primary:hover {
    background: var(--accent-hover);
}

.secondary {
    color: var(--primary-text);
    background: var(--card);
    border-color: var(--border);
}

.warning {
    background: var(--warning);
}

.danger {
    background: var(--danger);
}

.success-button {
    background: var(--success);
}

.empty {
    padding: 36px;
    color: var(--muted-text);
    text-align: center;
}

.pagination {
    margin-top: 20px;
    color: var(--primary-text);
}

.pagination :deep(nav),
.pagination :deep(.hidden),
.pagination :deep(p),
.pagination :deep(span),
.pagination :deep(a) {
    color: var(--muted-text);
}

.pagination :deep(a),
.pagination :deep(span) {
    background: var(--card) !important;
    border-color: var(--border) !important;
}

@media (max-width: 900px) {
    .vue-page {
        padding: 24px 14px 42px;
    }

    .filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 620px) {
    .page-header,
    .filter-card,
    .table-card {
        border-radius: 20px;
    }

    .header-actions,
    .filter-actions,
    .button {
        width: 100%;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }
}
</style>