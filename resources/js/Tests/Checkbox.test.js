import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import Checkbox from '@/Components/Checkbox.vue';

describe('Checkbox.vue', () => {
  it('renders with the correct base classes', () => {
    const wrapper = mount(Checkbox, {
      props: { checked: false }
    });

    const input = wrapper.find('input[type="checkbox"]');
    expect(input.classes()).toContain('text-indigo-600');
    expect(input.classes()).toContain('rounded');
  });

  it('emits update:checked when clicked (Boolean mode)', async () => {
    const wrapper = mount(Checkbox, {
      props: { checked: false }
    });

    const input = wrapper.find('input');
    // Simulate user click
    await input.setChecked();

    // Check if the update event was fired with 'true'
    expect(wrapper.emitted('update:checked')).toBeTruthy();
    expect(wrapper.emitted('update:checked')[0]).toEqual([true]);
  });

  it('works with array values for multi-select groups', async () => {
    const selectedIds = [1, 2];
    const wrapper = mount(Checkbox, {
      props: {
        checked: selectedIds,
        value: 3 // The value this specific checkbox represents
      }
    });

    const input = wrapper.find('input');
    await input.setChecked();

    /**
     * In array mode, Vue's setChecked appends the 'value' to the 'checked' array.
     * The emitted event should contain [1, 2, 3]
     */
    expect(wrapper.emitted('update:checked')[0][0]).toContain(3);
    expect(wrapper.emitted('update:checked')[0][0]).toHaveLength(3);
  });

  it('is disabled when the native attribute is passed', () => {
    const wrapper = mount(Checkbox, {
      props: { checked: false },
      attrs: { disabled: true }
    });

    expect(wrapper.find('input').element.disabled).toBe(true);
  });
});
