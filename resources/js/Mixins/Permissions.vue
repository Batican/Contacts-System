<script>
export default {
  data: (vm) => ({}),

  methods: {
    checkPermission(permissionToCheck) {
      let user = this.$store.getters.user;

      return user.permission_slugs.includes(permissionToCheck);
    },
  },
};
</script>
