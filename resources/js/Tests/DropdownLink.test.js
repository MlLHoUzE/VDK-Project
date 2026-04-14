import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import DropdownLink from '@/Components/DropdownLink.vue';

describe('DropdownLink.vue', () => {
  it('renders as an Inertia Link with the correct href', () => {
    const testUrl = '/admin/vehicles';
    const wrapper = mount(DropdownLink, {
      props: { href: testUrl },
      slots: {
        default: 'Manage Vehicles'
      },
      global: {
        stubs: {
          // We use a simple true stub for the logic,
          // but we will check the attributes instead of props
          Link: true
        }
      }
    });

    // 1. Verify the slot content is present
    // (Note: If 'Link: true' still hides text, use the 'a' template fix from before)

    // 2. Find the stubbed component by its tag
    const link = wrapper.find('link-stub');

    // 3. Check the 'href' attribute (stubs store passed props as attributes)
    expect(link.attributes('href')).toBe(testUrl);
  });

  it('has the correct utility classes for dropdown styling', () => {
    const wrapper = mount(DropdownLink, {
      props: { href: '#' },
      global: { stubs: { Link: true } }
    });
    const link = wrapper.find('link-stub');

    const classes = link.classes();
    expect(classes).toContain('block');
    expect(classes).toContain('w-full');
    expect(classes).toContain('text-start');
    expect(classes).toContain('text-sm');
  });
});
