import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import SecondaryButton from '@/Components/SecondaryButton.vue';

describe('SecondaryButton.vue', () => {
  it('renders the slot content', () => {
    const wrapper = mount(SecondaryButton, {
      slots: { default: 'Cancel' }
    });
    expect(wrapper.find('button').text()).toBe('Cancel');
  });

  it('applies neutral secondary styling classes', () => {
    const wrapper = mount(SecondaryButton);
    // Find the button specifically
    const button = wrapper.find('button');
    const classes = button.classes();

    expect(classes).toContain('bg-white');
    expect(classes).toContain('border-gray-300');
    expect(classes).toContain('text-gray-700');
  });

  it('defaults to type="button"', () => {
    const wrapper = mount(SecondaryButton);
    expect(wrapper.find('button').element.type).toBe('button');
  });

  it('can be set to type="submit"', () => {
    const wrapper = mount(SecondaryButton, {
      props: { type: 'submit' }
    });
    expect(wrapper.find('button').element.type).toBe('submit');
  });

  it('shows visual feedback when disabled', () => {
    const wrapper = mount(SecondaryButton, {
      attrs: { disabled: true }
    });

    const button = wrapper.find('button');
    expect(button.element.disabled).toBe(true);
    expect(button.classes()).toContain('disabled:opacity-25');
  });
});
