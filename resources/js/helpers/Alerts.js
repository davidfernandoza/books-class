import Swal from 'sweetalert2'

export const handlerErrors = async error => {
	console.error(error)
	const status = error.response.status
	let options = null
	let errors_message = []

	switch (status) {
		case 422:
			{
				options = {
					icon: 'error',
					title: 'Error: Campos erroneos.',
					text: 'Llena correctamente el formulario.'
				}
				if (Array.isArray(error.response.data.errors)) {
					errors_message = error.response.data.errors
				} else {
					errors_message.push(error.response.data.errors)
				}
			}
			break
		case 404:
			options = {
				icon: 'error',
				title: 'Error: URL no encontrada.',
				text: 'Intenta utilizar otra URL.'
			}
			break
		case 403:
			options = {
				icon: 'error',
				title: 'Error: Usuario sin permisos',
				text: 'No tienes los permisos para ejecutar esta accion.'
			}
			break
		default:
			options = {
				icon: 'error',
				title: 'Erro de servido',
				text: 'Algo salio mal, espera que se revisara este error.'
			}
			break
	}
	await Swal.fire(options)
	return errors_message
}

export const successMessage = async (is_delete = false, reload = false) => {
	await Swal.fire({
		icon: 'success',
		title: 'Felicidades!',
		text: is_delete
			? 'Dato eliminado correctamente.'
			: 'Dato almacenado correctamente.'
	})
	if (reload) {
		window.location.reload()
	}
}

export const deleteMessage = async () => {
	const { isConfirmed } = await Swal.fire({
		icon: 'warning',
		title: 'Esta seguro de eliminar?',
		showCancelButton: true
	})
	return isConfirmed
}
