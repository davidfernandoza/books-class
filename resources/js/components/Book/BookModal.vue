<template>
	<!-- Modal -->
	<div class="modal fade" id="books_modal" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ is_create ? 'Crear' : 'Editar' }} libro</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<!-- Backend error -->
				<BackendError :errors="back_errors" />

				<Form @submit="saveBook" :validation-schema="schema" ref="form">
					<div class="modal-body">
						<section class="row">

							<!-- Image -->
							<div class="col-12 d-flex justify-content-center mt-1">
								<img :src="image_preview" alt="Imagen Libro" class="img-thumbnail" width="170" height="170">
							</div>

							<!-- Load Image -->
							<div class="col-12 mt-1 ">
								<label for="file" class="form-label">Imagen</label>
								<input type="file" :class="`form-control ${back_errors['file'] ? 'is-invalid' : ''}`" id="file"
									accept="image/*" @change="previewImage">
								<span class="invalid-feedback" v-if="back_errors['file']">
									{{ back_errors['file'] }}
								</span>
							</div>


							<!-- Title -->
							<div class="col-12 mt-2">
								<label class="form-label" for="title">Titulo</label>
								<Field name="title" v-slot="{ errorMessage, field }" v-model="book.title">
									<input type="text" id="title" v-model="book.title"
										:class="`form-control ${errorMessage || back_errors['title'] ? 'is-invalid' : ''}`" v-bind="field">
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback" v-if="back_errors['title']">
										{{ back_errors['title'] }}
									</span>
								</Field>
							</div>

							<!-- Stock -->
							<div class="col-12 mt-2">
								<Field name="stock" v-slot="{ errorMessage, field }" v-model="book.stock">
									<label class="form-label" for="stock">Cantidad</label>
									<input type="number" id="stock" v-model="book.stock"
										:class="`form-control ${errorMessage || back_errors['stock'] ? 'is-invalid' : ''}`" v-bind="field">
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback" v-if="back_errors['stock']">
										{{ back_errors['stock'] }}
									</span>
								</Field>
							</div>

							<!-- Description -->
							<div class="col-12 mt-2">
								<Field name="description" v-slot="{ errorMessage, field }" v-model="book.description">
									<label class="form-label" for="description">Descripcion</label>
									<textarea v-model="book.description"
										:class="`form-control ${errorMessage || back_errors['description'] ? 'is-invalid' : ''}`"
										id="description" rows="3" v-bind="field"></textarea>
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback" v-if="back_errors['description']">
										{{ back_errors['description'] }}
									</span>
								</Field>
							</div>

							<!-- Author -->
							<div class="col-12 mt-2">
								<Field name="author" v-slot="{ errorMessage, field }" v-model="author">
									<label class="form-label" for="author">Autor</label>
									<v-select :options="authors_data" label="name" v-model="author" :reduce="author => author.id"
										v-bind="field" placeholder="Seleccione autor" :clearable="false"
										:class="`${errorMessage || back_errors['author_id'] ? 'is-invalid' : ''}`">
									</v-select>
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback" v-if="back_errors['author_id']">
										{{ back_errors['author_id'] }}
									</span>
								</Field>
							</div>

							<!-- Category -->
							<div class="col-12 mt-2">
								<Field name="category" v-slot="{ errorMessage, field, valid }" v-model="category">
									<label class="form-label" for="category">Categoria</label>
									<v-select id="category" :options="categories_data" v-model="category" :reduce="category => category.id"
										v-bind="field" label="name" placeholder="Selecciona categoria" :clearable="false"
										:class="`${errorMessage || back_errors['category_id'] ? 'is-invalid' : ''}`">
									</v-select>
									<span class="invalid-feedback" v-if="!valid">{{ errorMessage }}</span>
									<span class="invalid-feedback" v-if="back_errors['category_id']">
										{{ back_errors['category_id'] }}
									</span>
								</Field>
							</div>
						</section>
					</div>

					<!-- Buttons -->
					<div class="modal-footer mt-3">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary">Almacenar</button>
					</div>
				</Form>
			</div>
		</div>
	</div>
</template>

<script>
import { handlerErrors, successMessage } from '@/helpers/Alerts.js'
import { Field, ErrorMessage, Form } from 'vee-validate'
import * as yup from 'yup';

export default {
	props: ['book_data', 'authors_data', 'categories_data'],
	components: {
		Field, ErrorMessage, Form
	},
	watch: {
		book_data(new_value) {
			this.book = new_value
			if (!this.book.id) return
			this.is_create = false
			this.author = this.book.author_id
			this.category = this.book.category_id
			this.image_preview = this.book.file.route

		}
	},
	computed: {
		schema() {
			return yup.object({
				title: yup.string().required(),
				stock: yup.number().required().positive().integer(),
				description: yup.string().required(),
				author: yup.string().required(),
				category: yup.string().required()
			});
		}
	},
	data() {
		return {
			book: {},
			is_create: true,
			author: null,
			category: null,
			back_errors: {},
			image_preview: '/storage/images/books/default.png',
			file: null
		}
	},
	methods: {
		async saveBook() {
			try {
				this.book.author_id = this.author
				this.book.category_id = this.category
				const book = this.createFormData(this.book)
				if (this.is_create) await axios.post('/books/store', book)
				else await axios.post(`/books/update/${this.book.id}`, book)
				await successMessage({ is_delete: false, reload: true })
			} catch (error) {
				this.back_errors = await handlerErrors(error)
			}
		},
		createFormData(data) {
			const form_data = new FormData()
			if (this.file) form_data.append('file', this.file, this.file.name)
			for (const key in data) {
				form_data.append(key, data[key])
			}
			return form_data
		},
		previewImage(event) {
			this.file = event.target.files[0]
			this.image_preview = URL.createObjectURL(this.file)
		},
		reset() {
			this.is_create = true
			this.author = null
			this.category = null
			this.book = {}
			this.back_errors = {}
			this.file = null
			this.$parent.book = {}
			this.image_preview = '/storage/images/books/default.png'
			document.getElementById('file').value = ''
			setTimeout(() => this.$refs.form.resetForm(), 100);
		}
	}
}
</script>

<style></style>
