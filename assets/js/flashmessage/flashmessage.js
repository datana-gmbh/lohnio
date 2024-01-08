const duration = 60000 // 60 seconds

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.flash-message')
    .forEach(flashMessage => {
      flashMessage.querySelector('button').addEventListener('click', () => {
        flashMessage.classList.add('hidden')
      })

      setTimeout(() => {
        flashMessage.classList.add('hidden')
      }, duration)
    })
})
