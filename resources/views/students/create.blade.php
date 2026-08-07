<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@php
    $courseRows = $courses->map(function ($course) {
        return [
            'id' => $course->id,
            'course_name' => $course->course_name,
            'department_name' => optional($course->department)->name,
        ];
    });

    $studentData = [
        'student_id' => old('student_id', ''),
        'first_name' => old('first_name', ''),
        'last_name' => old('last_name', ''),
        'birthday' => old('birthday', ''),
        'gender' => old('gender', ''),
        'email' => old('email', ''),
        'phone' => old('phone', ''),
        'course_id' => old('course_id', ''),
        'course' => old('course', ''),
        'year_level' => old('year_level', ''),
        'photoUrl' => '',
    ];

    $props = [
        'title' => 'Add Student',
        'action' => route('students.store'),
        'method' => 'POST',
        'submitLabel' => 'Save Student',
        'cancelUrl' => route('students.index'),
        'csrf' => csrf_token(),
        'student' => $studentData,
        'courses' => $courseRows,
        'errors' => $errors->all(),
    ];
@endphp

<div id="student-form-app" data-vue-component="StudentForm"></div>
<script id="student-form-app-props" type="application/json">
    @json($props)
</script>
</body>
</html>