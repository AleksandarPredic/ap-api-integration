<?php

namespace ApApi\Widgets;

use Elementor\Controls_Manager;
use ApApi\Widgets\Traits\StoreTrait;

// Do not allow directly accessing this file.
if ( ! defined('ABSPATH')) {
    exit('Direct script access denied.');
}

if ( ! class_exists('\Elementor\Widget_Base')) {
    return;
}

/**
 * AP Store Phone Widget
 *
 * Elementor widget for displaying store phone number based on selected store ID.
 * Allows users to select a store and displays the phone number with clickable tel: link on the frontend.
 */
class StorePhoneWidget extends StoreBaseWidgetAbstract
{
    use StoreTrait;

    /**
     * {@inheritDoc}
     */
    public function get_widget_name(): string
    {
        return 'ap_store_phone_widget';
    }

    /**
     * {@inheritDoc}
     */
    public function get_widget_title(): string
    {
        return esc_html__('AP Store Phone', 'ap-api-integration');
    }

    /**
     * {@inheritDoc}
     */
    public function get_widget_icon(): string
    {
        return 'eicon-device-mobile';
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
     * Adds styling options for phone number display.
     *
     * @return void
     */
    protected function register_style_controls(): void
    {
        // Phone Link Style Section
        $this->start_controls_section(
            'section_phone_style',
            [
                'label' => esc_html__('Phone Link Style', 'ap-api-integration'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography Control
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'phone_typography',
                'label'    => esc_html__('Typography', 'ap-api-integration'),
                'selector' => '{{WRAPPER}} .mb-store-phone__info a',
            ]
        );

        // Text Alignment
        $this->add_responsive_control(
            'phone_alignment',
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
                    '{{WRAPPER}} .mb-store-phone' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Text Color (Normal State)
        $this->add_control(
            'phone_color',
            [
                'label'     => esc_html__('Text Color', 'ap-api-integration'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-phone__info a' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Hover Color
        $this->add_control(
            'phone_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'ap-api-integration'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mb-store-phone__info a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Text Decoration
        $this->add_control(
            'phone_text_decoration',
            [
                'label'     => esc_html__('Text Decoration', 'ap-api-integration'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'none',
                'options'   => [
                    'none'         => esc_html__('None', 'ap-api-integration'),
                    'underline'    => esc_html__('Underline', 'ap-api-integration'),
                    'overline'     => esc_html__('Overline', 'ap-api-integration'),
                    'line-through' => esc_html__('Line Through', 'ap-api-integration'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .mb-store-phone__info a' => 'text-decoration: {{VALUE}};',
                ],
            ]
        );

        // Hover Text Decoration
        $this->add_control(
            'phone_hover_text_decoration',
            [
                'label'     => esc_html__('Hover Text Decoration', 'ap-api-integration'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'underline',
                'options'   => [
                    'none'         => esc_html__('None', 'ap-api-integration'),
                    'underline'    => esc_html__('Underline', 'ap-api-integration'),
                    'overline'     => esc_html__('Overline', 'ap-api-integration'),
                    'line-through' => esc_html__('Line Through', 'ap-api-integration'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .mb-store-phone__info a:hover' => 'text-decoration: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Render widget output
     *
     * Displays the selected store's phone number on the frontend.
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
            <div class="mb-store-phone__no-selection">
                <p><?php echo esc_html__('Please select a store to view phone number.', 'ap-api-integration'); ?></p>
            </div>
            <?php
            $content = ob_get_clean();
        }

        // Fetch actual store data from repository
        $store = $this->repository->findStoreByBranchId((int)$store_id);
        if ( ! $store && empty($content)) {
            ob_start();
            ?>
            <div class="mb-store-phone__not-found">
                <p><?php echo esc_html__('Store not found.', 'ap-api-integration'); ?></p>
            </div>
            <?php
            $content = ob_get_clean();
        }

        if (empty($content)) {
            ob_start();
            ?>
            <div class="mb-store-phone__info">
                <span>
                    <a href="tel:<?php echo esc_attr($this->formatPhoneForTel($store->getPhone())); ?>"><?php echo esc_html($store->getPhone()); ?></a>
                </span>
            </div><!-- /.mb-store-phone__info -->
            <?php
            $content = ob_get_clean();
        }

        ?>
        <div class="mb-store-phone" data-store-id="<?php echo esc_attr($store_id); ?>">
            <?php echo $content; ?>
        </div><!-- /.mb-store-phone -->
        <?php
    }
}
