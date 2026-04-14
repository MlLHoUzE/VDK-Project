import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

describe('ResponsiveNavLink.vue', () => {
  const testUrl = '/admin/vehicles';

  it('renders with active mobile styles (left indigo border)', () => {
    const wrapper = mount(ResponsiveNavLink, {
      props: {
        href: testUrl,
        active: true
      },
      global: {
        stubs: { Link: { template: '<a><slot /></a>' } }
      },
      slots: { default: 'Mobile Nav Item' }
    });

    const classes = wrapper.classes();

    // Mobile-specific active classes
    expect(classes).toContain('border-l-4');
    expect(classes).toContain('border-indigo-400');
    expect(classes).toContain('bg-indigo-50');
    expect(classes).toContain('text-indigo-700');
  });

  it('renders with inactive mobile styles (transparent border)', () => {
    const wrapper = mount(ResponsiveNavLink, {
      props: {
        href: testUrl,
        active: false
      },
      global: {
        stubs: { Link: { template: '<a><slot /></a>' } }
      }
    });

    const classes = wrapper.classes();

    expect(classes).toContain('border-transparent');
    expect(classes).toContain('text-gray-600');
    // Ensure it doesn't have the active background
    expect(classes).not.toContain('bg-indigo-50');
  });

  it('is a block-level element for a larger touch target', () => {
    const wrapper = mount(ResponsiveNavLink, {
      props: { href: testUrl },
      global: { stubs: { Link: { template: '<a><slot /></a>' } } }
    });

    expect(wrapper.classes()).toContain('block');
    expect(wrapper.classes()).toContain('w-full');
  });
});
