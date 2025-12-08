<?php

namespace ApApi\Widgets;

use Elementor\Controls_Manager;

// Do not allow directly accessing this file.
if ( ! defined('ABSPATH')) {
    exit('Direct script access denied.');
}

if ( ! class_exists('\Elementor\Widget_Base')) {
    return;
}

/**
 * AP Store Hours Widget
 *
 * Elementor widget for displaying store hours based on selected store ID.
 * Allows users to select a store and displays the operating hours (Mon-Fri, Saturday, Sunday) on the frontend.
 */
class StoreHoursWidget extends StoreBaseWidgetAbstract
{
    /**
     * {@inheritDoc}
     */
    public function get_widget_name(): string
    {
        return 'ap_store_hours_widget';
    }

    /**
     * {@inheritDoc}
     */
    public function get_widget_title(): string
    {
        return esc_html__('AP Store Hours', 'ap-api-integration');
    }

    /**
     * {@inheritDoc}
     */
    public function get_widget_icon(): string
    {
        return 'eicon-clock';
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
     * Adds styling options for store hours display.
     *
     * @return void
     */
    protected function register_style_controls(): void
    {
        // Hours Style Section
        $this->start_controls_section(
            'section_hours_style',
            [
                'label' => esc_html__('Typography', 'ap-api-integration'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography Control
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'hours_typography',
                'label'    => esc_html__('Typography', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-store-hours__info li',
            ]
        );

        // Text Alignment
        $this->add_responsive_control(
            'hours_alignment',
            [
                'label'     => esc_html__('Alignment', 'ap-api-integration'),
                'type'      => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .mb-store-hours' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Text Color
        $this->add_control(
            'hours_color',
            [
                'label'     => esc_html__('Text Color', 'ap-api-integration'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-hours__info li' => 'color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .mb-store-hours__info ul' => 'list-style: none !important; padding-left: 0 !important;',
                ],
            ]
        );

        // List Item Spacing
        $this->add_responsive_control(
            'list_item_spacing',
            [
                'label'      => esc_html__('Item Spacing', 'ap-api-integration'),
                'type'       => Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .mb-store-hours__info li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     *
     * Displays the selected store's operating hours on the frontend.
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
            <div class="mb-store-hours__no-selection">
                <p><?php echo esc_html__('Please select a store to view hours.', 'ap-api-integration'); ?></p>
            </div>
            <?php
            $content = ob_get_clean();
        }

        // Fetch actual store data from repository
        try {
            $store = $this->repository->findStoreByBranchId((int)$store_id);
            if ( ! $store && empty($content)) {
                ob_start();
                ?>
                <div class="mb-store-hours__not-found">
                    <p><?php echo esc_html__('Store not found.', 'ap-api-integration'); ?></p>
                </div>
                <?php
                $content = ob_get_clean();
            }
        } catch (\InvalidArgumentException $e) {
            $this->logger->log(
                '[WIDGET] [ERROR] Invalid store data format in StoreHoursWidget',
                [
                    'store_id' => $store_id,
                    'widget_class' => get_class($this),
                    'exception_message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            ob_start();
            ?>
            <div class="mb-widget-error">
                <p><?php echo esc_html__('Error: Unable to load store data due to invalid format.', 'ap-api-integration'); ?></p>
            </div>
            <?php
            $content = ob_get_clean();
        }

        if (empty($content)) {
            $storeHours = $store->getStoreHours();
            ob_start();
            ?>
            <div class="mb-store-hours__info">
                <ul>
                    <li><?php echo esc_html($storeHours->getMonFriHours()); ?></li>
                    <li><?php echo esc_html($storeHours->getSaturdayHours()); ?></li>
                    <li><?php echo esc_html($storeHours->getSundayHours()); ?></li>
                </ul>
            </div><!-- /.mb-store-hours__info -->
            <?php
            $content = ob_get_clean();
        }

        ?>
        <div class="mb-store-hours" data-store-id="<?php echo esc_attr($store_id); ?>">
            <?php echo $content; ?>
        </div><!-- /.mb-store-hours -->
        <?php
    }
}
