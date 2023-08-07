<template>
	<!-- Modal -->
	<div class="modal fade" id="category_modal" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ is_create ? 'Crear' : 'Editar' }} categoria</h5>
					<button type="button" class="btn-close" @click="closeModal" aria-label="Close">
					</button>
				</div>
				<form @submit.prevent="saveCategory">
					<div class="modal-body row">
						<div class="col-12">
							<label for="name">Nombre</label>
							<input type="text" v-model="category.name" class="form-control" id="name">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
						<button type="submit" class="btn btn-primary">Almacenar</button>
					</div>
				</form>
			</div>
		</div>

	</div>
</template>

<script>
import { handlerErrors, successMessage } from '@/helpers/Alerts.js'
import { ref, getCurrentInstance } from 'vue'

export default {
	props: ['category_data'],
	setup({ category_data }) {
		const instance = getCurrentInstance();
		const is_create = category_data ? ref(false) : ref(true)
		const category = !is_create.value ? ref(category_data) : ref({})

		const closeModal = () => {
			instance.parent.ctx.closeModal()
		}

		const saveCategory = async () => {
			try {
				if (is_create.value) {
					await axios.post('/categories', category.value)
				} else {
					await axios.put(`/categories/${category.value.id}`, category.value)
				}
				await successMessage()
				succesRespose()
			} catch (error) {
				await handlerErrors(error)
			}
		}

		const succesRespose = () => {
			instance.parent.ctx.reloadState()
			closeModal()
		}

		return { is_create, category, closeModal, saveCategory }
	}

}
</script>
