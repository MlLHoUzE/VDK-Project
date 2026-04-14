import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import NavLink from '@/Components/NavLink.vue';

describe('NavLink.vue', () => {
  const testUrl = '/admin/vehicles';

  it('renders as an active link with indigo border classes', () => {
    const wrapper = mount(NavLink, {
      props: {
        href: testUrl,
        active: true
      },
      global: { stubs: { Link: { template: '<a><slot /></a>' } } },
      slots: { default: 'Dashboard' }
    });

    // Verify the active styling is present
    const classes = wrapper.classes();
    expect(classes).toContain('border-indigo-400');
    expect(classes).toContain('text-gray-900');

    // Verify it doesn't have transparent border classes
    expect(classes).not.toContain('border-transparent');
  });

  it('renders as an inactive link with transparent border classes', () => {
    const wrapper = mount(NavLink, {
      props: {
        href: testUrl,
        active: false
      },
      global: { stubs: { Link: { template: '<a><slot /></a>' } } }
    });

    const classes = wrapper.classes();
    expect(classes).toContain('border-transparent');
    expect(classes).toContain('text-gray-500');
  });

  it('contains the slot content', () => {
    const wrapper = mount(NavLink, {
      props: { href: testUrl },
      global: { stubs: { Link: { template: '<a><slot /></a>' } } },
      slots: { default: 'Inventory' }
    });

    expect(wrapper.text()).toBe('Inventory');
  });
});
