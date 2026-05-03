import { toast } from 'vue3-toastify'

export function useNotification() {
  const notify = (message, type = 'success', timeout = 3000) => {
    toast(message, {
      type: type, // 'success', 'error', 'info', 'warning'
      autoClose: timeout,
    })
  }

  const notifyError = (message) => {
    notify(message, 'error', 5000)
  }

  const notifySuccess = (message) => {
    notify(message, 'success', 3000)
  }

  const handleValidationError = (error) => {
    if (error.response && error.response.status === 422) {
      const errors = error.response.data.errors
      const firstField = Object.keys(errors)[0]
      const firstError = errors[firstField][0]
      notifyError(`${firstError}`)
    } else {
      notifyError(error.response?.data?.message || "Ocorreu um erro inesperado")
    }
  }

  return {
    notify,
    notifyError,
    notifySuccess,
    handleValidationError
  }
}
