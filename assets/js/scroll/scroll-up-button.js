import { scrollTo } from './scroll-to.js'

(() => {
  const scrollUpButton = document.querySelector('.scroll-up')

  if (!scrollUpButton) {
    return
  }

  scrollUpButton.addEventListener('click', () => {
    scrollTo(0)
    window.location.hash = 'top'
  })

  // EventListener to show/hide scroll to top button after "x" pixel.
  window.addEventListener('scroll', event => {
    if (window.scrollY <= 500) {
      scrollUpButton.classList.add('hidden')

      return
    }

    scrollUpButton.classList.remove('hidden')
  })
})()
