import { mount } from '@vue/test-utils';
import { describe, it, expect, vi, beforeEach } from 'vitest';
import Modal from '@/Components/Modal.vue';

describe('Modal.vue', () => {
  // Mocking HTMLDialogElement methods that aren't in JSDOM
  beforeEach(() => {
    HTMLDialogElement.prototype.showModal = vi.fn();
    HTMLDialogElement.prototype.close = vi.fn();
  });

  it('renders and shows the native dialog when "show" is true', async () => {
    const wrapper = mount(Modal, {
      props: { show: true }
    });

    // Wait for onMounted to fire and dialog ref to be populated
    await wrapper.vm.$nextTick();

    expect(HTMLDialogElement.prototype.showModal).toHaveBeenCalled();
    expect(document.body.style.overflow).toBe('hidden');
  });

  it('hides and restores scroll when "show" is false', async () => {
    const wrapper = mount(Modal, {
      props: { show: true }
    });

    await wrapper.setProps({ show: false });

    // Restore scroll immediately in the watcher logic
    expect(document.body.style.overflow).toBe('');
  });

  it('emits close when clicking the backdrop', async () => {
    const wrapper = mount(Modal, {
      props: { show: true, closeable: true }
    });

    // Click the backdrop div (usually the first child of the scroll region)
    const backdrop = wrapper.find('.fixed.inset-0.transform');
    await backdrop.trigger('click');

    expect(wrapper.emitted()).toHaveProperty('close');
  });

  it('does not emit close when clicking backdrop if closeable is false', async () => {
    const wrapper = mount(Modal, {
      props: { show: true, closeable: false }
    });

    const backdrop = wrapper.find('.fixed.inset-0.transform');
    await backdrop.trigger('click');

    expect(wrapper.emitted('close')).toBeUndefined();
  });

  it('closes on Escape key press', async () => {
    const wrapper = mount(Modal, {
      props: { show: true }
    });

    const event = new KeyboardEvent('keydown', { key: 'Escape' });
    document.dispatchEvent(event);

    expect(wrapper.emitted()).toHaveProperty('close');
  });

  it('applies the correct max-width class', () => {
    const wrapper = mount(Modal, {
      props: { show: true, maxWidth: 'lg' }
    });

    const content = wrapper.find('.rounded-lg');
    expect(content.classes()).toContain('sm:max-w-lg');
  });
});
