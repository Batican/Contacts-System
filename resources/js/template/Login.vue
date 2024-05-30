<template>
    <div>
        <v-app id="inspire" class="at-login">
            <v-main>
                <v-container class="fill-height" fluid>
                    <v-row justify="center" align="center">
                        <div class="w-50">
                            <div
                                class="text-h3 font-weight-bold text-primary text-center mb-5"
                            ></div>
                            <v-card
                                class="mx-auto pa-12 pb-8"
                                elevation="8"
                                max-width="448"
                                rounded="lg"
                            >
                                <p class="text-h5 text-black mb-2 at-welcome">
                                    Login
                                </p>
                                <div
                                    class="text-subtitle-1 text-medium-emphasis"
                                >
                                    Account
                                </div>

                                <v-text-field
                                    density="compact"
                                    placeholder="Email address"
                                    prepend-inner-icon="mdi-email-outline"
                                    variant="outlined"
                                    v-model="this.form.email"
                                ></v-text-field>

                                <div
                                    class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
                                >
                                    Password
                                </div>

                                <v-text-field
                                    :append-inner-icon="
                                        show_pass ? 'mdi-eye-off' : 'mdi-eye'
                                    "
                                    :type="show_pass ? 'text' : 'password'"
                                    density="compact"
                                    placeholder="Enter your password"
                                    prepend-inner-icon="mdi-lock-outline"
                                    variant="outlined"
                                    @click:append-inner="show_pass = !show_pass"
                                    v-model="this.form.password"
                                ></v-text-field>

                                <v-btn
                                    block
                                    class="mb-8"
                                    color="blue"
                                    size="large"
                                    variant="tonal"
                                    @click="loginUser()"
                                >
                                    Log In
                                </v-btn>

                                <v-card-text class="text-center">
                                    <a
                                        class="text-blue text-decoration-none"
                                        href="#"
                                        @click.prevent="
                                            this.$router.push('/register')
                                        "
                                        rel="noopener noreferrer"
                                        target="_blank"
                                    >
                                        Sign up now
                                        <v-icon
                                            icon="mdi-chevron-right"
                                        ></v-icon>
                                    </a>
                                </v-card-text>
                            </v-card>
                        </div>
                    </v-row>
                </v-container>
            </v-main>
            <notification ref="notify"></notification>
        </v-app>
    </div>
</template>
<script>
import notification from "../components/Notification.vue";
export default {
    components: {
        notification,
    },
    data: () => ({
        form: {
            email: "",
            password: "",
        },
        show_pass: false,
    }),

    methods: {
        loginUser() {
            this.$axios
                .post(`/api/login`, this.form)
                .then(({ data }) => {
                    localStorage.setItem("token", data.data.user_token);
                    if (data.data.user_token) {
                        this.successNotify("Login successfuly");
                        this.$router.push("/contacts");
                    }
                })
                .catch((err) => {
                    this.errorNotify(err.response.data.message);
                });
        },
    },
    mounted() {
        this.$root.$notify = this.$refs.notify.open;
    },
};
</script>
<style lang="scss" scoped>
.fill-height {
    min-height: 100%;
    height: auto !important; /* cross-browser */
    height: 100%; /* cross-browser */
}
.at-welcome {
    font-weight: bolder;
    color: #545454;
}
.at-login {
    background-color: #2196f3;
}
</style>
