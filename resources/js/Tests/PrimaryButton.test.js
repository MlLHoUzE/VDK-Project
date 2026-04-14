import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import PrimaryButton from '@/Components/PrimaryButton.vue';

describe('PrimaryButton.vue', () => {
  it('renders the content passed into the slot', () => {
    const wrapper = mount(PrimaryButton, {
      slots: {
        default: 'Save Vehicle'
      }
    });

    expect(wrapper.text()).toBe('Save Vehicle');
  });

  it('applies the correct primary indigo theme classes', () => {
    const wrapper = mount(PrimaryButton);

    // Target the button specifically to skip comments in the template
    const button = wrapper.find('button');
    const classes = button.classes();

    expect(classes).toContain('bg-indigo-600');
    expect(classes).toContain('text-white');
    expect(classes).toContain('font-semibold');
  });

  it('handles native button types via attributes', () => {
    const wrapper = mount(PrimaryButton, {
      attrs: {
        type: 'submit'
      }
    });

    expect(wrapper.find('button').element.type).toBe('submit');
  });

  it('emits a click event when the user clicks', async () => {
    const wrapper = mount(PrimaryButton);

    await wrapper.find('button').trigger('click');

    expect(wrapper.emitted()).toHaveProperty('click');
  });
});
