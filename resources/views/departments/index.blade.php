<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div id="department-management-app" data-vue-component="DepartmentManagement"></div>
<script id="department-management-app-props" type="application/json">
    @json($props)
</script>
</body>
</html>