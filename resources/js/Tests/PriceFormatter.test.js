import { describe, it, expect } from 'vitest';
import { formatPrice } from '@/Utils/formatters';

describe('Price Formatter Logic', () => {
  it('formats large numbers into CAD currency strings', () => {
    // Note: Intl.NumberFormat often uses non-breaking spaces (\u00A0)
    // depending on the environment, but for en-CA it's usually standard $
    expect(formatPrice(50000)).toContain('$50,000');
  });

  it('defaults to $0 when the value is null or undefined', () => {
    expect(formatPrice(null)).toContain('$0');
    expect(formatPrice(undefined)).toContain('$0');
  });

  it('strips decimals as per maximumFractionDigits: 0', () => {
    expect(formatPrice(12500.99)).toContain('$12,501');
  });
});
