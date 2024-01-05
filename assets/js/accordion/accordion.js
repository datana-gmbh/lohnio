'use strict'

const toggleButtons = document.querySelectorAll('.accordion-toggle-button')

const closeAllExcept = (button) => {
  toggleButtons.forEach(b => {
    const target = document.getElementById(b.getAttribute('data-target-id'))

    if (button.getAttribute('data-target-id') === target.id) {
      return
    }

    b.querySelector('.icon').innerHTML = '<svg class="h-6 w-6 text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" /></svg>'

    target.classList.add('hidden')
  })
}

toggleButtons.forEach(button => {
  const target = document.getElementById(button.getAttribute('data-target-id'))

  button.addEventListener('click', () => {
    if (target.classList.contains('hidden')) {
      target.classList.remove('hidden')
      button.querySelector('.icon').innerHTML = '<svg class="h-6 w-6 text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" /></svg>'

      closeAllExcept(button)
    } else {
      button.querySelector('.icon').innerHTML = '<svg class="h-6 w-6 text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" /></svg>'

      target.classList.add('hidden')
    }
  })
})
