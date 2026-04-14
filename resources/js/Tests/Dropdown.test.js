import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import Dropdown from '@/Components/Dropdown.vue';

describe('Dropdown.vue', () => {
  // Helper to mount the dropdown with slot content
  const createWrapper = (props = {}) => {
    return mount(Dropdown, {
      props,
      slots: {
        trigger: '<button id="trigger">Click Me</button>',
        content: '<div id="content">Menu Content</div>'
      }
    });
  };

  it('is closed by default', () => {
    const wrapper = createWrapper();
    // The content div has v-show="open", so it should be hidden
    expect(wrapper.find('#content').isVisible()).toBe(false);
  });

  it('opens when the trigger is clicked', async () => {
    const wrapper = createWrapper();

    await wrapper.find('#trigger').trigger('click');

    expect(wrapper.find('#content').isVisible()).toBe(true);
  });

  it('closes when the content is clicked', async () => {
    const wrapper = mount(Dropdown, {
      // We "stub" the transition to get it out of the way entirely
      global: { stubs: { Transition: true } },
      slots: {
        trigger: '<button id="trigger">Click Me</button>',
        content: '<div id="content">Menu Content</div>'
      }
    });

    // 1. Open it
    await wrapper.find('#trigger').trigger('click');
    expect(wrapper.vm.open).toBe(true);

    // 2. Click the absolute container (the one with @click="open = false")
    // We use .find('.absolute') because that's where the event handler sits
    const contentWrapper = wrapper.find('.absolute');
    await contentWrapper.trigger('click');

    // 3. Verify the LOGIC changed the state
    expect(wrapper.vm.open).toBe(false);
  });

  it('closes when the Escape key is pressed', async () => {
    const wrapper = createWrapper();

    // Open it
    await wrapper.find('#trigger').trigger('click');

    // Dispatch global keydown event
    const event = new KeyboardEvent('keydown', { key: 'Escape' });
    document.dispatchEvent(event);

    // Wait for Vue to update the DOM
    await wrapper.vm.$nextTick();

    expect(wrapper.find('#content').isVisible()).toBe(false);
  });

  it('applies correct alignment classes based on props', () => {
    const wrapper = createWrapper({ align: 'left' });

    // Find the absolute positioned div that holds the content
    const contentContainer = wrapper.find('.absolute');
    expect(contentContainer.classes()).toContain('start-0');
  });
});
