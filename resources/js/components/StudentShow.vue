<template>
    <section class="vue-show-page">
        <article class="profile-card">
            <header class="profile-header">
                <div>
                    <p class="eyebrow">Student Profile</p>
                    <h1>{{ student.fullName }}</h1>
                    <p class="subtitle">{{ student.student_id }}</p>
                </div>

                <img
                    v-if="student.photoUrl"
                    class="profile-photo"
                    :src="student.photoUrl"
                    :alt="student.fullName"
                />
            </header>

            <dl class="details-grid">
                <div>
                    <dt>Birthday</dt>
                    <dd>{{ student.birthday || "N/A" }}</dd>
                </div>

                <div>
                    <dt>Gender</dt>
                    <dd>{{ student.gender || "N/A" }}</dd>
                </div>

                <div>
                    <dt>Email</dt>
                    <dd>{{ student.email || "N/A" }}</dd>
                </div>

                <div>
                    <dt>Phone</dt>
                    <dd>{{ student.phone || "N/A" }}</dd>
                </div>

                <div>
                    <dt>Course</dt>
                    <dd>{{ student.courseName || "N/A" }}</dd>
                </div>

                <div>
                    <dt>Department</dt>
                    <dd>{{ student.departmentName || "N/A" }}</dd>
                </div>

                <div>
                    <dt>Year Level</dt>
                    <dd>{{ student.year_level || "N/A" }}</dd>
                </div>
            </dl>

            <div class="actions">
                <a class="button primary" :href="student.editUrl"
                    >Edit Student</a
                >
                <a class="button secondary" :href="indexUrl">Back to List</a>
            </div>
        </article>
    </section>
</template>

<script setup>
defineProps({
    student: {
        type: Object,
        required: true,
    },
    indexUrl: {
        type: String,
        required: true,
    },
});
</script>

<style scoped src="../../css/vue/StudentShow.css"></style>
.vue-show-page { --background: #050505; --card: rgba(16, 16, 18, .94);
--card-solid: #111113; --primary: #f5f5f5; --muted: #a1a1aa; --border: rgba(255,
255, 255, .08); --accent: #ef4444; --accent-strong: #b91c1c; --ring: rgba(239,
68, 68, .28); --shadow: 0 28px 90px rgba(0, 0, 0, .42); --soft-shadow: 0 14px
36px rgba(0, 0, 0, .26); min-height: calc(100vh - 64px); margin: -24px auto 0;
padding: 34px 18px 56px; color: var(--primary); background: var(--background);
font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
"Segoe UI", sans-serif; transition: background .25s ease, color .25s ease;

