import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import InputLabel from '@/Components/InputLabel.vue';

describe('InputLabel.vue', () => {
  it('renders the text from the value prop', () => {
    const wrapper = mount(InputLabel, {
      props: { value: 'Email Address' }
    });

    expect(wrapper.text()).toBe('Email Address');
    // Ensure the span for the prop is used
    expect(wrapper.find('span').exists()).toBe(true);
  });

  it('renders the slot content when the value prop is missing', () => {
    const wrapper = mount(InputLabel, {
      slots: {
        default: 'Custom Label Content'
      }
    });

    expect(wrapper.text()).toBe('Custom Label Content');
  });

  it('has the correct semantic styling for labels', () => {
    const wrapper = mount(InputLabel, {
      props: { value: 'Test' }
    });

    const label = wrapper.find('label');
    const classes = label.classes();

    expect(classes).toContain('block');
    expect(classes).toContain('text-sm');
    expect(classes).toContain('font-medium');
  });

  it('handles an empty state gracefully', () => {
    const wrapper = mount(InputLabel);

    // Should render an empty label without crashing
    expect(wrapper.text()).toBe('');
  });
});
