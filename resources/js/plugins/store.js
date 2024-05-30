import { createStore } from "vuex";

import axios from "./axios.js";

const store = createStore({
    state: () => ({
        user: {
            id: null,
        },
    }),
    getters: {
        user(state) {
            return state.user;
        },
    },
    actions: {
        async updateUser({ commit }) {
            try {
                if (localStorage.getItem("token")) {
                    const response = await axios.get("/api/me");
                    commit("SET_USER", response.data);
                    console.log(response.data, "user");
                    return response.data;
                }
            } catch (error) {
                console.log(error);
                commit("SET_USER", { id: null });
                localStorage.removeItem("token");
                localStorage.removeItem("userType");
            }
        },
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user;
        },
    },
});

export default store;
