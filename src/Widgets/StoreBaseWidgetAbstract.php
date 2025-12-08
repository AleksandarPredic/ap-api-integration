<?php

namespace ApApi\Widgets;

use Elementor\Widget_Base;
use ApApi\Repositories\ApiStoreRepository;
use ApApi\Logger\Logger;

abstract class StoreBaseWidgetAbstract extends Widget_Base
{
    /**
     * Store repository instance
     *
     * @var ApiStoreRepository
     */
    protected ApiStoreRepository $repository;

    /**
     * Logger instance
     *
     * @var Logger
     */
    protected Logger $logger;

    /**
     * Constructor
     */
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        $this->repository = new ApiStoreRepository();
        $this->logger = Logger::getInstance();
    }

    /**
     * Get widget name
     *
     * @return string Widget name identifier
     */
    abstract public function get_widget_name(): string;

    /**
     * Get widget name
     *
     * @return string Widget name identifier
     */
    public function get_name(): string
    {
        return $this->get_widget_name();
    }

    /**
     * Get widget title
     *
     * @return string Widget display title
     */
    abstract public function get_widget_title(): string;

    /**
     * Get widget title
     *
     * @return string Widget display title
     */
    public function get_title(): string
    {
        return $this->get_widget_title();
    }

    /**
     * Get widget icon
     *
     * @return string Widget icon class
     */
    abstract public function get_widget_icon(): string;

    /**
     * Get widget icon
     *
     * @return string Widget icon class
     */
    public function get_icon(): string
    {
        return $this->get_widget_icon();
    }

    /**
     * Get widget categories
     *
     * @return array Widget categories
     */
    public function get_categories(): array
    {
        return ['theme-elements'];
    }

    /**
     * Register widget controls
     *
     * Sets up the Elementor controls for the widget, including
     * the store selection dropdown populated with available stores.
     *
     * @return void
     */
    protected function register_controls(): void
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Store Settings', 'ap-api-integration'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        try {
            $allStores = $this->repository->getAllStores();

            // Create options array from stores (ID => Name)
            $storeOptions = [];
            foreach ($allStores as $store) {
                $storeOptions[$store->getBranchId()] = $store->getStoreName();
            }

            // Sort $storeOptions by value name
            asort($storeOptions);
        } catch (\InvalidArgumentException $e) {
            $this->logger->log(
                '[WIDGET] [ERROR] Invalid store data format in widget controls',
                [
                    'widget_class' => get_class($this),
                    'exception_message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            $storeOptions = [0 => esc_html__('Error: Invalid store data format', 'ap-api-integration')];
        }

        $this->add_control(
            'store_id',
            [
                'label' => esc_html__('Select Store', 'ap-api-integration'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 0,
                'options' => $storeOptions,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     *
     * @return void
     */
    abstract protected function render_widget_content(): void;

    /**
     * Render widget output
     *
     * @return void
     */
    protected function render(): void
    {
        $this->render_widget_content();
    }
}
