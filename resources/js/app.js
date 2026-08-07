import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';
import Dashboard from './components/Dashboard.vue';
import DepartmentManagement from './components/DepartmentManagement.vue';
import StudentForm from './components/StudentForm.vue';
import StudentIndex from './components/StudentIndex.vue';
import StudentShow from './components/StudentShow.vue';
import WelcomePage from './components/WelcomePage.vue';
import LoginPage from './components/LoginPage.vue';
import RegisterPage from './components/RegisterPage.vue';

window.Alpine = Alpine;

const storedTheme = localStorage.getItem('dashboard-theme');
const initialTheme = storedTheme === 'light' || storedTheme === 'dark'
    ? storedTheme
    : (window.matchMedia?.('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

document.documentElement.classList.toggle('dashboard-dark', initialTheme === 'dark');
document.documentElement.classList.toggle('dashboard-light', initialTheme === 'light');

Alpine.start();

const components = {
    Dashboard,
    DepartmentManagement,
    StudentForm,
    StudentIndex,
    StudentShow,
    WelcomePage,
    LoginPage,
    RegisterPage,
};

document.querySelectorAll('[data-vue-component]').forEach((element) => {
    const componentName = element.dataset.vueComponent;
    const component = components[componentName];

    if (!component) {
        return;
    }

    const propsElement = document.getElementById(`${element.id}-props`);
    const props = propsElement ? JSON.parse(propsElement.textContent) : {};

    createApp(component, props).mount(element);
});