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
                >
            </header>

            <dl class="details-grid">
                <div>
                    <dt>Birthday</dt>
                    <dd>{{ student.birthday || 'N/A' }}</dd>
                </div>

                <div>
                    <dt>Gender</dt>
                    <dd>{{ student.gender || 'N/A' }}</dd>
                </div>

                <div>
                    <dt>Email</dt>
                    <dd>{{ student.email || 'N/A' }}</dd>
                </div>

                <div>
                    <dt>Phone</dt>
                    <dd>{{ student.phone || 'N/A' }}</dd>
                </div>

                <div>
                    <dt>Course</dt>
                    <dd>{{ student.courseName || 'N/A' }}</dd>
                </div>

                <div>
                    <dt>Department</dt>
                    <dd>{{ student.departmentName || 'N/A' }}</dd>
                </div>

                <div>
                    <dt>Year Level</dt>
                    <dd>{{ student.year_level || 'N/A' }}</dd>
                </div>
            </dl>

            <div class="actions">
                <a class="button primary" :href="student.editUrl">Edit Student</a>
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

<style scoped>
.vue-show-page {
    --background: #ffffff;
    --card: rgba(250, 250, 250, .86);
    --card-solid: #fafafa;
    --primary: #18181b;
    --muted: #71717a;
    --border: rgba(24, 24, 27, .10);
    --accent: #2563eb;
    --accent-strong: #1d4ed8;
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

:global(.dashboard-dark) .vue-show-page {
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

.profile-card {
    position: relative;
    max-width: 920px;
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

.profile-card::after {
    content: "";
    position: absolute;
    right: -90px;
    top: -110px;
    width: 270px;
    height: 270px;
    background: radial-gradient(circle, rgba(37, 99, 235, .20), transparent 68%);
    pointer-events: none;
}

.profile-card > * {
    position: relative;
    z-index: 1;
}

.profile-header {
    display: flex;
    justify-content: space-between;
    gap: 22px;
    align-items: center;
    padding-bottom: 24px;
    margin-bottom: 24px;
    border-bottom: 1px solid var(--border);
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
    margin: 12px 0 0;
    color: var(--muted);
    font-size: 15px;
    line-height: 1.7;
}

.profile-photo {
    width: 138px;
    height: 138px;
    flex: 0 0 auto;
    object-fit: cover;
    border: 3px solid var(--card-solid);
    border-radius: 26px;
    box-shadow: 0 18px 42px rgba(24, 24, 27, .16);
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin: 0;
}

.details-grid div {
    padding: 17px;
    background: rgba(255, 255, 255, .42);
    border: 1px solid var(--border);
    border-radius: 18px;
    box-shadow: var(--soft-shadow);
    transition: transform .2s ease, border-color .2s ease, background .2s ease;
}

:global(.dashboard-dark) .details-grid div {
    background: rgba(24, 24, 27, .52);
}

.details-grid div:hover {
    transform: translateY(-2px);
    border-color: var(--ring);
}

dt {
    margin-bottom: 7px;
    color: var(--muted);
    font-size: 12px;
    font-weight: 850;
    text-transform: uppercase;
    letter-spacing: .06em;
}

dd {
    margin: 0;
    color: var(--primary);
    font-size: 15px;
    font-weight: 800;
    overflow-wrap: anywhere;
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
    .vue-show-page {
        padding: 22px 12px 42px;
    }

    .profile-card {
        padding: 22px;
        border-radius: 22px;
    }

    .profile-header,
    .details-grid {
        grid-template-columns: 1fr;
    }

    .profile-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .profile-photo {
        width: 118px;
        height: 118px;
    }

    .actions,
    .button {
        width: 100%;
    }
}
</style>
