<template>
    <section class="vue-page" :class="themeClass">
        <header class="page-header">
            <div>
                <p class="eyebrow">
                    {{ isTrashMode ? "Recycle Bin" : "Student Management" }}
                </p>
                <h1>
                    {{
                        isTrashMode ? "Deleted Students" : "Student Management"
                    }}
                </h1>
                <p class="subtitle">
                    {{
                        isTrashMode
                            ? "Restore deleted students or permanently remove them."
                            : "Search, filter, view, edit, and manage student records."
                    }}
                </p>
            </div>

            <div class="header-actions">
                <a class="button secondary" :href="dashboardUrl">Back</a>
                <a v-if="!isTrashMode" class="button primary" :href="createUrl"
                    >Add New Student</a
                >
                <a v-if="!isTrashMode" class="button warning" :href="trashUrl"
                    >Recycle Bin</a
                >
                <a v-else class="button primary" :href="indexUrl"
                    >Active Students</a
                >
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
                    />
                </label>

                <label>
                    <span>Gender</span>
                    <select v-model="filterState.gender" name="gender">
                        <option value="">All Genders</option>
                        <option
                            v-for="gender in filters.genders"
                            :key="gender"
                            :value="gender"
                        >
                            {{ gender }}
                        </option>
                    </select>
                </label>

                <label>
                    <span>Year Level</span>
                    <select v-model="filterState.year_level" name="year_level">
                        <option value="">All Year Levels</option>
                        <option
                            v-for="yearLevel in filters.yearLevels"
                            :key="yearLevel"
                            :value="yearLevel"
                        >
                            {{ yearLevel }}
                        </option>
                    </select>
                </label>

                <label>
                    <span>Course</span>
                    <select v-model="filterState.course_id" name="course_id">
                        <option value="">All Courses</option>
                        <option
                            v-for="course in filters.courses"
                            :key="course.id"
                            :value="String(course.id)"
                        >
                            {{ course.name
                            }}{{
                                course.department
                                    ? ` - ${course.department}`
                                    : ""
                            }}
                        </option>
                    </select>
                </label>
            </div>

            <div class="filter-actions">
                <button class="button primary" type="submit">
                    Apply Filters
                </button>
                <a class="button secondary" :href="currentListUrl"
                    >Clear Filters</a
                >
            </div>
        </form>

        <div class="toolbar">
            <span class="result-count">
                Showing {{ filteredStudents.length }} of
                {{ students.length }} students on this page
            </span>
            <span v-if="isTrashMode" class="badge danger-badge"
                >Recycle Bin Mode</span
            >
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
                            />
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
                                <a
                                    class="button secondary"
                                    :href="student.showUrl"
                                    >View Profile</a
                                >
                                <a
                                    class="button primary"
                                    :href="student.editUrl"
                                    >Edit</a
                                >

                                <form
                                    :action="student.deleteUrl"
                                    method="POST"
                                    @submit="confirmSoftDelete"
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
                                    <button class="button danger" type="submit">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <div v-else class="actions">
                                <form
                                    :action="student.restoreUrl"
                                    method="POST"
                                    @submit="confirmRestore"
                                >
                                    <input
                                        type="hidden"
                                        name="_token"
                                        :value="csrf"
                                    />
                                    <input
                                        type="hidden"
                                        name="_method"
                                        value="PATCH"
                                    />
                                    <button
                                        class="button success-button"
                                        type="submit"
                                    >
                                        Restore
                                    </button>
                                </form>

                                <form
                                    :action="student.forceDeleteUrl"
                                    method="POST"
                                    @submit="confirmPermanentDelete"
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
                                    <button class="button danger" type="submit">
                                        Permanent Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="filteredStudents.length === 0">
                        <td :colspan="isTrashMode ? 10 : 9" class="empty">
                            {{
                                isTrashMode
                                    ? "No deleted students found."
                                    : "No students found."
                            }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav v-if="pagination" class="pagination" v-html="pagination"></nav>
    </section>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from "vue";

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
        default: "",
    },
    success: {
        type: String,
        default: "",
    },
    pagination: {
        type: String,
        default: "",
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
            search: "",
            gender: "",
            year_level: "",
            course_id: "",
        }),
    },
    mode: {
        type: String,
        default: "active",
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

const searchText = ref(props.initialSearch);

const filterState = reactive({
    gender: props.currentFilters.gender || "",
    year_level: props.currentFilters.year_level || "",
    course_id: props.currentFilters.course_id || "",
});

const isTrashMode = computed(() => props.mode === "trash");
const currentListUrl = computed(() =>
    isTrashMode.value ? props.trashUrl : props.indexUrl,
);

const filteredStudents = computed(() => {
    const keyword = searchText.value.trim().toLowerCase();

    return props.students.filter((student) => {
        const matchesKeyword =
            !keyword ||
            [
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

        const matchesGender =
            !filterState.gender || student.gender === filterState.gender;
        const matchesYearLevel =
            !filterState.year_level ||
            student.year_level === filterState.year_level;
        const matchesCourse =
            !filterState.course_id ||
            String(student.course_id || "") === filterState.course_id;

        return (
            matchesKeyword && matchesGender && matchesYearLevel && matchesCourse
        );
    });
});

function confirmSoftDelete(event) {
    if (!window.confirm("Move this student to the recycle bin?")) {
        event.preventDefault();
    }
}

function confirmRestore(event) {
    if (!window.confirm("Restore this student?")) {
        event.preventDefault();
    }
}

function confirmPermanentDelete(event) {
    if (
        !window.confirm(
            "Permanently delete this student? This cannot be undone.",
        )
    ) {
        event.preventDefault();
    }
}
</script>

<style scoped src="../../css/vue/StudentIndex.css"></style>

