<template>
	<div class="card">
		<div class="card-header d-flex justify-content-end">
			<button class="btn btn-primary" @click="createCategory">Crear categoria</button>
		</div>
		<div class="card-body">
			<div class="table-responsive my-4 mx-2">
				<table class="table table-bordered" id="category_table">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(category, index) in categories" :key="index">
							<td>{{ category.name }}</td>
							<td class="d-flex justify-content-center">
								<button @click="editCategory(category)" class="btn btn-warning btn-sm" title="Editar">
									<i class="fas fa-pencil-alt"></i>
								</button>
								<button @click="deleteCategory(category)" class="btn btn-danger btn-sm ms-2" title="Eliminar">
									<i class="fas fa-trash-alt"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div v-if="load_modal">
		<category-modal :category_data="category" />
	</div>
</template>

<script>
import { handlerErrors, successMessage, deleteMessage } from '@/helpers/Alerts.js'
import HandlerModal from '@/helpers/HandlerModal.js'
import CategoryModal from './CategoryModal.vue'
import { ref, onMounted } from 'vue'
export default {
	components: { CategoryModal },
	/*
	* Setup se lanza en beforeCreated
	*/
	setup(/* props, context*/) {

		onMounted(() => index());

		const categories = ref(null)
		const category = ref({ hola: 'hola' })
		const table = ref(null)
		const { openModal, closeModal, load_modal } = HandlerModal()

		const index = async () => {
			await getCategories()
			mountedTable()
		}

		const getCategories = async () => {
			try {
				const { data } = await axios.get('/categories/get-all')
				categories.value = data.categories
			} catch (error) {
				await handlerErrors(error)
			}
		}

		const mountedTable = () => {
			table.value = $('#category_table').DataTable()
		}

		const editCategory = async ({ id }) => {
			try {
				const { data } = await axios.get(`/categories/${id}`)
				category.value = data.category
				await openModal('category_modal')
			} catch (error) {
				await handlerErrors(error)
			}
		}

		const createCategory = async () => {
			category.value = null
			await openModal('category_modal')
		}

		const deleteCategory = async ({ id }) => {
			if (!await deleteMessage()) return
			try {
				await axios.delete(`/categories/${id}`)
				await successMessage(true, false)
				reloadState()
			} catch (error) {
				await handlerErrors(error)
			}
		}

		const reloadState = () => {
			table.value.destroy()
			index()
		}

		// index()
		return {
			categories,
			category,
			load_modal,
			editCategory,
			deleteCategory,
			createCategory,
			closeModal,
			reloadState
		}
	}
}
</script>


