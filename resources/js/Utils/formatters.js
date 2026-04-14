/**
 * Formats a numeric value into a Canadian Currency (CAD) string.
 *
 * @param {number|string|null} value
 * @returns {string}
 */
export const formatPrice = (value) => {
  return new Intl.NumberFormat('en-CA', {
    style: 'currency',
    currency: 'CAD',
    maximumFractionDigits: 0
  }).format(value ?? 0);
};
