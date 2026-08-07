<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@php
    $studentRows = $students->getCollection()->map(function ($student) use ($mode) {
        $isTrash = $mode === 'trash';

        return [
            'id' => $student->id,
            'student_id' => $student->student_id,
            'fullName' => trim($student->first_name . ' ' . $student->last_name),
            'email' => $student->email,
            'courseName' => optional($student->enrolledCourse)->course_name ?? $student->course,
            'course_id' => $student->course_id,
            'departmentName' => optional(optional($student->enrolledCourse)->department)->name ?? 'N/A',
            'gender' => $student->gender,
            'year_level' => $student->year_level,
            'photoUrl' => $student->photo ? asset('storage/' . $student->photo) : null,
            'showUrl' => $isTrash ? null : route('students.show', $student),
            'editUrl' => $isTrash ? null : route('students.edit', $student),
            'deleteUrl' => $isTrash ? null : route('students.destroy', $student),
            'restoreUrl' => $isTrash ? route('students.restore', $student->id) : null,
            'forceDeleteUrl' => $isTrash ? route('students.force-delete', $student->id) : null,
            'deletedAt' => optional($student->deleted_at)->format('M d, Y h:i A'),
        ];
    });

    $props = [
        'students' => $studentRows,
        'createUrl' => route('students.create'),
        'indexUrl' => route('students.index'),
        'trashUrl' => route('students.trash'),
        'dashboardUrl' => route('dashboard'),
        'csrf' => csrf_token(),
        'initialSearch' => $search ?? '',
        'success' => session('success') ?? '',
        'pagination' => $students->links()->toHtml(),
        'filters' => $filters ?? [
            'courses' => [],
            'genders' => [],
            'yearLevels' => [],
        ],
        'currentFilters' => $currentFilters ?? [
            'search' => '',
            'gender' => '',
            'year_level' => '',
            'course_id' => '',
        ],
        'mode' => $mode ?? 'active',
    ];
@endphp

<div id="student-index-app" data-vue-component="StudentIndex"></div>
<script id="student-index-app-props" type="application/json">
    @json($props)
</script>
</body>
</html>