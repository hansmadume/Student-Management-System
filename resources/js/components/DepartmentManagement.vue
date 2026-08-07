<template>
    <section class="department-page" :class="themeClass">
        <header class="page-header">
            <div>
                <p class="eyebrow">Department Management</p>
                <h1>Departments</h1>
                <p class="subtitle">Create, update, and manage departments, department heads, and descriptions.</p>
            </div>

            <div class="header-actions">
                <a class="button secondary" :href="dashboardUrl">Back to Dashboard</a>
            </div>
        </header>

        <div v-if="success" class="alert success">{{ success }}</div>

        <div v-if="errorMessages.length" class="alert danger-alert">
            <ul>
                <li v-for="message in errorMessages" :key="message">{{ message }}</li>
            </ul>
        </div>

        <div class="content-grid">
            <form class="form-card" :action="formAction" method="POST">
                <input type="hidden" name="_token" :value="csrf">
                <input v-if="editingDepartment" type="hidden" name="_method" value="PUT">

                <div class="card-heading">
                    <p class="mini-label">{{ editingDepartment ? 'Update Record' : 'New Record' }}</p>
                    <h2>{{ editingDepartment ? 'Edit Department' : 'Add Department' }}</h2>
                </div>

                <label>
                    <span>Department</span>
                    <select v-model="form.name" name="name" required>
                        <option value="" disabled>Select department</option>
                        <option v-for="departmentName in departmentOptions" :key="departmentName" :value="departmentName">
                            {{ departmentName }}
                        </option>
                    </select>
                </label>

                <label>
                    <span>Department Head</span>
                    <input
                        v-model="form.department_head"
                        type="text"
                        name="department_head"
                        placeholder="Enter department head"
                        maxlength="255"
                    >
                </label>

                <label>
                    <span>Description</span>
                    <textarea
                        v-model="form.description"
                        name="description"
                        rows="5"
                        placeholder="Enter department description"
                        maxlength="2000"
                    ></textarea>
                </label>

                <div class="form-actions">
                    <button class="button primary" type="submit">
                        {{ editingDepartment ? 'Update Department' : 'Create Department' }}
                    </button>
                    <button v-if="editingDepartment" class="button secondary" type="button" @click="resetForm">
                        Cancel
                    </button>
                </div>
            </form>

            <div class="table-card">
                <div class="table-header">
                    <div>
                        <p class="mini-label">Directory</p>
                        <h2>Department List</h2>
                    </div>
                    <span>{{ departments.length }} departments</span>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Department Head</th>
                            <th>Description</th>
                            <th>Courses</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="department in departments" :key="department.id">
                            <td>
                                <strong>{{ department.name }}</strong>
                            </td>
                            <td>{{ department.department_head || 'Not assigned' }}</td>
                            <td class="description-cell">{{ department.description || 'No description' }}</td>
                            <td>
                                <span class="count-pill">{{ department.courses_count }}</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="button primary" type="button" @click="editDepartment(department)">
                                        Edit
                                    </button>

                                    <form
                                        :action="department.deleteUrl"
                                        method="POST"
                                        @submit="confirmDelete"
                                    >
                                        <input type="hidden" name="_token" :value="csrf">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="button danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="departments.length === 0">
                            <td colspan="5" class="empty">No departments found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';

const props = defineProps({
    departments: {
        type: Array,
        default: () => [],
    },
    storeUrl: {
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
    success: {
        type: String,
        default: '',
    },
    errors: {
        type: Object,
        default: () => ({}),
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

const departmentOptions = ['BSIT', 'BSCS', 'BSEd', 'BSBA'];

const editingDepartment = ref(null);

const form = reactive({
    name: '',
    department_head: '',
    description: '',
});

const formAction = computed(() => editingDepartment.value?.updateUrl || props.storeUrl);

const errorMessages = computed(() => Object.values(props.errors).flat());

function editDepartment(department) {
    editingDepartment.value = department;
    form.name = department.name || '';
    form.department_head = department.department_head || '';
    form.description = department.description || '';

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    editingDepartment.value = null;
    form.name = '';
    form.department_head = '';
    form.description = '';
}

function confirmDelete(event) {
    if (!window.confirm('Delete this department?')) {
        event.preventDefault();
    }
}
</script>

<style scoped>
.department-page {
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
    --success: #059669;
    --shadow: 0 22px 70px rgba(24, 24, 27, .10);
    --soft-shadow: 0 12px 34px rgba(24, 24, 27, .08);
    --header-tint: rgba(37, 99, 235, .10);
    --row-hover: rgba(37, 99, 235, .06);

    max-width: 1320px;
    min-height: calc(100vh - 64px);
    margin: -24px auto 0;
    padding: 32px 24px 56px;
    color: var(--primary-text);
    background:
        radial-gradient(circle at 12% 0%, rgba(37, 99, 235, .10), transparent 32%),
        radial-gradient(circle at 88% 8%, rgba(16, 185, 129, .08), transparent 30%),
        var(--background);
    border-radius: 0 0 32px 32px;
    font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    transition: background .25s ease, color .25s ease;
}

.department-page.theme-dark,
:global(html.dashboard-dark) .department-page,
:global(.dashboard-dark) .department-page {
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
    --header-tint: rgba(59, 130, 246, .16);
    --row-hover: rgba(59, 130, 246, .10);
}

.page-header,
.table-header,
.header-actions,
.form-actions,
.actions {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.page-header,
.table-header {
    justify-content: space-between;
}

.page-header,
.form-card,
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
    margin-bottom: 18px;
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
    background: radial-gradient(circle, var(--header-tint), transparent 68%);
    pointer-events: none;
}

.page-header > * {
    position: relative;
    z-index: 1;
}

.eyebrow,
.mini-label {
    display: inline-flex;
    margin: 0;
    color: var(--accent);
    font-weight: 850;
    text-transform: uppercase;
    letter-spacing: .08em;
}

.eyebrow {
    margin-bottom: 10px;
    padding: 7px 11px;
    background: var(--header-tint);
    border: 1px solid var(--ring);
    border-radius: 999px;
    font-size: 12px;
}

.mini-label {
    margin-bottom: 6px;
    font-size: 11px;
}

h1,
h2,
p {
    margin: 0;
}

h1 {
    color: var(--primary-text);
    font-size: clamp(34px, 5vw, 52px);
    font-weight: 900;
    letter-spacing: -.055em;
    line-height: .95;
}

h2 {
    color: var(--primary-text);
    font-size: 21px;
    font-weight: 900;
    letter-spacing: -.03em;
}

.subtitle {
    max-width: 640px;
    margin-top: 12px;
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

.department-page.theme-dark .success,
:global(html.dashboard-dark) .success,
:global(.dashboard-dark) .success {
    color: #bbf7d0;
    background: rgba(20, 83, 45, .38);
    border-color: rgba(74, 222, 128, .28);
}

.danger-alert {
    color: #991b1b;
    background: rgba(254, 226, 226, .94);
    border: 1px solid #fecaca;
}

.department-page.theme-dark .danger-alert,
:global(html.dashboard-dark) .danger-alert,
:global(.dashboard-dark) .danger-alert {
    color: #fecaca;
    background: rgba(127, 29, 29, .35);
    border-color: rgba(248, 113, 113, .28);
}

.danger-alert ul {
    margin: 0;
    padding-left: 18px;
}

.content-grid {
    display: grid;
    grid-template-columns: 390px 1fr;
    gap: 18px;
    align-items: start;
}

.form-card,
.table-card {
    padding: 22px;
    border-radius: 22px;
}

.form-card {
    display: grid;
    gap: 17px;
}

.card-heading {
    padding-bottom: 4px;
}

label {
    display: grid;
    gap: 8px;
    color: var(--primary-text);
    font-size: 13px;
    font-weight: 850;
}

label span {
    color: var(--muted-text);
    text-transform: uppercase;
    letter-spacing: .06em;
}

input,
select,
textarea {
    width: 100%;
    padding: 12px 13px;
    color: var(--primary-text);
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    outline: none;
    font: inherit;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

input::placeholder,
textarea::placeholder {
    color: var(--muted-text);
}

input:focus,
select:focus,
textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 4px var(--ring);
}

textarea {
    resize: vertical;
}

.table-card {
    overflow-x: auto;
}

.table-header {
    margin-bottom: 16px;
}

.table-header span,
.count-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 32px;
    padding: 6px 12px;
    color: var(--muted-text);
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 999px;
    font-size: 13px;
    font-weight: 850;
}

.count-pill {
    min-width: 38px;
    color: var(--accent);
    background: var(--header-tint);
    border-color: var(--ring);
}

table {
    width: 100%;
    min-width: 860px;
    border-collapse: collapse;
}

th,
td {
    padding: 15px 16px;
    border-bottom: 1px solid var(--border);
    text-align: left;
    vertical-align: top;
}

th {
    color: var(--muted-text);
    background: var(--card);
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
    background: var(--row-hover);
}

.description-cell {
    max-width: 380px;
    color: var(--muted-text);
    line-height: 1.6;
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

.danger {
    background: var(--danger);
}

.empty {
    padding: 36px;
    color: var(--muted-text);
    text-align: center;
}

@media (max-width: 980px) {
    .department-page {
        padding: 24px 14px 42px;
    }

    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 620px) {
    .page-header,
    .form-card,
    .table-card {
        border-radius: 20px;
    }

    .header-actions,
    .form-actions,
    .button {
        width: 100%;
    }
}
</style>