import './bootstrap'
import { createApp } from 'vue'
import vSelect from 'vue-select'

// Components ---------------------------------------------------
import TheBookList from './components/Book/TheBookList.vue'
import TheCaregoryList from './components/Category/TheCaregoryList.vue'

const app = createApp({
	components: {
		TheBookList,
		TheCaregoryList
	}
})

app.component('v-select', vSelect)
app.mount('#app')
