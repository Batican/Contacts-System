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
                                <v-card-text>
                                    <v-form
                                        ref="form"
                                        v-model="valid"
                                        lazy-validation
                                    >
                                        <div class="ma-2">
                                            <p
                                                class="text-h5 text-black mb-0 at-welcome"
                                            >
                                                Create Account
                                            </p>

                                            <v-form class="mt-7">
                                                <v-text-field
                                                    v-model="form.email"
                                                    label="Email"
                                                    name="login"
                                                    required
                                                    :rules="emailRule"
                                                    prepend-inner-icon="mdi-account"
                                                    type="text"
                                                    density="compact"
                                                    variant="outlined"
                                                ></v-text-field>
                                                <v-text-field
                                                    v-model="form.name"
                                                    label="Name"
                                                    name="name"
                                                    required
                                                    :rules="nameRule"
                                                    prepend-inner-icon="mdi-account"
                                                    type="text"
                                                    density="compact"
                                                    variant="outlined"
                                                ></v-text-field>

                                                <v-text-field
                                                    label="Password"
                                                    :required="true"
                                                    :rules="passwordRule"
                                                    class="pt-2"
                                                    :type="
                                                        show_pass
                                                            ? 'text'
                                                            : 'password'
                                                    "
                                                    :append-inner-icon="
                                                        show_pass
                                                            ? 'mdi-eye'
                                                            : 'mdi-eye-off'
                                                    "
                                                    @click:append-inner="
                                                        show_pass = !show_pass
                                                    "
                                                    prepend-inner-icon="mdi-lock-outline"
                                                    type="text"
                                                    density="compact"
                                                    variant="outlined"
                                                    v-model="form.password"
                                                ></v-text-field>
                                                <v-text-field
                                                    label="Confirm Password"
                                                    :required="true"
                                                    :rules="confirmPasswordRule"
                                                    class="pt-2"
                                                    :type="
                                                        show_confirm_pass
                                                            ? 'text'
                                                            : 'password'
                                                    "
                                                    :append-inner-icon="
                                                        show_confirm_pass
                                                            ? 'mdi-eye'
                                                            : 'mdi-eye-off'
                                                    "
                                                    @click:append-inner="
                                                        show_confirm_pass =
                                                            !show_confirm_pass
                                                    "
                                                    v-model="
                                                        form.confirm_password
                                                    "
                                                    prepend-inner-icon="mdi-lock-outline"
                                                    type="text"
                                                    variant="outlined"
                                                    density="compact"
                                                ></v-text-field>
                                            </v-form>
                                            <v-row
                                                justify="center"
                                                class="mr-1 mt-2"
                                            >
                                                <v-btn
                                                    block
                                                    class="mb-8"
                                                    color="blue"
                                                    size="large"
                                                    variant="tonal"
                                                    @click="validate()"
                                                    >Register</v-btn
                                                >
                                                <div class="d-flex">
                                                    <p class="mr-2">
                                                        Already have an account?
                                                    </p>
                                                    <a
                                                        class="text-blue text-decoration-none"
                                                        href="#"
                                                        @click.prevent="
                                                            this.$router.back()
                                                        "
                                                    >
                                                        Login Here
                                                    </a>
                                                </div>
                                            </v-row>
                                        </div>
                                    </v-form>
                                </v-card-text>
                            </v-card>
                        </div>
                    </v-row>
                </v-container>
                <v-dialog v-model="thanksDialog" width="500">
                    <v-card>
                        <v-card-text class="pa-4"
                            >Thank you for registering</v-card-text
                        >
                        <v-card-actions class="pt-0">
                            <v-spacer></v-spacer>
                            <v-btn
                                text
                                @click.native="agree"
                                @click="
                                    (thanksDialog = false),
                                        $router.push('/contacts')
                                "
                                >Continue</v-btn
                            >
                        </v-card-actions>
                    </v-card>
                </v-dialog>
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
    data: (vm) => ({
        valid: true,
        thanksDialog: false,
        form: {
            email: "",
            password: "",
            confirm_password: "",
            name: "",
        },
        nameRule: [(v) => !!v || "Name is required"],
        passwordRule: [(v) => !!v || "Password is required"],
        confirmPasswordRule: [
            (v) => !!v || "type confirm password",
            (v) =>
                v === vm.form.password ||
                "The password confirmation does not match.",
        ],
        emailRule: [
            (v) => !!v || "Email is required",
            (v) => /.+@.+/.test(v) || "E-mail must be valid",
        ],
        show_pass: false,
        show_confirm_pass: false,
    }),

    methods: {
        async validate() {
            const { valid } = await this.$refs.form.validate();

            if (valid) {
                this.registerUser();
            }
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        },
        registerUser() {
            this.$axios
                .post(`/api/register`, this.form)
                .then(({ data }) => {
                    localStorage.setItem("token", data.data.user_token);
                    if (data.data.user_token) {
                        this.thanksDialog = true;
                        this.successNotify("Registered Successfully");
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
.image-login {
    width: 100%;
    height: 100vh;
}
.at-welcome {
    font-weight: bolder;
    color: #545454;
}
.at-login-form {
    background-color: gray;
    backdrop-filter: blur(50px);
}

.at-login {
    background-color: #2196f3;
}
</style>
