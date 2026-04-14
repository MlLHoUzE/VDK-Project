import { mount } from '@vue/test-utils';
import { describe, it, expect, vi } from 'vitest';
import VehicleCard from '@/Components/VehicleCard.vue';

describe('VehicleCard.vue', () => {
  const mockVehicle = {
    id: 1,
    title: '2024 Toyota Corolla',
    make: 'Toyota',
    model: 'Corolla',
    year: 2024,
    price: 25000,
    mileage: 50,
    transmission: 'Automatic',
    fuel_type: 'Gasoline',
    is_published: true,
    image_path: null
  };

  // Helper to stub Inertia Link and Ziggy route()
  const globalConfig = {
    stubs: { Link: { template: '<a><slot /></a>', props: ['href'] } },
    config: {
      globalProperties: {
        route: vi.fn((name) => name) // Simple mock for Ziggy
      }
    }
  };

  it('renders vehicle information correctly', () => {
    const wrapper = mount(VehicleCard, {
      props: { vehicle: mockVehicle },
      global: globalConfig
    });

    expect(wrapper.text()).toContain('2024 Toyota Corolla');
    expect(wrapper.text()).toContain('$25,000');
    expect(wrapper.text()).toContain('50 mi');
  });

  it('uses the public route when isAdmin is false', () => {
    const wrapper = mount(VehicleCard, {
      props: { vehicle: mockVehicle, isAdmin: false },
      global: {
        stubs: {
          Link: true // Simplified stub
        },
        config: {
          globalProperties: {
            route: vi.fn((name) => name)
          }
        }
      }
    });

    // 1. Find the first link-stub in the component
    // Vue Test Utils automatically names stubbed components as [tag]-stub
    const link = wrapper.find('link-stub');

    // 2. Check the attribute (props are mirrored as attributes on stubs)
    expect(link.attributes('href')).toBe('vehicles.show');
  });

  it('shows admin controls and status badge when isAdmin is true', () => {
    const wrapper = mount(VehicleCard, {
      props: { vehicle: mockVehicle, isAdmin: true },
      global: globalConfig
    });

    expect(wrapper.text()).toContain('Admin Controls');
    expect(wrapper.text()).toContain('Published');
    expect(wrapper.find('button').text()).toContain('Unpublish');
  });

  it('emits custom events for admin actions', async () => {
    const wrapper = mount(VehicleCard, {
      props: { vehicle: mockVehicle, isAdmin: true },
      global: globalConfig
    });

    // Find and click the Delete button
    const deleteBtn = wrapper
      .findAll('button')
      .find((b) => b.text() === 'Delete');
    await deleteBtn.trigger('click');

    expect(wrapper.emitted()).toHaveProperty('delete');
    expect(wrapper.emitted('delete')[0][0]).toEqual(mockVehicle);
  });

  it('renders "N/A" for missing technical specs', () => {
    const incompleteVehicle = {
      ...mockVehicle,
      mileage: null,
      transmission: ''
    };
    const wrapper = mount(VehicleCard, {
      props: { vehicle: incompleteVehicle },
      global: globalConfig
    });

    expect(wrapper.text()).toContain('N/A');
  });
});
