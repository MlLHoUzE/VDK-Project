import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import TextInput from '@/Components/TextInput.vue';

describe('TextInput.vue', () => {
  it('updates the model value when input changes', async () => {
    const wrapper = mount(TextInput, {
      props: {
        modelValue: '',
        'onUpdate:modelValue': (e) => wrapper.setProps({ modelValue: e })
      }
    });

    const input = wrapper.find('input');
    await input.setValue('2024 Tesla');

    // Check if the internal model-driven value is correct
    expect(input.element.value).toBe('2024 Tesla');
  });

  it('triggers focus on mount if the autofocus attribute is present', () => {
    // We mock the focus method because JSDOM doesn't actually 'draw' focus
    const focusSpy = vi.spyOn(HTMLInputElement.prototype, 'focus');

    mount(TextInput, {
      attrs: { autofocus: true }
    });

    expect(focusSpy).toHaveBeenCalled();
    focusSpy.mockRestore();
  });

  it('exposes a focus method to parent components', () => {
    const wrapper = mount(TextInput, {
      props: { modelValue: '' }
    });

    const focusSpy = vi.spyOn(wrapper.vm.input, 'focus');

    // Call the method we exposed via defineExpose
    wrapper.vm.focus();

    expect(focusSpy).toHaveBeenCalled();
    focusSpy.mockRestore();
  });

  it('applies the correct Tailwind styling for focus states', () => {
    const wrapper = mount(TextInput, {
      props: { modelValue: '' }
    });

    const input = wrapper.find('input');
    expect(input.classes()).toContain('focus:border-indigo-500');
    expect(input.classes()).toContain('dark:bg-gray-900');
  });
});
