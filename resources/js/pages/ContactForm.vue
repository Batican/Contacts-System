<template>
    <div>
        <v-btn
            class="mt-1 mb-3"
            text
            size="small"
            @click="$router.push('/contacts')"
            prepend-icon="mdi-arrow-left"
        >
            Go Back
        </v-btn>
        <v-card>
            <v-card-title>
                <span class="text-h5">Add Contact</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field
                            label="Name*"
                            required
                            type="text"
                            class="pt-2"
                            :rules="nameRule"
                            v-model="contact.name"
                            variant="outlined"
                            density="compact"
                        />
                        <v-text-field
                            label="Company*"
                            required
                            type="text"
                            class="pt-2"
                            :rules="companyRule"
                            v-model="contact.company"
                            variant="outlined"
                            density="compact"
                        />
                        <v-text-field
                            label="Phone*"
                            required
                            type="text"
                            class="pt-2"
                            :rules="phoneRule"
                            v-model="contact.phone"
                            variant="outlined"
                            density="compact"
                        />
                        <v-text-field
                            label="Email*"
                            required
                            type="text"
                            class="pt-2"
                            :rules="emailRule"
                            v-model="contact.email"
                            variant="outlined"
                            density="compact"
                        />
                    </v-form>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green" variant="tonal" @click="validate()">
                    {{ contact.id ? "Update" : "Add" }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {
    components: {},
    data: (vm) => ({
        valid: true,
        contact: {
            id: null,
            name: "",
            company: "",
            phone: "",
            email: "",
        },
        nameRule: [(v) => !!v || "Name is required"],
        companyRule: [(v) => !!v || "Company is required"],
        phoneRule: [(v) => !!v || "Phone is required"],
        emailRule: [
            (v) => !!v || "Email is required",
            (v) => /.+@.+/.test(v) || "E-mail must be valid",
        ],
    }),
    mounted() {
        if (this.$route.params.id) {
            this.getContact();
        }
    },
    methods: {
        async validate() {
            const { valid } = await this.$refs.form.validate();

            if (valid) {
                this.saveContact();
            }
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        },
        getContact() {
            this.$axios
                .get(`/api/contacts/` + this.$route.params.id)
                .then(({ data }) => {
                    this.contact = data.data;
                });
        },
        saveContact() {
            if (this.contact.id) {
                this.$axios
                    .put("/api/contacts/" + this.contact.id, this.contact)
                    .then(({ data }) => {
                        this.successNotify("Updated contact");
                        this.$router.push("/contacts");
                    })
                    .catch((err) => {
                        this.errorNotify(err.response.data.message);
                    });
            } else {
                this.$axios
                    .post("/api/contacts", this.contact)
                    .then(({ data }) => {
                        this.successNotify("Created contact");
                        this.$router.push("/contacts");
                    })
                    .catch((err) => {
                        this.errorNotify(err.response.data.message);
                    });
            }
        },
    },
};
</script>
