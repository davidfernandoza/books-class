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
				
				<Form @submit="saveCategory" :validation-schema="schema">
					<div class="modal-body row">
						<div class="col-12">
							<label for="name">Nombre</label>
							<Field type="text" name="name" id="name" class="form-control" v-model="category.name" />
							<ErrorMessage name="name" />
						</div>
						<div class="col-12 mt-3">
							<label for="email">Email</label>
							<Field type="text" name="email" id="email" class="form-control" />
							<ErrorMessage name="email" />
						</div>
						<div class="col-12 mt-3">
							<Field name="password" v-slot="{ field, valid, errorMessage }">
								<label for="password">Password</label>
								<textarea id="password" v-bind="field" rows="3" class="form-control"></textarea>
								<span v-if="!valid">{{ errorMessage }}</span>
							</Field>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
						<button type="submit" class="btn btn-primary">Almacenar</button>
					</div>
				</Form>
			</div>
		</div>

	</div>
</template>

<script>
import { handlerErrors, successMessage } from '@/helpers/Alerts.js'
import { ref, getCurrentInstance } from 'vue'
import { Field, ErrorMessage, Form } from 'vee-validate'
import * as yup from 'yup';


export default {
	props: ['category_data'],
	components: { Field, ErrorMessage, Form },
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



		// const nameRules = yup.string().required().min(8);

		const schema = yup.object({
			email: yup.string().required().email(),
			name: yup.string().required(),
			password: yup.string().required().min(8),
		});

		return { is_create, category, closeModal, saveCategory, schema }
	}

}
</script>
