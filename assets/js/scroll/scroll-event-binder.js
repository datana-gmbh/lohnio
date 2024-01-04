import { scrollTo } from './scroll-to.js'

/**
 * EventListener to scroll to element via URL hash.
 */
document.addEventListener('DOMContentLoaded', () => {
  const targetId = window.location.hash

  if (!targetId) {
    return
  }

  const target = document.getElementById(targetId.replace('#', ''))

  if (!target) {
    return
  }

  scrollTo(target.offsetTop)
})

/**
 * All ".scroll-to" elements get an EventListener on click.
 * Elements with class "scroll-to" must have the href attribute.
 */
document.querySelectorAll('.scroll-to').forEach(link => {
  const attribute = link.getAttribute('href')

  const target = document.getElementById(attribute.slice((attribute.indexOf('#') + 1), attribute.length))

  if (!target) {
    return
  }

  link.addEventListener('click', event => {
    scrollTo(target.offsetTop)
  })
})
