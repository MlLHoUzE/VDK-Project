import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import VehicleDetailsCard from '@/Components/VehicleDetailsCard.vue';

describe('VehicleDetailsCard.vue', () => {
  const mockVehicle = {
    id: 1,
    title: '2024 Ford F-150',
    make: 'Ford',
    model: 'F-150',
    year: 2024,
    price: 65000,
    mileage: 1500,
    transmission: '10-Speed Automatic',
    fuel_type: 'Gasoline',
    color: 'Oxford White',
    description: 'A powerful truck for all your needs.',
    image_path: 'https://example.com'
  };

  it('renders the vehicle title, specs, and CAD price', () => {
    const wrapper = mount(VehicleDetailsCard, {
      props: { vehicle: mockVehicle }
    });

    expect(wrapper.text()).toContain('2024 Ford F-150');
    expect(wrapper.text()).toContain('$65,000'); // CAD formatting check
    expect(wrapper.text()).toContain('1,500 mi');
  });

  it('uses the provided image path or a placeholder', () => {
    const wrapper = mount(VehicleDetailsCard, {
      props: { vehicle: mockVehicle }
    });

    const img = wrapper.find('img');
    expect(img.attributes('src')).toBe(mockVehicle.image_path);
  });

  it('renders "N/A" for missing optional fields', () => {
    const incompleteVehicle = {
      id: 2,
      title: 'Ghost Car',
      price: 10000,
      mileage: null,
      transmission: null,
      fuel_type: null,
      color: null,
      description: null
    };

    const wrapper = mount(VehicleDetailsCard, {
      props: { vehicle: incompleteVehicle }
    });

    // Check specifically inside the specs list
    const dl = wrapper.find('dl');
    expect(dl.text()).toContain('N/A');
    expect(wrapper.text()).toContain('No additional description provided.');
  });

  it('correctly formats the year/make/model subtitle', () => {
    const wrapper = mount(VehicleDetailsCard, {
      props: { vehicle: mockVehicle }
    });

    const subtitle = wrapper.find('p.text-sm');
    expect(subtitle.text()).toBe('2024 Ford F-150');
  });
});
