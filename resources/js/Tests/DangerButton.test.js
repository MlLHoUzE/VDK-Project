import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import DangerButton from '@/Components/DangerButton.vue';

describe('DangerButton.vue', () => {
  it('renders the content passed into the slot', () => {
    const wrapper = mount(DangerButton, {
      slots: {
        default: 'Delete Listing'
      }
    });

    expect(wrapper.text()).toBe('Delete Listing');
  });

  it('has the correct base tailwind classes for a danger button', () => {
    const wrapper = mount(DangerButton);

    // Use .find('button') to skip over comments and target the actual element
    const button = wrapper.find('button');
    const classes = button.classes();

    expect(classes).toContain('bg-red-600');
    expect(classes).toContain('hover:bg-red-500');
    expect(classes).toContain('uppercase');
  });

  it('emits a click event when pressed', async () => {
    const wrapper = mount(DangerButton);

    const button = wrapper.find('button');

    await button.trigger('click');

    // Check if the component successfully passed the click up
    expect(wrapper.emitted()).toHaveProperty('click');
  });

  it('can be disabled', () => {
    const wrapper = mount(DangerButton, {
      props: {
        disabled: true
      }
    });

    // Target the actual button inside the fragment
    const button = wrapper.find('button');

    // 1. Check the DOM property
    expect(button.element.disabled).toBe(true);
  });
});
