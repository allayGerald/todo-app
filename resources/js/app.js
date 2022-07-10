import './bootstrap'
import { createApp } from 'vue'

import '../css/app.css'
import app from './Containers/Index.vue'
import router from './router'

createApp(app).use(router).mount("#app")
