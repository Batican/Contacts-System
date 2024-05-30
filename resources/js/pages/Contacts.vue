<template>
    <div>
        <v-toolbar flat primary>
            <v-toolbar-title>Contacts</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-text-field
                v-model="searchKey"
                hide-details
                placeholder="Search"
                class="ma-2"
                density="compact"
                prepend-inner-icon="mdi-magnify"
            ></v-text-field>
        </v-toolbar>
        <v-table>
            <thead>
                <tr>
                    <th class="text-left">Name</th>
                    <th class="text-left">Company</th>
                    <th class="text-left">Phone</th>
                    <th class="text-left">Email</th>
                    <th class="text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in serverItems" :key="item.id">
                    <td>{{ item.name }}</td>
                    <td>{{ item.company }}</td>
                    <td>{{ item.phone }}</td>
                    <td>{{ item.email }}</td>
                    <td>
                        <v-btn
                            color="warning"
                            size="small"
                            class="me-2"
                            @click="$router.push('/contacts/edit/' + item.id)"
                        >
                            EDIT
                        </v-btn>
                        <v-btn
                            size="small"
                            color="error"
                            @click="deleteContact(item)"
                        >
                            delete
                        </v-btn>
                    </td>
                </tr>
            </tbody>
        </v-table>
        <v-pagination
            v-model="options.page"
            :length="lastPage"
            rounded="circle"
        ></v-pagination>
    </div>
</template>
<script>
export default {
    data: () => ({
        searchKey: "",
        lastPage: 0,
        options: {
            itemsPerPage: 5,
            page: 1,
            sortBy: [],
        },
        contact: {
            id: null,
            name: "",
            company: "",
            phone: "",
            email: "",
        },

        serverItems: [],
        loading: true,
        totalItems: 0,
        relatedData: [],
        related_id: null,
    }),
    watch: {
        searchKey() {
            this.loadContacts();
        },
        "options.page": {
            handler() {
                this.loadContacts();
                console.log("test");
            },
        },
    },

    created() {
        this.loadContacts();
    },
    methods: {
        loadContacts({ page, itemsPerPage } = this.options) {
            let params = {
                page: page,
                per_page: itemsPerPage,
                search_key: this.searchKey,
            };

            this.$axios
                .get("/api/contacts", { params })
                .then(({ data }) => {
                    this.serverItems = data.data.data;
                    this.totalItems = data.data.total;
                    this.lastPage = data.data.last_page;
                    this.options = {
                        itemsPerPage: data.data.per_page,
                        page: data.data.current_page,
                    };
                })
                .catch((err) => {
                    this.errorNotify(err.response.data.message);
                });
        },

        async deleteContact(contact) {
            let confirm = await this.deleteRecord(contact.name);
            if (!confirm) return;

            this.$axios
                .delete("/api/contacts/" + contact.id)
                .then(({ data }) => {
                    this.loadContacts(this.options);
                    this.successNotify("Deleted contact");
                })
                .catch((err) => {
                    this.errorNotify(err.response.data.message);
                });
        },
    },
};
</script>
