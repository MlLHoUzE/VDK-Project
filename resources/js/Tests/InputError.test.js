import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import InputError from '@/Components/InputError.vue';

describe('InputError.vue', () => {
  it('renders the error message when provided', () => {
    const errorMessage = 'The email field is required.';
    const wrapper = mount(InputError, {
      props: { message: errorMessage }
    });

    // 1. Verify the text is rendered
    expect(wrapper.text()).toBe(errorMessage);

    // 2. Verify it is visible (v-show is true)
    expect(wrapper.find('div').isVisible()).toBe(true);
  });

  it('is hidden when no message is provided', () => {
    const wrapper = mount(InputError, {
      props: { message: null }
    });

    // In JSDOM, v-show="null/false" results in display: none
    expect(wrapper.find('div').isVisible()).toBe(false);
  });

  it('has the correct styling for error visibility', () => {
    const wrapper = mount(InputError, {
      props: { message: 'Error' }
    });

    const text = wrapper.find('p');
    expect(text.classes()).toContain('text-red-600');
    expect(text.classes()).toContain('dark:text-red-400');
  });
});
