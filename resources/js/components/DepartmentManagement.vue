<template>
    <section class="department-page" :class="themeClass">
        <header class="page-header">
            <div>
                <p class="eyebrow">Department Management</p>
                <h1>Departments</h1>
                <p class="subtitle">
                    Create, update, and manage departments, department heads,
                    and descriptions.
                </p>
            </div>

            <div class="header-actions">
                <a class="button secondary" :href="dashboardUrl"
                    >Back to Dashboard</a
                >
            </div>
        </header>

        <div v-if="success" class="alert success">{{ success }}</div>

        <div v-if="errorMessages.length" class="alert danger-alert">
            <ul>
                <li v-for="message in errorMessages" :key="message">
                    {{ message }}
                </li>
            </ul>
        </div>

        <div class="content-grid">
            <form class="form-card" :action="formAction" method="POST">
                <input type="hidden" name="_token" :value="csrf" />
                <input
                    v-if="editingDepartment"
                    type="hidden"
                    name="_method"
                    value="PUT"
                />

                <div class="card-heading">
                    <p class="mini-label">
                        {{ editingDepartment ? "Update Record" : "New Record" }}
                    </p>
                    <h2>
                        {{
                            editingDepartment
                                ? "Edit Department"
                                : "Add Department"
                        }}
                    </h2>
                </div>

                <label>
                    <span>Department</span>
                    <select v-model="form.name" name="name" required>
                        <option value="" disabled>Select department</option>
                        <option
                            v-for="departmentName in departmentOptions"
                            :key="departmentName"
                            :value="departmentName"
                        >
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
                    />
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
                        {{
                            editingDepartment
                                ? "Update Department"
                                : "Create Department"
                        }}
                    </button>
                    <button
                        v-if="editingDepartment"
                        class="button secondary"
                        type="button"
                        @click="resetForm"
                    >
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
                        <tr
                            v-for="department in departments"
                            :key="department.id"
                        >
                            <td>
                                <strong>{{ department.name }}</strong>
                            </td>
                            <td>
                                {{
                                    department.department_head || "Not assigned"
                                }}
                            </td>
                            <td class="description-cell">
                                {{ department.description || "No description" }}
                            </td>
                            <td>
                                <span class="count-pill">{{
                                    department.courses_count
                                }}</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button
                                        class="button primary"
                                        type="button"
                                        @click="editDepartment(department)"
                                    >
                                        Edit
                                    </button>

                                    <form
                                        :action="department.deleteUrl"
                                        method="POST"
                                        @submit="confirmDelete"
                                    >
                                        <input
                                            type="hidden"
                                            name="_token"
                                            :value="csrf"
                                        />
                                        <input
                                            type="hidden"
                                            name="_method"
                                            value="DELETE"
                                        />
                                        <button
                                            class="button danger"
                                            type="submit"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="departments.length === 0">
                            <td colspan="5" class="empty">
                                No departments found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from "vue";

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
        default: "",
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

function readTheme() {
    const storedTheme = localStorage.getItem("dashboard-theme");

    if (
        storedTheme === "dark" ||
        document.documentElement.classList.contains("dashboard-dark")
    ) {
        return "dark";
    }

    return "light";
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
        attributeFilter: ["class"],
    });
});

onBeforeUnmount(() => {
    themeObserver?.disconnect();
});

const departmentOptions = ["BSIT", "BSCS", "BSEd", "BSBA"];

const editingDepartment = ref(null);

const form = reactive({
    name: "",
    department_head: "",
    description: "",
});

const formAction = computed(
    () => editingDepartment.value?.updateUrl || props.storeUrl,
);
const errorMessages = computed(() => Object.values(props.errors).flat());

function editDepartment(department) {
    editingDepartment.value = department;
    form.name = department.name || "";
    form.department_head = department.department_head || "";
    form.description = department.description || "";

    window.scrollTo({ top: 0, behavior: "smooth" });
}

function resetForm() {
    editingDepartment.value = null;
    form.name = "";
    form.department_head = "";
    form.description = "";
}

function confirmDelete(event) {
    if (!window.confirm("Delete this department?")) {
        event.preventDefault();
    }
}
</script>

<style scoped src="../../css/vue/DepartmentManagement.css"></style>

