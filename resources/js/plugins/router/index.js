import { createRouter, createWebHistory } from "vue-router";

import Main from "../../template/Main.vue";
import Login from "../../template/Login.vue";
import NotFound from "../../pages/NotFound.vue";
import Register from "../../template/Register.vue";
import store from "../store";

const routes = [
    {
        path: "/",
        component: Main,
        meta: {
            requiresAuth: true,
            permissionSlugs: [],
        },
        children: [
            {
                path: "",
                name: "main",
                redirect: "/contacts",
            },
            {
                path: "/contacts",
                name: "contacts",
                component: () => import("../../pages/Contacts.vue"),
            },
            {
                path: "/contacts/create",
                name: "contact_create",
                component: () => import("../../pages/ContactForm.vue"),
            },
            {
                path: "/contacts/edit/:id",
                name: "contact_edit",
                component: () => import("../../pages/ContactForm.vue"),
            },
        ],
    },
    {
        path: "/:pathMatch(.*)*",
        component: NotFound,
        meta: {
            permissionSlugs: [],
        },
    },
    {
        path: "/login",
        name: "login",
        component: Login,
        meta: {
            requiresAuth: false,
            permissionSlugs: [],
        },
    },
    {
        path: "/register",
        name: "register",
        component: Register,
        meta: {
            requiresAuth: false,
            permissionSlugs: [],
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    localStorage.setItem("from", from.fullPath);

    let user = null;

    try {
        user = await store.dispatch("updateUser");
    } catch (error) {
        user = null;
    }
    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);

    if (!requiresAuth && user) {
        next("/contacts");
    } else if (requiresAuth && !user) {
        next("/login");
    } else {
        next();
    }
});

export default router;
