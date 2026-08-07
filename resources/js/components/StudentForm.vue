<template>
    <section class="vue-form-page">
        <div class="form-card">
            <header class="form-header">
                <div>
                    <p class="eyebrow">Student Record</p>
                    <h1>{{ title }}</h1>
                    <p class="subtitle">Create and update student information with a clean, responsive Shadcn-inspired form.</p>
                </div>
            </header>

            <div v-if="errors.length" class="errors">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <li v-for="error in errors" :key="error">{{ error }}</li>
                </ul>
            </div>

            <form :action="action" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" :value="csrf">
                <input v-if="method !== 'POST'" type="hidden" name="_method" :value="method">

                <div class="grid">
                    <label>
                        <span>Student ID</span>
                        <input v-model="form.student_id" type="text" name="student_id" required>
                    </label>

                    <label>
                        <span>First Name</span>
                        <input v-model="form.first_name" type="text" name="first_name" required>
                    </label>

                    <label>
                        <span>Last Name</span>
                        <input v-model="form.last_name" type="text" name="last_name" required>
                    </label>

                    <label>
                        <span>Birthday</span>
                        <input v-model="form.birthday" type="date" name="birthday" required>
                    </label>

                    <label>
                        <span>Gender</span>
                        <select v-model="form.gender" name="gender" required>
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </label>

                    <label>
                        <span>Email</span>
                        <input v-model="form.email" type="email" name="email" required>
                    </label>

                    <label>
                        <span>Phone</span>
                        <input v-model="form.phone" type="text" name="phone" required>
                    </label>

                    <label>
                        <span>Department</span>
                        <select v-model="form.department_id" name="department_id" required>
                            <option value="">Select department</option>
                            <option v-for="department in departments" :key="department.id" :value="department.id">
                                {{ department.name }}
                            </option>
                        </select>
                    </label>

                    <label>
                        <span>Course Relationship</span>
                        <select v-model="form.course_id" name="course_id">
                            <option value="">No course relationship yet</option>
                            <option v-for="course in filteredCourses" :key="course.id" :value="course.id">
                                {{ course.course_name }}<template v-if="course.department_name"> - {{ course.department_name }}</template>
                            </option>
                        </select>
                    </label>

                    <label>
                        <span>Course Text</span>
                        <input v-model="form.course" type="text" name="course" placeholder="Example: BSIT" required>
                    </label>

                    <label>
                        <span>Year Level</span>
                        <input v-model="form.year_level" type="text" name="year_level" required>
                    </label>
                </div>

                <div class="photo-section">
                    <label>
                        <span>Student Photo</span>
                        <input type="file" name="photo" accept="image/*" @change="previewPhoto">
                    </label>

                    <img
                        v-if="previewUrl"
                        class="photo-preview"
                        :src="previewUrl"
                        alt="Student photo preview"
                    >
                </div>

                <div class="actions">
                    <button class="button primary" type="submit">{{ submitLabel }}</button>
                    <a class="button secondary" :href="cancelUrl">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup>
import { computed, reactive, ref, watch } from 'vue';

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
        default: 'POST',
    },
    submitLabel: {
        type: String,
        default: 'Save Student',
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
    student_id: props.student.student_id || '',
    first_name: props.student.first_name || '',
    last_name: props.student.last_name || '',
    birthday: props.student.birthday || '',
    gender: props.student.gender || '',
    email: props.student.email || '',
    phone: props.student.phone || '',
    department_id: props.student.department_id || '',
    course_id: props.student.course_id || '',
    course: props.student.course || '',
    year_level: props.student.year_level || '',
});

const previewUrl = ref(props.student.photoUrl || '');

const filteredCourses = computed(() => {
    if (!form.department_id) {
        return props.courses;
    }

    return props.courses.filter((course) => String(course.department_id) === String(form.department_id));
});

watch(() => form.department_id, () => {
    const selectedCourse = props.courses.find((course) => String(course.id) === String(form.course_id));

    if (selectedCourse && String(selectedCourse.department_id) !== String(form.department_id)) {
        form.course_id = '';
    }

    const selectedDepartment = props.departments.find((department) => String(department.id) === String(form.department_id));

    if (selectedDepartment && !form.course) {
        form.course = selectedDepartment.name;
    }
});

watch(() => form.course_id, () => {
    const selectedCourse = props.courses.find((course) => String(course.id) === String(form.course_id));

    if (!selectedCourse) {
        return;
    }

    form.department_id = selectedCourse.department_id || form.department_id;
    form.course = selectedCourse.course_name || form.course;
});

function previewPhoto(event) {
    const [file] = event.target.files;

    if (file) {
        previewUrl.value = URL.createObjectURL(file);
    }
}
</script>

<style scoped>
.vue-form-page {
    --background: #ffffff;
    --card: rgba(250, 250, 250, .86);
    --card-solid: #fafafa;
    --primary: #18181b;
    --muted: #71717a;
    --border: rgba(24, 24, 27, .10);
    --accent: #2563eb;
    --accent-strong: #1d4ed8;
    --danger: #dc2626;
    --ring: rgba(37, 99, 235, .22);
    --shadow: 0 24px 80px rgba(24, 24, 27, .10);
    --soft-shadow: 0 16px 40px rgba(24, 24, 27, .08);

    min-height: calc(100vh - 64px);
    margin: -24px auto 0;
    padding: 34px 18px 56px;
    color: var(--primary);
    background:
        radial-gradient(circle at 12% 4%, rgba(37, 99, 235, .14), transparent 30%),
        radial-gradient(circle at 88% 0%, rgba(59, 130, 246, .10), transparent 28%),
        linear-gradient(180deg, var(--background), var(--background));
    font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    transition: background .25s ease, color .25s ease;
}

:global(.dashboard-dark) .vue-form-page {
    --background: #09090b;
    --card: rgba(24, 24, 27, .78);
    --card-solid: #18181b;
    --primary: #fafafa;
    --muted: #a1a1aa;
    --border: rgba(250, 250, 250, .10);
    --accent: #3b82f6;
    --accent-strong: #60a5fa;
    --ring: rgba(59, 130, 246, .32);
    --shadow: 0 24px 90px rgba(0, 0, 0, .34);
    --soft-shadow: 0 18px 52px rgba(0, 0, 0, .28);
}

.form-card {
    position: relative;
    max-width: 980px;
    margin: 0 auto;
    overflow: hidden;
    padding: 30px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 28px;
    box-shadow: var(--shadow);
    backdrop-filter: blur(22px) saturate(180%);
    -webkit-backdrop-filter: blur(22px) saturate(180%);
}

.form-card::after {
    content: "";
    position: absolute;
    right: -90px;
    top: -110px;
    width: 270px;
    height: 270px;
    background: radial-gradient(circle, rgba(37, 99, 235, .20), transparent 68%);
    pointer-events: none;
}

.form-card > * {
    position: relative;
    z-index: 1;
}

.form-header {
    margin-bottom: 24px;
}

.eyebrow {
    display: inline-flex;
    margin: 0 0 10px;
    padding: 7px 11px;
    color: var(--accent);
    background: rgba(37, 99, 235, .10);
    border: 1px solid rgba(37, 99, 235, .18);
    border-radius: 999px;
    font-size: 12px;
    font-weight: 850;
    letter-spacing: .08em;
    text-transform: uppercase;
}

h1 {
    margin: 0;
    color: var(--primary);
    font-size: clamp(34px, 5vw, 52px);
    font-weight: 900;
    letter-spacing: -.055em;
    line-height: .95;
}

.subtitle {
    max-width: 660px;
    margin: 12px 0 0;
    color: var(--muted);
    font-size: 15px;
    line-height: 1.7;
}

.errors {
    padding: 15px 16px;
    margin-bottom: 20px;
    color: #991b1b;
    background: rgba(254, 226, 226, .94);
    border: 1px solid #fecaca;
    border-radius: 18px;
    box-shadow: var(--soft-shadow);
}

.errors ul {
    margin-bottom: 0;
}

.grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

label {
    display: grid;
    gap: 8px;
}

label span {
    display: block;
    color: var(--muted);
    font-size: 13px;
    font-weight: 850;
    letter-spacing: .05em;
    text-transform: uppercase;
}

input,
select {
    width: 100%;
    min-height: 46px;
    padding: 11px 13px;
    color: var(--primary);
    background: var(--card-solid);
    border: 1px solid var(--border);
    border-radius: 14px;
    box-sizing: border-box;
    outline: none;
    font: inherit;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

input:focus,
select:focus {
    border-color: rgba(37, 99, 235, .55);
    box-shadow: 0 0 0 4px var(--ring);
}

.photo-section {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 18px;
    align-items: end;
    margin-top: 20px;
    padding: 18px;
    background: rgba(255, 255, 255, .42);
    border: 1px solid var(--border);
    border-radius: 22px;
}

:global(.dashboard-dark) .photo-section {
    background: rgba(24, 24, 27, .52);
}

.photo-preview {
    width: 128px;
    height: 128px;
    object-fit: cover;
    border: 3px solid var(--card-solid);
    border-radius: 22px;
    box-shadow: 0 16px 38px rgba(24, 24, 27, .14);
}

.actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 24px;
}

.button {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    min-height: 44px;
    padding: 10px 16px;
    color: #ffffff;
    text-decoration: none;
    border: 1px solid transparent;
    border-radius: 14px;
    cursor: pointer;
    font: inherit;
    font-size: 14px;
    font-weight: 800;
    box-shadow: 0 14px 28px rgba(24, 24, 27, .10);
    transition: transform .22s ease, box-shadow .22s ease, filter .22s ease;
}

.button:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px rgba(24, 24, 27, .16);
    filter: brightness(1.03);
}

.primary {
    background: linear-gradient(135deg, var(--accent), var(--accent-strong));
}

.secondary {
    color: var(--primary);
    background: var(--card-solid);
    border-color: var(--border);
}

@media (max-width: 720px) {
    .vue-form-page {
        padding: 22px 12px 42px;
    }

    .form-card {
        padding: 22px;
        border-radius: 22px;
    }

    .grid,
    .photo-section {
        grid-template-columns: 1fr;
    }

    .actions,
    .button {
        width: 100%;
    }
}
</style>
