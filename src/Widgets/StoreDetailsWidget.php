<?php

namespace ApApi\Widgets;

use ApApi\Widgets\Traits\StoreTrait;

// Do not allow directly accessing this file.
if ( ! defined('ABSPATH')) {
    exit('Direct script access denied.');
}

if ( ! class_exists('\Elementor\Widget_Base')) {
    return;
}

/**
 * AP Store Details Widget
 *
 * Elementor widget for displaying store details based on selected store ID.
 * Allows users to select a store and displays contact information, address,
 * and other store details on the frontend.
 */
class StoreDetailsWidget extends StoreBaseWidgetAbstract
{
    use StoreTrait;

    /**
     * {@inheritDoc}
     */
    public function get_widget_name(): string
    {
        return 'ap_store_details_widget';
    }

    /**
     * {@inheritDoc}
     */
    public function get_widget_title(): string
    {
        return esc_html__('AP Store Details', 'ap-api-integration');
    }

    /**
     * {@inheritDoc}
     */
    public function get_widget_icon(): string
    {
        return 'eicon-map-pin';
    }

    /**
     * Register widget controls
     *
     * Adds both content and style controls for the widget.
     *
     * @return void
     */
    protected function register_controls(): void
    {
        // Register parent content controls (store selection)
        parent::register_controls();

        // Add Style Tab Controls
        $this->register_style_controls();
    }

    /**
     * Register style controls
     *
     * Adds styling options for store details display combining controls from
     * address, phone, and hours widgets.
     *
     * @return void
     */
    protected function register_style_controls(): void
    {
        // Address Style Section
        $this->start_controls_section(
            'section_address_style',
            [
                'label' => esc_html__('Address Style', 'ap-api-integration'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography Control for Address
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'address_typography',
                'label'    => esc_html__('Typography', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-store-details__address span:not(:first-child)',
            ]
        );

        // Text Alignment for Address
        $this->add_responsive_control(
            'address_alignment',
            [
                'label'     => esc_html__('Alignment', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'ap-api-integration'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'ap-api-integration'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'ap-api-integration'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mb-store-details__address' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Text Color for Address
        $this->add_control(
            'address_color',
            [
                'label'     => esc_html__('Text Color', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-details__address span:not(:first-child)' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Address Padding
        $this->add_responsive_control(
            'address_padding',
            [
                'label'      => esc_html__('Padding', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-details__address' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Address Margin
        $this->add_responsive_control(
            'address_margin',
            [
                'label'      => esc_html__('Margin', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-details__address' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Address Icon Alignment
        $this->add_control(
            'address_icon_alignment',
            [
                'label'        => esc_html__('Align Icon with Text', 'ap-api-integration'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ap-api-integration'),
                'label_off'    => esc_html__('No', 'ap-api-integration'),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .mb-store-details__address svg, {{WRAPPER}} .mb-store-details__address span' => 'display: inline-block; vertical-align: middle;',
                ],
            ]
        );

        // Hide Address Icon
        $this->add_control(
            'hide_address_icon',
            [
                'label'        => esc_html__('Hide Icon', 'ap-api-integration'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ap-api-integration'),
                'label_off'    => esc_html__('No', 'ap-api-integration'),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .mb-store-details__address svg' => 'display: none !important;',
                ],
            ]
        );

        // Address Icon Height
        $this->add_responsive_control(
            'address_icon_height',
            [
                'label'      => esc_html__('Icon Height', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 6,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 6,
                        'step' => 0.1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-details__address svg' => 'height: {{SIZE}}{{UNIT}}; width: auto;',
                ],
                'condition'  => [
                    'hide_address_icon!' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Phone Link Style Section
        $this->start_controls_section(
            'section_phone_style',
            [
                'label' => esc_html__('Phone Link Style', 'ap-api-integration'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography Control for Phone
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'phone_typography',
                'label'    => esc_html__('Typography', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-store-details__phone a',
            ]
        );

        // Text Alignment for Phone
        $this->add_responsive_control(
            'phone_alignment',
            [
                'label'     => esc_html__('Alignment', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'ap-api-integration'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'ap-api-integration'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'ap-api-integration'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mb-store-details__phone' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Text Color for Phone (Normal State)
        $this->add_control(
            'phone_color',
            [
                'label'     => esc_html__('Text Color', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-details__phone a' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Hover Color for Phone
        $this->add_control(
            'phone_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-details__phone a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Text Decoration for Phone
        $this->add_control(
            'phone_text_decoration',
            [
                'label'     => esc_html__('Text Decoration', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'none',
                'options'   => [
                    'none'         => esc_html__('None', 'ap-api-integration'),
                    'underline'    => esc_html__('Underline', 'ap-api-integration'),
                    'overline'     => esc_html__('Overline', 'ap-api-integration'),
                    'line-through' => esc_html__('Line Through', 'ap-api-integration'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .mb-store-details__phone a' => 'text-decoration: {{VALUE}};',
                ],
            ]
        );

        // Hover Text Decoration for Phone
        $this->add_control(
            'phone_hover_text_decoration',
            [
                'label'     => esc_html__('Hover Text Decoration', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'underline',
                'options'   => [
                    'none'         => esc_html__('None', 'ap-api-integration'),
                    'underline'    => esc_html__('Underline', 'ap-api-integration'),
                    'overline'     => esc_html__('Overline', 'ap-api-integration'),
                    'line-through' => esc_html__('Line Through', 'ap-api-integration'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .mb-store-details__phone a:hover' => 'text-decoration: {{VALUE}};',
                ],
            ]
        );

        // Phone Padding
        $this->add_responsive_control(
            'phone_padding',
            [
                'label'      => esc_html__('Padding', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-details__phone' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Phone Margin
        $this->add_responsive_control(
            'phone_margin',
            [
                'label'      => esc_html__('Margin', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-details__phone' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Phone Icon Alignment
        $this->add_control(
            'phone_icon_alignment',
            [
                'label'        => esc_html__('Align Icon with Text', 'ap-api-integration'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ap-api-integration'),
                'label_off'    => esc_html__('No', 'ap-api-integration'),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .mb-store-details__phone svg, {{WRAPPER}} .mb-store-details__phone a' => 'display: inline-block; vertical-align: middle;',
                ],
            ]
        );

        // Hide Phone Icon
        $this->add_control(
            'hide_phone_icon',
            [
                'label'        => esc_html__('Hide Icon', 'ap-api-integration'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ap-api-integration'),
                'label_off'    => esc_html__('No', 'ap-api-integration'),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .mb-store-details__phone svg' => 'display: none !important;',
                ],
            ]
        );

        // Phone Icon Height
        $this->add_responsive_control(
            'phone_icon_height',
            [
                'label'      => esc_html__('Icon Height', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 6,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 6,
                        'step' => 0.1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-details__phone svg' => 'height: {{SIZE}}{{UNIT}}; width: auto;',
                ],
                'condition'  => [
                    'hide_phone_icon!' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Hours Typography Section
        $this->start_controls_section(
            'section_hours_style',
            [
                'label' => esc_html__('Hours Typography', 'ap-api-integration'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography Control for Hours
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'hours_typography',
                'label'    => esc_html__('Typography', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-store-details__hours li',
            ]
        );

        // Text Alignment for Hours
        $this->add_responsive_control(
            'hours_alignment',
            [
                'label'     => esc_html__('Alignment', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'ap-api-integration'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'ap-api-integration'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'ap-api-integration'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mb-store-details__hours' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Text Color for Hours
        $this->add_control(
            'hours_color',
            [
                'label'     => esc_html__('Text Color', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-details__hours li' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Remove List Style for Hours
        $this->add_control(
            'remove_list_style',
            [
                'label'        => esc_html__('Remove List Style', 'ap-api-integration'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ap-api-integration'),
                'label_off'    => esc_html__('No', 'ap-api-integration'),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .mb-store-details__hours ul' => 'list-style: none !important; padding-left: 0 !important;',
                ],
            ]
        );

        // List Item Spacing for Hours
        $this->add_responsive_control(
            'list_item_spacing',
            [
                'label'      => esc_html__('Item Spacing', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-details__hours li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Hours Padding
        $this->add_responsive_control(
            'hours_padding',
            [
                'label'      => esc_html__('Padding', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-details__hours' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Hours Margin
        $this->add_responsive_control(
            'hours_margin',
            [
                'label'      => esc_html__('Margin', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-details__hours' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     *
     * Displays the selected store's details on the frontend including
     * contact information, address, and other store data.
     *
     * @return void
     */
    protected function render_widget_content(): void
    {
        $settings = $this->get_settings_for_display();
        $store_id = $settings['store_id'];

        $content = '';

        // Handle case when no store is selected
        if (empty($store_id)) {
            ob_start();
            ?>
            <div class="mb-store-details__no-selection">
                <p><?php echo esc_html__('Please select a store to view details.', 'ap-api-integration'); ?></p>
            </div>
            <?php
            $content = ob_get_clean();
        }

        // Fetch actual store data from repository
        $store = $this->repository->findStoreByBranchId((int)$store_id);
        if ( ! $store && empty($content)) {
            ob_start();
            ?>
            <div class="mb-store-details__not-found">
                <p><?php echo esc_html__('Store not found.', 'ap-api-integration'); ?></p>
            </div>
            <?php
            $content = ob_get_clean();
        }

        if (empty($content)) {
            ob_start();
            ?>
            <div class="mb-store-details__info">
                <div class="mb-store-details__address">
                    <svg class="mr-1" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.3337 6.89632C13.3337 10.225 9.64099 13.6917 8.40099 14.7623C8.28547 14.8492 8.14486 14.8962 8.00033 14.8962C7.85579 14.8962 7.71518 14.8492 7.59966 14.7623C6.35966 13.6917 2.66699 10.225 2.66699 6.89632C2.66699 5.48183 3.2289 4.12528 4.22909 3.12509C5.22928 2.12489 6.58584 1.56299 8.00033 1.56299C9.41481 1.56299 10.7714 2.12489 11.7716 3.12509C12.7718 4.12528 13.3337 5.48183 13.3337 6.89632Z" fill="#C8BC92"></path>
                        <path d="M8 8.89648C9.10457 8.89648 10 8.00105 10 6.89648C10 5.79191 9.10457 4.89648 8 4.89648C6.89543 4.89648 6 5.79191 6 6.89648C6 8.00105 6.89543 8.89648 8 8.89648Z" fill="#395C6B"></path>
                    </svg>
                    <span>
                        <?php echo esc_html($store->getStreetAddress() . ', ' . $store->getCity() . ', ' . $store->getState() . ' ' . $store->getZip()); ?>
                    </span>
                </div>
                <div class="mb-store-details__phone">
                    <svg class="mr-1" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.333 1.56299H4.66634C3.92996 1.56299 3.33301 2.15994 3.33301 2.89632V13.563C3.33301 14.2994 3.92996 14.8963 4.66634 14.8963H11.333C12.0694 14.8963 12.6663 14.2994 12.6663 13.563V2.89632C12.6663 2.15994 12.0694 1.56299 11.333 1.56299Z" fill="#C8BC92"></path>
                        <path d="M8 12.2297H8.00667H8Z" fill="#395C6B"></path>
                        <path d="M8 12.2297H8.00667" stroke="#395C6B" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <a href="tel:<?php echo esc_attr($this->formatPhoneForTel($store->getPhone())); ?>"><?php echo esc_html($store->getPhone()); ?></a>
                </div>
                <div class="mb-store-details__hours">
                    <?php
                    $storeHours = $store->getStoreHours();
                    ?>
                    <ul>
                        <li><?php echo esc_html($storeHours->getMonFriHours()); ?></li>
                        <li><?php echo esc_html($storeHours->getSaturdayHours()); ?></li>
                        <li><?php echo esc_html($storeHours->getSundayHours()); ?></li>
                    </ul>
                </div>
            </div><!-- /.mb-store-details__info -->
            <?php
            $content = ob_get_clean();
        }

        ?>
        <div class="mb-store-details" data-store-id="<?php echo esc_attr($store_id); ?>">
            <?php echo $content; ?>
        </div><!-- /.mb-store-details -->
        <?php
    }
}
