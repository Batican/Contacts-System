<template>
    <v-app id="inspire">
        <v-app-bar app absolute color="blue" class="text-black">
            <v-toolbar-title>Contacts System</v-toolbar-title>
            <v-btn
                v-for="(link, i) in links"
                :key="i"
                text
                @click="$router.push(link.link)"
            >
                {{ link.text }}
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn small @click.stop="logoutUser">
                <v-icon> mdi-logout </v-icon>
                Logout
            </v-btn>
        </v-app-bar>
        <!-- <v-main class="light-green accent-2 "> -->
        <v-main class="yellow lighten-1">
            <v-container>
                <router-view v-slot="{ Component }">
                    <transition
                        name="custom-classes-transition"
                        enter-active-class="animated slideInRight"
                        leave-active-class="animated slideOutLeft"
                        mode="out-in"
                    >
                        <component :is="Component" />
                    </transition>
                </router-view>
            </v-container>
        </v-main>
        <notification ref="notify"></notification>
        <confirm ref="confirm"></confirm>
    </v-app>
</template>
<script>
import notification from "../components/Notification.vue";
import confirm from "../components/Confirm.vue";
export default {
    components: {
        notification,
        confirm,
    },
    data: () => ({
        links: [
            { text: "Add Contact", link: "contacts/create" },
            { text: "Contacts", link: "contacts" },

            // { text : 'Enrolled Subjects',link : '/enrolled_subjects'},
        ],
    }),
    methods: {
        logoutUser() {
            this.$axios.post("/api/logout").then(({ data }) => {
                localStorage.removeItem("token");
                this.$router.push(`/login`);
            });
        },
    },
    mounted() {
        this.$root.$notify = this.$refs.notify.open;
        this.$root.$confirm = this.$refs.confirm.open;
    },
};
</script>
