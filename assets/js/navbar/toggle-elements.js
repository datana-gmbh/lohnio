document.addEventListener('DOMContentLoaded', () => {
  const elements = document.querySelectorAll('.toggle-button')

  if (!elements) {
    return
  }

  elements.forEach(button => {
    if (button.hasAttribute('disabled')) {
      return
    }

    const target = document.getElementById(button.getAttribute('data-target-id'))

    button.addEventListener('click', () => {
      target.classList.toggle('hidden')
    })

    // close menu if document is clicked
    document.addEventListener('mouseup', event => {
      if (event.target === target || target.contains(event.target)) {
        return
      }

      if (event.target === button || button.contains(event.target)){
        return
      }

      target.classList.add('hidden')
    })
  })
})
