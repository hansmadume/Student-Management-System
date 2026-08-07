<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@php
    $props = [
        'student' => [
            'id' => $student->id,
            'student_id' => $student->student_id,
            'fullName' => trim($student->first_name . ' ' . $student->last_name),
            'birthday' => optional($student->birthday)->format('Y-m-d') ?? $student->birthday,
            'gender' => $student->gender,
            'email' => $student->email,
            'phone' => $student->phone,
            'courseName' => optional($student->enrolledCourse)->course_name ?? $student->course,
            'departmentName' => optional(optional($student->enrolledCourse)->department)->name ?? 'N/A',
            'year_level' => $student->year_level,
            'photoUrl' => $student->photo ? asset('storage/' . $student->photo) : null,
            'editUrl' => route('students.edit', $student),
        ],
        'indexUrl' => route('students.index'),
    ];
@endphp

<div id="student-show-app" data-vue-component="StudentShow"></div>
<script id="student-show-app-props" type="application/json">
    @json($props)
</script>
</body>
</html>