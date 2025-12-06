<?php

namespace ApApi\Widgets;

// Do not allow directly accessing this file.
if ( ! defined('ABSPATH')) {
    exit('Direct script access denied.');
}

if ( ! class_exists('\Elementor\Widget_Base')) {
    return;
}

/**
 * AP Store Prices Per Department Widget
 *
 * Elementor widget for displaying department prices in a table format based on selected store ID.
 * Allows users to select a store and displays all departments and their item prices in an organized table.
 */
class StorePricesPerDepartmentWidget extends StoreBaseWidgetAbstract
{
    /**
     * {@inheritDoc}
     */
    public function get_widget_name(): string
    {
        return 'ap_store_prices_per_department_widget';
    }

    /**
     * {@inheritDoc}
     */
    public function get_widget_title(): string
    {
        return esc_html__('AP Store Prices Per Department', 'ap-api-integration');
    }

    /**
     * {@inheritDoc}
     */
    public function get_widget_icon(): string
    {
        return 'eicon-products';
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
     * Adds styling options for department prices table/list display.
     *
     * @return void
     */
    protected function register_style_controls(): void
    {
        // Table Container Style Section
        $this->start_controls_section(
            'section_table_container_style',
            [
                'label' => esc_html__('Table Container', 'ap-api-integration'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Remove List Style
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
                    '{{WRAPPER}} .mb-store-department-prices__items' => 'list-style: none !important; padding-left: 0 !important;',
                ],
            ]
        );

        // Container Background Color
        $this->add_control(
            'table_background_color',
            [
                'label'     => esc_html__('Background Color', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-department-prices__items' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Container Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'table_border',
                'label'    => esc_html__('Border', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-store-department-prices__items',
            ]
        );

        // Container Border Radius
        $this->add_responsive_control(
            'table_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-department-prices__items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Container Box Shadow
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'table_box_shadow',
                'label'    => esc_html__('Box Shadow', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-store-department-prices__items',
            ]
        );

        $this->end_controls_section();

        // Row Style Section
        $this->start_controls_section(
            'section_row_style',
            [
                'label' => esc_html__('Row Style', 'ap-api-integration'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Price Position
        $this->add_control(
            'price_position',
            [
                'label'        => esc_html__('Enable Price Position Layout', 'ap-api-integration'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ap-api-integration'),
                'label_off'    => esc_html__('No', 'ap-api-integration'),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .mb-table-row__inner-wrapper' => 'display: flex; justify-content: space-between; align-items: center;',
                ],
            ]
        );

        // Remove Row Bottom Margin
        $this->add_control(
            'remove_row_bottom_margin',
            [
                'label'        => esc_html__('Remove Row Bottom Margin', 'ap-api-integration'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'ap-api-integration'),
                'label_off'    => esc_html__('No', 'ap-api-integration'),
                'return_value' => 'yes',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .mb-store-department-prices__items li' => 'margin: 0 !important;',
                ],
            ]
        );

        // Gap between name and price (Responsive)
        $this->add_responsive_control(
            'row_gap',
            [
                'label'      => esc_html__('Gap Between Name and Price', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .mb-table-row' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Row Padding
        $this->add_responsive_control(
            'row_padding',
            [
                'label'      => esc_html__('Row Padding', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-table-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Alternating Row Background
        $this->add_control(
            'row_alternate_background',
            [
                'label'     => esc_html__('Alternating Row Background', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-table-row:nth-child(even)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Row Hover Background
        $this->add_control(
            'row_hover_background',
            [
                'label'     => esc_html__('Row Hover Background', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-table-row:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Row Border Bottom
        $this->add_control(
            'row_border_bottom_color',
            [
                'label'     => esc_html__('Row Border Bottom Color', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-table-row' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_border_bottom_width',
            [
                'label'      => esc_html__('Row Border Bottom Width', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .mb-table-row' => 'border-bottom-width: {{SIZE}}{{UNIT}}; border-bottom-style: solid;',
                ],
            ]
        );

        $this->end_controls_section();

        // Department Title Style Section
        $this->start_controls_section(
            'section_department_title_style',
            [
                'label' => esc_html__('Department Title', 'ap-api-integration'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography Control for Department Title
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'department_title_typography',
                'label'    => esc_html__('Typography', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-store-department-prices__title',
            ]
        );

        // Department Title Color
        $this->add_control(
            'department_title_color',
            [
                'label'     => esc_html__('Text Color', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-department-prices__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Department Title Background Color
        $this->add_control(
            'department_title_background_color',
            [
                'label'     => esc_html__('Background Color', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-department-prices__title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Department Title Padding
        $this->add_responsive_control(
            'department_title_padding',
            [
                'label'      => esc_html__('Padding', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-department-prices__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Department Title Margin
        $this->add_responsive_control(
            'department_title_margin',
            [
                'label'      => esc_html__('Margin', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-department-prices__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Department Title Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'department_title_border',
                'label'    => esc_html__('Border', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-store-department-prices__title',
            ]
        );

        // Department Title Border Radius
        $this->add_responsive_control(
            'department_title_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'ap-api-integration'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mb-store-department-prices__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Service Name Style Section
        $this->start_controls_section(
            'section_service_name_style',
            [
                'label' => esc_html__('Service Name', 'ap-api-integration'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography Control for Service Name
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'service_name_typography',
                'label'    => esc_html__('Typography', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-table-name',
            ]
        );

        // Service Name Color
        $this->add_control(
            'service_name_color',
            [
                'label'     => esc_html__('Text Color', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-table-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Price Style Section
        $this->start_controls_section(
            'section_price_style',
            [
                'label' => esc_html__('Price', 'ap-api-integration'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography Control for Price
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'label'    => esc_html__('Typography', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-price',
            ]
        );

        // Price Color
        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__('Text Color', 'ap-api-integration'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     *
     * Displays the selected store's department prices in a table format on the frontend.
     *
     * @return void
     */
    protected function render_widget_content(): void
    {
        $settings = $this->get_settings_for_display();
        $store_id = $settings['store_id'];

        $content = '';

        if (empty($store_id)) {
            ob_start();
            ?>
            <div class="mb-store-department-prices__no-selection">
                <p><?php echo esc_html__('Please select a store to view department prices.', 'ap-api-integration'); ?></p>
            </div>
            <?php
            $content = ob_get_clean();
        }

        // Fetch actual store data from repository
        $store = $this->repository->findStoreByBranchId((int)$store_id);
        if ( ! $store && empty($content)) {
            ob_start();
            ?>
            <div class="mb-store-department-prices__not-found">
                <p><?php echo esc_html__('Store not found.', 'ap-api-integration'); ?></p>
            </div>
            <?php
            $content = ob_get_clean();
        }

        if (empty($content)) {
            $departmentPrices = $store->getPricesPerDepartments();

            ob_start();
            ?>
            <div class="mb-store-department-prices__info">
                <?php if (empty($departmentPrices)): ?>
                    <div class="mb-department-prices__no-data">
                        <p><?php echo esc_html__('No department prices available for this store.', 'ap-api-integration'); ?></p>
                    </div>
                <?php else: ?>
                    <div class="mb-store-department-prices__list">
                        <?php foreach ($departmentPrices as $department): ?>
                            <div class="mb-store-department-prices__section">
                                <div class="mb-store-department-prices__title"><?php echo esc_html($department->getDepartmentName()); ?></div>
                                <ul class="mb-store-department-prices__items">
                                    <?php foreach ($department->getItems() as $item): ?>
                                        <li class="mb-table-row">
                                            <div class="mb-table-row__inner-wrapper">
                                                <span class="mb-table-name"><?php echo sanitize_text_field($item->getName()); ?></span>
                                                <span class="mb-table-value">
                                                    <span class="mb-price"><?php echo sanitize_text_field($item->getPrice()); ?></span>
                                                </span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div><!-- /.mb-store-department-prices__info -->
            <?php
            $content = ob_get_clean();
        }

        ?>
        <div class="mb-store-department-prices" data-store-id="<?php echo esc_attr($store_id); ?>">
            <?php echo $content; ?>
        </div><!-- /.mb-store-department-prices -->
        <?php
    }
}
