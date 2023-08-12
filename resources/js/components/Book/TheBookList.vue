<template>
	<div class="card">
		<div class="card-header d-flex justify-content-end">
			<button class="btn btn-primary" @click="openModal">Crear libro</button>
		</div>
		<div class="card-body">
			<div class="table-responsive my-4 mx-2">
				<table class="table table-bordered" id="book_table">
					<thead>
						<tr>
							<th>Imagen</th>
							<th>Titulo</th>
							<th>Autor</th>
							<th>Cantidad</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(book, index) in books" :key="index">
							<td>
								<div class="d-flex justify-content-center">
									<img :src="book.file.route" alt="Imagen Libro" class="img-thumbnail" width="40" height="40">
								</div>
							</td>
							<td>{{ book.title }}</td>
							<td>{{ book.author.name }}</td>
							<td>{{ book.stock }}</td>
							<td>
								<div class="d-flex justify-content-center">
									<button @click="editBook(book)" class="btn btn-warning btn-sm" title="Editar">
										<i class="fas fa-pencil-alt"></i>
									</button>
									<button @click="deleteBook(book)" class="btn btn-danger btn-sm ms-2" title="Eliminar">
										<i class="fas fa-trash-alt"></i>
									</button>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div>
			<book-modal ref="book_modal" :book_data="book" :categories_data="categories" :authors_data="authors" />
		</div>
	</div>
</template>

<script>
import { handlerErrors, successMessage, deleteMessage } from '@/helpers/Alerts.js'
import BookModal from './BookModal.vue'
export default {
	props: ['books', 'categories', 'authors'],
	components: { BookModal },
	data() {
		return {
			modal: null,
			load_modal: true,
			book: {}
		}
	},
	mounted() {
		this.index()
	},
	methods: {
		index() {
			$('#book_table').DataTable()
			const modal_id = document.getElementById('books_modal')
			this.modal = new bootstrap.Modal(modal_id)
			modal_id.addEventListener('hidden.bs.modal', e => {
				this.$refs.book_modal.reset()
			})
		},
		editBook(book) {
			this.book = book
			this.openModal()
		},
		async deleteBook({ id }) {
			if (!await deleteMessage()) return
			try {
				await axios.delete(`/books/${id}`)
				await successMessage({ is_delete: true, reload: true })
			} catch (error) {
				await handlerErrors(error)
			}
		},
		openModal() {
			this.modal.show()
		}
	}
}
</script>

<style></style>
