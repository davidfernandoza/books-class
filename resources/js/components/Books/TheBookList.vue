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
							<th>Titulo</th>
							<th>Autor</th>
							<th>Cantidad</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(book, index) in books" :key="index">
							<td>{{book.title}}</td>
							<td>{{book.author.name}}</td>
							<td>{{book.stock}}</td>
							<td class="d-flex justify-content-center">
								<button @click="editBook(book)" class="btn btn-warning btn-sm" title="Editar">
									<i class="fas fa-pencil-alt"></i>
								</button>
								<button @click="deleteBook(book)" class="btn btn-danger btn-sm ms-2" title="Eliminar">
									<i class="fas fa-trash-alt"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div>
			<books-modal ref="book_modal" :book_data="book" :categories_data="categories" :authors_data="authors" />
		</div>
	</div>
</template>

<script>
	import BooksModal from './BooksModal.vue'
	export default {
		props: ['books', 'categories', 'authors'],
		components: { BooksModal },
		data() {
			return {
				modal: null,
				load_modal: true,
				book: null
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
				const { isConfirmed } = await Swal.fire({
					icon: 'warning',
					title: 'Esta seguro de eliminar?',
					showCancelButton: true
				})
				if (!isConfirmed) return
				try {
					await axios.delete(`/books/${id}`)
					await Swal.fire({ icon: 'success', title: 'Felicidades', text: 'Libro eliminado' })
					window.location.reload()
				} catch (error) {
					console.error(error)
					Swal.fire({ icon: 'error', title: 'Oops...', text: 'Algo salió mal' })
				}
			},
			openModal() {
				this.modal.show()
			}
		}
	}
</script>

<style>
</style>
