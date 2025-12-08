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
 * AP Store Address Widget
 *
 * Elementor widget for displaying store address based on selected store ID.
 * Allows users to select a store and displays the full address on the frontend.
 */
class StoreAddressWidget extends StoreBaseWidgetAbstract
{
    /**
     * {@inheritDoc}
     */
    public function get_widget_name(): string
    {
        return 'ap_store_address_widget';
    }

    /**
     * {@inheritDoc}
     */
    public function get_widget_title(): string
    {
        return esc_html__('AP Store Address', 'ap-api-integration');
    }

    /**
     * {@inheritDoc}
     */
    public function get_widget_icon(): string
    {
        return 'eicon-google-maps';
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
     * Adds styling options for address display.
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
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography Control
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'address_typography',
                'label'    => esc_html__('Typography', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-store-address__info span',
            ]
        );

        // Text Alignment
        $this->add_responsive_control(
            'address_alignment',
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
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mb-store-address' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Text Color
        $this->add_control(
            'address_color',
            [
                'label'     => esc_html__('Text Color', 'ap-api-integration'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-address__info span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     *
     * Displays the selected store's address on the frontend.
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
            <div class="mb-store-address__no-selection">
                <p><?php echo esc_html__('Please select a store to view address.', 'ap-api-integration'); ?></p>
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
                <div class="mb-store-address__not-found">
                    <p><?php echo esc_html__('Store not found.', 'ap-api-integration'); ?></p>
                </div>
                <?php
                $content = ob_get_clean();
            }
        } catch (\InvalidArgumentException $e) {
            $this->logger->log(
                '[WIDGET] [ERROR] Invalid store data format in StoreAddressWidget',
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
            ob_start();
            ?>
            <div class="mb-store-address__info">
                <span>
                    <?php echo esc_html($store->getStreetAddress() . ', ' . $store->getCity() . ', ' . $store->getState() . ' ' . $store->getZip()); ?>
                </span>
            </div><!-- /.mb-store-address__info -->
            <?php
            $content = ob_get_clean();
        }

        ?>
        <div class="mb-store-address" data-store-id="<?php echo esc_attr($store_id); ?>">
            <?php echo $content; ?>
        </div><!-- /.mb-store-address -->
        <?php
    }
}
