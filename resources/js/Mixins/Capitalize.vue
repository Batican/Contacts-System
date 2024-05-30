<script>
export default {
  data: (vm) => ({}),

  methods: {
    toCapitalize(string) {
      if(string.includes('-')) {
              let final = []
              string.split('-').filter(n => n).forEach((ell) => {
              final.push(ell.charAt(0).toUpperCase() + ell.slice(1))
          })
          return final.join(' ')
      } else {
        return string.charAt(0).toUpperCase() + string.slice(1);
      }
    },
  },
};
</script>
