import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import { aliases, mdi } from 'vuetify/lib/iconsets/mdi';
import { VDataTable, VDataTableServer } from 'vuetify/labs/VDataTable';
import '@mdi/font/css/materialdesignicons.css';
import * as directives from 'vuetify/directives';

const myCustomLightTheme = {
  dark: false,
  colors: {
    primary: '#305D47',
    secondary: '#60B98F',
    accent: '#9a809d',
    error: '#f53851',
    info: '#2196F3',
    success: '#27b460',
    warning: '#ffc409',
  },
};

export default createVuetify({
  components: {
    ...components,
    VDataTable,
    VDataTableServer,
  },
  directives,
  theme: {
    defaultTheme: 'myCustomLightTheme',
    themes: {
      myCustomLightTheme,
    },
  },
  icons: {
    defaultSet: 'mdi',
    sets: {
      mdi,
    },
  },
});
