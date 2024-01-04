/**
 * @param {Number} targetOffset
 */
export const scrollTo = (targetOffset) => {
  let i = window.scrollY
  const interval = setInterval(() => {
    window.scrollTo(0, i)

    if (window.scrollY <= targetOffset) {
      i += 60

      if (i >= targetOffset) {
        clearInterval(interval)
      }
    } else {
      i -= 60

      if (i <= targetOffset) {
        clearInterval(interval)
      }
    }
  }, 8)
}
