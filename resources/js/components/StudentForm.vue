<template>
    <section class="vue-form-page">
        <div class="form-card">
            <header class="form-header">
                <div>
                    <p class="eyebrow">Student Record</p>
                    <h1>{{ title }}</h1>
                    <p class="subtitle">
                        Create and update student information with a clean,
                        responsive Shadcn-inspired form.
                    </p>
                </div>
            </header>

            <div v-if="errors.length" class="errors">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <li v-for="error in errors" :key="error">{{ error }}</li>
                </ul>
            </div>

            <form :action="action" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" :value="csrf" />
                <input
                    v-if="method !== 'POST'"
                    type="hidden"
                    name="_method"
                    :value="method"
                />

                <div class="grid">
                    <label>
                        <span>Student ID</span>
                        <input
                            v-model="form.student_id"
                            type="text"
                            name="student_id"
                            required
                        />
                    </label>

                    <label>
                        <span>First Name</span>
                        <input
                            v-model="form.first_name"
                            type="text"
                            name="first_name"
                            required
                        />
                    </label>

                    <label>
                        <span>Last Name</span>
                        <input
                            v-model="form.last_name"
                            type="text"
                            name="last_name"
                            required
                        />
                    </label>

                    <label>
                        <span>Birthday</span>
                        <input
                            v-model="form.birthday"
                            type="date"
                            name="birthday"
                            required
                        />
                    </label>

                    <label>
                        <span>Gender</span>
                        <select v-model="form.gender" name="gender" required>
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        .vue-form-page { --background: #050505; --card: rgba(16,
                        16, 18, .94); --card-solid: #111113; --primary: #f5f5f5;
                        --muted: #a1a1aa; --border: rgba(255, 255, 255, .08);
                        --accent: #ef4444; --accent-strong: #b91c1c; --danger:
                        #dc2626; --ring: rgba(239, 68, 68, .28); --shadow: 0
                        28px 90px rgba(0, 0, 0, .42); --soft-shadow: 0 14px 36px
                        rgba(0, 0, 0, .26); min-height: calc(100vh - 64px);
                        margin: -24px auto 0; padding: 34px 18px 56px; color:
                        var(--primary); background: var(--background);
                        font-family: Inter, ui-sans-serif, system-ui,
                        -apple-system, BlinkMacSystemFont, "Segoe UI",
                        sans-serif; transition: background .25s ease, color .25s
                        ease;
                        <select v-model="form.course_id" name="course_id">
                            <option value="">No course relationship yet</option>
                            <option
                                v-for="course in filteredCourses"
                                :key="course.id"
                                :value="course.id"
                            >
                                {{ course.course_name
                                }}<template v-if="course.department_name">
                                    - {{ course.department_name }}</template
                                >
                            </option>
                        </select>
                    </label>

                    <label>
                        <span>Course Text</span>
                        <input
                            v-model="form.course"
                            type="text"
                            name="course"
                            placeholder="Example: BSIT"
                            required
                        />
                    </label>

                    <label>
                        <span>Year Level</span>
                        <input
                            v-model="form.year_level"
                            type="text"
                            name="year_level"
                            required
                        />
                    </label>
                </div>

                <div class="photo-section">
                    <label>
                        <span>Student Photo</span>
                        <input
                            type="file"
                            name="photo"
                            accept="image/*"
                            @change="previewPhoto"
                        />
                    </label>

                    <img
                        v-if="previewUrl"
                        class="photo-preview"
                        :src="previewUrl"
                        alt="Student photo preview"
                    />
                </div>

                <div class="actions">
                    <button class="button primary" type="submit">
                        {{ submitLabel }}
                    </button>
                    <a class="button secondary" :href="cancelUrl">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup>
import { computed, reactive, ref, watch } from "vue";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    action: {
        type: String,
        required: true,
    },
    method: {
        type: String,
        default: "POST",
    },
    submitLabel: {
        type: String,
        default: "Save Student",
    },
    cancelUrl: {
        type: String,
        required: true,
    },
    csrf: {
        type: String,
        required: true,
    },
    student: {
        type: Object,
        default: () => ({}),
    },
    departments: {
        type: Array,
        default: () => [],
    },
    courses: {
        type: Array,
        default: () => [],
    },
    errors: {
        type: Array,
        default: () => [],
    },
});

const form = reactive({
    student_id: props.student.student_id || "",
    first_name: props.student.first_name || "",
    last_name: props.student.last_name || "",
    birthday: props.student.birthday || "",
    gender: props.student.gender || "",
    email: props.student.email || "",
    phone: props.student.phone || "",
    department_id: props.student.department_id || "",
    course_id: props.student.course_id || "",
    course: props.student.course || "",
    year_level: props.student.year_level || "",
});

const previewUrl = ref(props.student.photoUrl || "");

const filteredCourses = computed(() => {
    if (!form.department_id) {
        return props.courses;
    }

    return props.courses.filter(
        (course) => String(course.department_id) === String(form.department_id),
    );
});

watch(
    () => form.department_id,
    () => {
        const selectedCourse = props.courses.find(
            (course) => String(course.id) === String(form.course_id),
        );

        if (
            selectedCourse &&
            String(selectedCourse.department_id) !== String(form.department_id)
        ) {
            form.course_id = "";
        }

        const selectedDepartment = props.departments.find(
            (department) =>
                String(department.id) === String(form.department_id),
        );

        if (selectedDepartment && !form.course) {
            form.course = selectedDepartment.name;
        }
    },
);

watch(
    () => form.course_id,
    () => {
        const selectedCourse = props.courses.find(
            (course) => String(course.id) === String(form.course_id),
        );

        if (!selectedCourse) {
            return;
        }

        form.department_id = selectedCourse.department_id || form.department_id;
        form.course = selectedCourse.course_name || form.course;
    },
);

function previewPhoto(event) {
    const [file] = event.target.files;

    if (file) {
        previewUrl.value = URL.createObjectURL(file);
    }
}
</script>

<style scoped src="../../css/vue/StudentForm.css"></style>

