<template>
	<!-- Modal -->
	<div class="modal fade" id="books_modal" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="books_modal">{{is_create?'Crear':'Editar'}} Libro</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<section class="row">
						<div class="col-12">
							<label for="title">Titulo</label>
							<input type="text" v-model="book.title" class="form-control" id="title">
						</div>
						<div class="col-12 mt-2">
							<label for="stock">Cantidad</label>
							<input type="number" v-model="book.stock" class="form-control" id="stock">
						</div>
						<div class="col-12 mt-2">
							<label for="description">Descripcion</label>
							<textarea v-model="book.description" class="form-control" id="description" rows="3"></textarea>
						</div>
						<div class="col-12 mt-2">
							<label for="author">Autor</label>
							<v-select id="author" :options="authors_data" v-model="author" :reduce="author => author.id" label="name" :clearable="false" placeholder="Selecciona autor">
								<template #search="{attributes, events}">
									<input class="vs__search" :required="!author" v-bind="attributes" v-on="events">
								</template>
							</v-select>
						</div>
						<div class="col-12 mt-2">
							<label for="category">Categoria</label>
							<v-select id="category" :options="categories_data" v-model="category" :reduce="category => category.id" label="name" :clearable="false" placeholder="Selecciona categoria">
								<template #search="{attributes, events}">
									<input class="vs__search" :required="!category" v-bind="attributes" v-on="events">
								</template>
							</v-select>
						</div>
					</section>
				</div>
				<div class="modal-footer mt-3">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" @click="saveBook">Almacenar</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Swal from 'sweetalert2'
	export default {
		props: ['book_data', 'authors_data', 'categories_data'],
		watch: {
			book_data(new_value) {
				this.book = new_value
				this.is_create = false
				this.author = this.book.author_id
				this.category = this.book.category_id
			}
		},
		data() {
			return {
				book: {},
				is_create: true,
				author: null,
				category: null
			}
		},
		methods: {
			async saveBook() {
				try {
					this.book.author_id = this.author
					this.book.category_id = this.category
					if (this.is_create) await axios.post('/books', this.book)
					else await axios.put(`/books/${this.book.id}`, this.book)
					await Swal.fire({ icon: 'success', title: 'Felicidades', text: 'Libro almacenado' })
					window.location.reload()
				} catch (error) {
					console.log(error)
					Swal.fire({ icon: 'error', title: 'Oops...', text: 'Algo salió mal' })
				}
			},

			reset() {
				this.is_create = true
				this.author = null
				this.category = null
				this.book = {}
			}
		}
	}
</script>

<style>
</style>
