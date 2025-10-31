<?php
namespace ETC\App\Controllers\Elementor\General;

/**
 * MailChimp widget.
 *
 * @since      2.0.0
 * @package    ETC
 * @subpackage ETC/Controllers/Elementor/General
 */
class Mail_Chimp extends \Elementor\Widget_Base {

	/**
	 * Retrieve heading widget name.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'etheme-mail-chimp';
	}

	/**
	 * Retrieve heading widget title.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'XStore Mail Chimp', 'xstore-core' );
	}

	/**
	 * Retrieve heading widget icon.
	 *
	 * @since 2.1.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-mailchimp';
	}


    /**
     * Get widget keywords.
     *
     * @since 2.1.3
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'mailchimp' ];
    }

    /**
     * Get widget categories.
     *
     * @since 2.1.3
     * @access public
     *
     * @return array Widget categories.
     */
	public function get_categories() {
		return ['eight_theme_general'];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_script_depends() {
		return ['etheme_mailchimp'];
	}
	
	/**
	 * Get widget dependency.
	 *
	 * @since 4.0.12
	 * @access public
	 *
	 * @return array Widget dependency.
	 */
	public function get_style_depends() {
		return ['etheme-elementor-mailchimp'];
	}
	
	/**
	 * Help link.
	 *
	 * @since 4.1.5
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return 'https://xstore.helpscoutdocs.com/article/122-elementor-live-copy-option/';
	}
	
	/**
	 * Register mail chimp widget controls.
	 *
	 * @since 2.1.3
	 * @access protected
	 */
    protected function register_controls() {
        $this->start_controls_section(
            'etheme_mail_chimp_section_form', [
                'label' => esc_html__( 'Form', 'xstore-core' ),
            ]
        );

		if ( ! get_theme_mod( 'mail_chimp_api', '' ) ) {
			$message = sprintf(
				__( 'Please enter your mail chimp API Key <a href="%1$s">here in mail chimp section</a>.<br>If dont have api key <a href="%2$s" target="_blank">click here</a> to generate one.', 'xstore-core' ),
				admin_url( 'customize.php' ),
				esc_url( 'https://login.mailchimp.com/' )
			);

			$this->add_control(
				'important_note',
				[
					'label' => __( 'Important Note', 'xstore-core' ),
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw' => $message,
				// 'content_classes' => 'your-class',
				]
			);

		}      

		$this->add_control(
            'etheme_mail_chimp_select_check_api',
            [
                'raw' => '<strong>' . esc_html__( 'Please note!', 'xstore-core' ) . '</strong> ' . esc_html__( 'Please set API Key in xstore-core Dashboard - User Data - MailChimp and Create Campaign..', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'render_type' => 'ui',
                'condition' => [
                    'etheme_mail_chimp_select_listed_id' => '',
                ],
            ]
        );

		$this->add_control(
			'etheme_mail_chimp_select_listed_id',
			[
				'label' => esc_html__( 'Select List', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->__get_lists(),
				'description' => esc_html__('Create a campaign in mailchimp account <a href="https://mailchimp.com/help/create-a-regular-email-campaign/#Create_a_campaign" target="_blank"> Create Campaign</a>', 'xstore-core'),
			]
		);	

		// show name control
		$this->add_control(
			'etheme_mail_chimp_section_form_name_show',
			[
				'label' => esc_html__( 'Show Name', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);
		// first name
		$this->add_control(
			'etheme_mail_first_heading_title',
			[
				'label' => esc_html__( 'First Name', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'condition' => [
					'etheme_mail_chimp_section_form_name_show' => 'yes',
				],
			]
		);
		$this->add_control(
            'etheme_mail_chimp_first_name_label',
            [
                'label' => esc_html__( 'Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'First name', 'xstore-core' ),
				'label_block'	 => false,
				'condition' => [
					'etheme_mail_chimp_section_form_name_show' => 'yes'
				]
            ]
		);
		$this->add_control(
            'etheme_mail_chimp_first_name_placeholder',
            [
                'label' => esc_html__( 'Placeholder', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Your frist name', 'xstore-core' ),
				'label_block'	 => false,
				'condition' => [
					'etheme_mail_chimp_section_form_name_show' => 'yes'
				]
            ]
		);
		$this->add_control(
			'etheme_mail_chimp_first_name_icon_show',
			[
				'label' => esc_html__( 'Show Input Group Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'etheme_mail_chimp_section_form_name_show' => 'yes'
				]
			]
		);
		$this->add_control(
            'etheme_mail_chimp_first_name_icons',
            [
                'label' => esc_html__( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'etheme_mail_chimp_first_name_icon',
                'default' => [
                    'value' => 'far fa-user',
                    'library' => 'fa-regular',
                ],
                'condition' => [
					'etheme_mail_chimp_first_name_icon_show' => 'yes',
					'etheme_mail_chimp_section_form_name_show' => 'yes'
                ]
            ]
		);
		$this->add_control(
			'etheme_mail_chimp_first_name_icon_before_after',
			[
				'label' => esc_html__( 'Before After', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before'  => esc_html__( 'Before', 'xstore-core' ),
					'after' => esc_html__( 'After', 'xstore-core' ),
				],
				'condition' => [
					'etheme_mail_chimp_first_name_icon_show' => 'yes',
					'etheme_mail_chimp_first_name_icons!' => '',
					'etheme_mail_chimp_section_form_name_show' => 'yes'
				]
			]
		);

		$this->add_control(
			'etheme_mail_last_and_first_name_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'etheme_mail_chimp_section_form_name_show' => 'yes'
				]
			]
		);

		// last name
		$this->add_control(
			'etheme_mail_last_heading_title',
			[
				'label' => esc_html__( 'Last Name:', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'condition' => [
					'etheme_mail_chimp_section_form_name_show' => 'yes',
				],
			]
		);
		$this->add_control(
            'etheme_mail_chimp_last_name_label',
            [
                'label' => esc_html__( 'Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Last name:', 'xstore-core' ),
				'label_block'	 => false,
				'condition' => [
					'etheme_mail_chimp_section_form_name_show' => 'yes'
				]
            ]
        );
		$this->add_control(
            'etheme_mail_chimp_last_name_placeholder',
            [
                'label' => esc_html__( 'Placeholder', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Your last name', 'xstore-core' ),
				'label_block'	 => false,
				'condition' => [
					'etheme_mail_chimp_section_form_name_show' => 'yes'
				]
            ]
		);

		$this->add_control(
			'etheme_mail_chimp_last_name_icon_show',
			[
				'label' => esc_html__( 'Show Input Group Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'etheme_mail_chimp_section_form_name_show' => 'yes'
				]
			]
		);
		$this->add_control(
            'etheme_mail_chimp_last_name_icons',
            [
                'label' => esc_html__( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'etheme_mail_chimp_last_name_icon',
                'default' => [
                    'value' => 'far fa-user',
                    'library' => 'fa-regular',
                ],
                'condition' => [
					'etheme_mail_chimp_last_name_icon_show' => 'yes',
					'etheme_mail_chimp_section_form_name_show' => 'yes'
                ]
            ]
		);
		$this->add_control(
			'etheme_mail_chimp_last_name_icon_before_after',
			[
				'label' => esc_html__( 'Before After', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before'  => esc_html__( 'Before', 'xstore-core' ),
					'after' => esc_html__( 'After', 'xstore-core' ),
				],
				'condition' => [
					'etheme_mail_chimp_last_name_icon_show' => 'yes',
					'etheme_mail_chimp_last_name_icons!' => '',
					'etheme_mail_chimp_section_form_name_show' => 'yes'
				]
			]
		);

		// phone number
		$this->add_control(
			'etheme_mail_chimp_section_form_phone_show',
			[
				'label' => esc_html__( 'Show Phone:', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'etheme_mail_phone_heading_title',
			[
				'label' => esc_html__( 'Phone:', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'condition' => [
					'etheme_mail_chimp_section_form_phone_show' => 'yes',
				],
			]
		);
		$this->add_control(
            'etheme_mail_chimp_phone_label',
            [
                'label' => esc_html__( 'Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Phone', 'xstore-core' ),
				'label_block'	 => false,
				'condition' => [
					'etheme_mail_chimp_section_form_phone_show' => 'yes'
				]
            ]
        );
		$this->add_control(
            'etheme_mail_chimp_phone_placeholder',
            [
                'label' => esc_html__( 'Placeholder', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Your phone No', 'xstore-core' ),
				'label_block'	 => false,
				'condition' => [
					'etheme_mail_chimp_section_form_phone_show' => 'yes'
				]
            ]
		);
		$this->add_control(
			'etheme_mail_chimp_phone_icon_show',
			[
				'label' => esc_html__( 'Show Input Group Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'etheme_mail_chimp_section_form_phone_show' => 'yes'
				]
			]
		);
		$this->add_control(
            'etheme_mail_chimp_phone_icons',
            [
                'label' => esc_html__( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'etheme_mail_chimp_phone_icon',
                'default' => [
                    'value' => 'far fa-phone',
                    'library' => 'fa-regular',
                ],
                'condition' => [
					'etheme_mail_chimp_phone_icon_show' => 'yes',
					'etheme_mail_chimp_section_form_phone_show' => 'yes'
                ]
            ]
		);
		$this->add_control(
			'etheme_mail_chimp_phone_icon_before_after',
			[
				'label' => esc_html__( 'Before After', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before'  => esc_html__( 'Before', 'xstore-core' ),
					'after' => esc_html__( 'After', 'xstore-core' ),
				],
				'condition' => [
					'etheme_mail_chimp_phone_icon_show' => 'yes',
					'etheme_mail_chimp_phone_icons!' => '',
					'etheme_mail_chimp_section_form_phone_show' => 'yes'
				]
			]
		);

		// email address
		$this->add_control(
			'etheme_mail_email_address_heading_title',
			[
				'label' => esc_html__( 'Email Address:', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
            'etheme_mail_chimp_email_address_label',
            [
                'label' => esc_html__( 'Label', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Email address', 'xstore-core' ),
				'label_block'	 => false,

            ]
        );
		$this->add_control(
            'etheme_mail_chimp_email_address_placeholder',
            [
                'label' => esc_html__( 'Placeholder', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' 	 => esc_html__( 'Your email address', 'xstore-core' ),
				'label_block'	 => false,
            ]
		);

		$this->add_control(
			'etheme_mail_chimp_email_icon_show',
			[
				'label' => esc_html__( 'Show Input Group Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
            'etheme_mail_chimp_email_icons',
            [
                'label' => esc_html__( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'etheme_mail_chimp_email_icon',
                'default' => [
                    'value' => 'far fa-address-book',
                    'library' => 'fa-regular',
                ],
                'condition' => [
					'etheme_mail_chimp_email_icon_show' => 'yes',
                ]
            ]
		);
		$this->add_control(
			'etheme_mail_chimp_email_icon_before_after',
			[
				'label' => esc_html__( 'Before After', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before'  => esc_html__( 'Before', 'xstore-core' ),
					'after' => esc_html__( 'After', 'xstore-core' ),
				],
				'condition' => [
					'etheme_mail_chimp_email_icon_show' => 'yes',
					'etheme_mail_chimp_email_icons!' => '',
				]
			]
		);

		$this->add_control(
			'etheme_mail_chimp_email_and_button_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// submit button text
		$this->add_control(
            'etheme_mail_chimp_submit',
            [
                'label' => esc_html__( 'Submit Button Text', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Sign Up', 'xstore-core' ),
				'placeholder' => '',
				'label_block'	 => false,
            ]
        );
		$this->add_control(
			'etheme_mail_chimp_submit_button_heading',
			[
				'label' => esc_html__( 'Submit Button:', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'etheme_mail_chimp_submit_icon_show',
			[
				'label' => esc_html__( 'Show Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'etheme_mail_chimp_submit_icons',
			[
				'label' => esc_html__( 'Button Icons', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'etheme_mail_chimp_submit_icon',
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
				'condition' => [
					'etheme_mail_chimp_submit_icon_show' => 'yes'
				]
			]
		);
		$this->add_control(
			'etheme_mail_chimp_submit_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before'  => esc_html__( 'Before', 'xstore-core' ),
					'after' => esc_html__( 'After', 'xstore-core' ),
				],
				'condition' => [
					'etheme_mail_chimp_submit_icon_show' => 'yes',
					'etheme_mail_chimp_submit_icons!' => ''
				]
			]
		);

		$this->add_control(
            'etheme_mail_chimp_form_style_switcher',
            [
                'label' =>esc_html__( 'Form Style', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'yes',
                'options' => [
                    'yes' =>esc_html__( 'Inline', 'xstore-core' ),
                    'no' =>esc_html__( 'Full Width', 'xstore-core' ),
                ],
            ]
		);
		
		$this->add_control(
			'etheme_mail_chimp_success_message',
			[
				'label' => __( 'Success Message', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Successfully listed this email', 'xstore-core' ),
				'placeholder' => __( 'Type your title here', 'xstore-core' ),
			]
		);

		$this->end_controls_section();
		// end content form

		// label
		$this->start_controls_section(
			'etheme_mail_chimp_input_label_style',
			[
				'label' => esc_html__( 'Label', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'etheme_mail_chimp_input_label_typography',
				'label' => esc_html__( 'Typography', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme_input_label',
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_label_color',
			[
				'label' => esc_html__( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .etheme_input_label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_label_margin',
			[
				'label' => esc_html__( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme_input_label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// input style
		$this->start_controls_section(
			'etheme_mail_chimp_input_style',
			[
				'label' => esc_html__( 'Input', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'etheme_mail_chimp_input_typography',
				'label' => esc_html__( 'Typography', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme_form_control',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'etheme_mail_chimp_input_style_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme_form_control',
				'exclude' => [
					'image'
				]
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_style_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme_form_control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'etheme_mail_chimp_input_style_border',
				'label' => esc_html__( 'Border', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme_form_control',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'etheme_mail_chimp_input_style_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme_form_control, {{WRAPPER}} .etheme_form_control:focus',
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_style_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default'		=> [
					'top'		=> 0,
					'right'		=> 20,
					'bottom'	=> 0,
					'left'		=> 20
				],
				'selectors' => [
					'{{WRAPPER}} .etheme_form_control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'etheme_mail_chimp_input_style_width__switch',
			[
				'label' => esc_html__( 'Use Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_style_width',
			[
				'label' => esc_html__( 'Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'	=> [
					'unit'	=> '%',
					'size'	=> 66
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etheme_input_container' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
				],
				'condition'	=> [
					'etheme_mail_chimp_input_style_width__switch' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_style_margin_bottom',
			[
				'label' => esc_html__( 'Margin Bottom', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme_input_wraper:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'etheme_mail_chimp_form_style_switcher!' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_style_margin_right',
			[
				'label' => esc_html__( 'Margin Right', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme_inline_form .etheme_input_wraper:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'etheme_mail_chimp_form_style_switcher' => 'yes'
				]
			]
		);

		$this->add_control(
			'etheme_mail_chimp_input_style_placeholder_heading',
			[
				'label' => esc_html__( 'Placeholder', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_style_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .etheme_form_control::-webkit-input-placeholder' => 'color: {{VALUE}}',
					'{{WRAPPER}} .etheme_form_control::-moz-placeholder' => 'color: {{VALUE}}',
					'{{WRAPPER}} .etheme_form_control:-ms-input-placeholder' => 'color: {{VALUE}}',
					'{{WRAPPER}} .etheme_form_control:-moz-placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_style_placeholder_font_size',
			[
				'label' => esc_html__( 'Font Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme_form_control::-webkit-input-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .etheme_form_control::-moz-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .etheme_form_control:-ms-input-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .etheme_form_control:-moz-placeholder' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'etheme_mail_chimp_button_style_holder',
			[
				'label' => esc_html__( 'Button', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'etheme_mail_chimp_button_typography',
				'label' => esc_html__( 'Typography', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme-mail-submit',
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_button_border_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default'	=> [
					'top'		=> 8,
					'right'		=> 20,
					'bottom'	=> 8,
					'left'		=> 20
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'etheme_mail_chimp_button_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme-mail-submit',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'etheme_mail_chimp_button_border',
				'label' => esc_html__( 'Border', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme-mail-submit',
			]
		);

		$this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'etheme_mail_chimp_button_title_shadow',
                'selector' => '{{WRAPPER}} .etheme-mail-submit' ,
            ]
		);

		$this->add_control(
			'etheme_mail_chimp_button_style_use_width_height',
			[
				'label' => esc_html__( 'Use Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_button_width',
			[
				'label' => esc_html__( 'Width', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-submit' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'etheme_mail_chimp_button_style_use_width_height' => 'yes'
				]
			]
		);


		$this->add_responsive_control(
			'etheme_mail_chimp_button_style_margin',
			[
				'label' => esc_html__( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
            'etheme_mail_chimp_button_normal_and_hover_tabs'
        );
        $this->start_controls_tab(
            'etheme_mail_chimp_button_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'xstore-core' ),
            ]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_button_color',
			[
				'label' => esc_html__( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-submit' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-mail-submit svg path'	=> 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'etheme_mail_chimp_button_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .etheme-mail-submit',
				'exclude' => [
					'image'
				]
			]
		);

		$this->end_controls_tab();
        $this->start_controls_tab(
            'etheme_mail_chimp_button_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'xstore-core' ),
            ]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_button_color_hover',
			[
				'label' => esc_html__( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-submit:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme-mail-submit:hover svg path'	=> 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'etheme_mail_chimp_button_background_hover',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .etheme-mail-submit:before',
				'exclude' => [
					'image'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'etheme_mail_chimp_button_icon_heading',
			[
				'label' => esc_html__( 'Icon', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_button_icon_padding_right',
			[
				'label' => esc_html__( 'Icon Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-submit > i, {{WRAPPER}} .etheme-mail-submit > svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'etheme_mail_chimp_submit_icon_position' => 'before'
				]
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_button_icon_padding_left',
			[
				'label' => esc_html__( 'Icon Spacing', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-submit > i, {{WRAPPER}} .etheme-mail-submit > svg' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'etheme_mail_chimp_submit_icon_position' => 'after'
				]
			]
		);

		$this->add_responsive_control(
            'etheme_simple_tab_title_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .etheme-mail-submit > i, {{WRAPPER}} .etheme-mail-submit > i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .etheme-mail-submit > i, {{WRAPPER}} .etheme-mail-submit > svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'etheme_mail_chimp_input_icon_style_holder',
			[
				'label' => esc_html__( 'Input Icon', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'etheme_mail_chimp_input_icon_background',
				'label' => esc_html__( 'Background', 'xstore-core' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .etheme_input_group_text',
				'exclude' => [
					'image'
				]
			]
		);

		$this->add_control(
			'etheme_mail_chimp_input_icon_color_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_icon_color',
			[
				'label' => esc_html__( 'Color', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .etheme_input_group_text i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .etheme_input_group_text svg path'	=> 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_icon_font_size',
			[
				'label' => esc_html__( 'Font Size', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .etheme_input_group_text' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .etheme_input_group_text svg'	=> 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'etheme_mail_chimp_input_icon_border',
				'label' => esc_html__( 'Border', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme_input_group_text',
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_icon_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme_input_group_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_input_icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme_input_group_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'etheme_mail_chimp_success_error',
			[
				'label' => esc_html__( 'Success & Error message', 'xstore-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_success_error_padding',
			[
				'label' => esc_html__( 'Padding', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'etheme_mail_chimp_success_error_margin',
			[
				'label' => esc_html__( 'Margin', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'etheme_mail_chimp_success_error_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'xstore-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etheme-mail-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(), [
			'name'		 => 'etheme_mail_chimp_success_error_typography',
			'selector'	 => '{{WRAPPER}} .etheme-mail-message',
			]
		);

		$this->add_control(
            'etheme_mail_chimp_success_heading',
            [
                'label' => esc_html__( 'Success:', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
		);
		
		$this->add_responsive_control(
			'etheme_mail_chimp_success_color', [
				'label'		 =>esc_html__( 'Color', 'xstore-core' ),
				'type'		 => \Elementor\Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .etheme-mail-message.success' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
				'name'     => 'etheme_mail_chimp_success_bg_color',
				'label'		 => esc_html__( 'Background Color', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme-mail-message.success',
            ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'etheme_mail_chimp_success_border',
				'label' => esc_html__( 'Border', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme-mail-message.success',
			]
		);

		$this->add_control(
            'etheme_mail_chimp_error_heading',
            [
                'label' => esc_html__( 'Error:', 'xstore-core' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
		);
		
		$this->add_responsive_control(
			'etheme_mail_chimp_error_color', [
				'label'		 =>esc_html__( 'Color', 'xstore-core' ),
				'type'		 => \Elementor\Controls_Manager::COLOR,
				'selectors'	 => [
					'{{WRAPPER}} .etheme-mail-message.error' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
				'name'     => 'etheme_mail_chimp_error_bg_color',
				'label'		 => esc_html__( 'Background Color', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme-mail-message.error',
            ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'etheme_mail_chimp_error_border',
				'label' => esc_html__( 'Border', 'xstore-core' ),
				'selector' => '{{WRAPPER}} .etheme-mail-message.error',
			]
		);


		$this->end_controls_section();
		
	}


	protected function render( ) {
		$settings = $this->get_settings_for_display();
		extract($settings);

		ob_start();
        ?>
        <div class="etheme-wid-con">
		 <div class="etheme-mail-chimp">
			<form method="post" class="etheme_mailchimpform" data-nonce="<?php echo wp_create_nonce( 'etheme_mailchimp' ); ?>" data-listed="<?php echo esc_attr($etheme_mail_chimp_select_listed_id);?>" data-success-message="<?php echo esc_attr($etheme_mail_chimp_success_message); ?>">
			<div class="etheme-mail-message"></div>

				<div class="etheme_form_wraper <?php if($etheme_mail_chimp_form_style_switcher == 'yes'): ?>etheme_inline_form<?php endif;?>">
				<?php if(isset($etheme_mail_chimp_section_form_name_show) && $etheme_mail_chimp_section_form_name_show == 'yes'):?>
					<div class="etheme-mail-chimp-name etheme_input_wraper etheme_input_container">
						<?php // if( strlen($etheme_mail_chimp_first_name_label) > 1 || strlen($etheme_mail_chimp_first_name_placeholder) > 1): ?>
						<div class="etheme_form_group">
							<?php if($etheme_mail_chimp_first_name_label != ''): ?>
							<label class="etheme_input_label"><?php esc_html_e($etheme_mail_chimp_first_name_label);?> </label>
							<?php endif; ?>
							<div class="etheme_input_element_container <?php if(($etheme_mail_chimp_first_name_icon_show == 'yes') && ($etheme_mail_chimp_first_name_icons != '')) : ?>etheme_input_group<?php endif; ?>">
								<?php if(($etheme_mail_chimp_first_name_icon_show == 'yes') && ($etheme_mail_chimp_first_name_icons != '') && ($etheme_mail_chimp_first_name_icon_before_after == 'before')) : ?>
								<div class="etheme_input_group_prepend">
									<div class="etheme_input_group_text">
										<?php
										\Elementor\Icons_Manager::render_icon( $settings['etheme_mail_chimp_first_name_icons'], [ 'aria-hidden' => 'true' ] );
										?>
									</div>
								</div>
								<?php endif; ?>
								<input type="text" class="etheme_user_first etheme_form_control <?php if(($etheme_mail_chimp_first_name_icon_show == 'yes') && ($etheme_mail_chimp_first_name_icons != '') && ($etheme_mail_chimp_first_name_icon_before_after == 'after')) : ?> etheme_append_input <?php endif; ?>"  name="firstname" placeholder="<?php esc_html_e($etheme_mail_chimp_first_name_placeholder);?>" required />

								<?php if(($etheme_mail_chimp_first_name_icon_show == 'yes') && ($etheme_mail_chimp_first_name_icons != '') && ($etheme_mail_chimp_first_name_icon_before_after == 'after')) : ?>
								<div class="etheme_input_group_append">
									<div class="etheme_input_group_text">
										<?php
										\Elementor\Icons_Manager::render_icon( $settings['etheme_mail_chimp_first_name_icons'], [ 'aria-hidden' => 'true' ] );
										?>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
						<?php // endif; ?>
					</div>
				<?php endif; ?>
				<?php if(isset($etheme_mail_chimp_section_form_name_show) && $etheme_mail_chimp_section_form_name_show == 'yes'):?>
					<div class="etheme-mail-chimp-name etheme_input_wraper etheme_input_container">
						<?php // if( strlen($etheme_mail_chimp_last_name_label) > 1 || strlen($etheme_mail_chimp_last_name_placeholder) > 1): ?>
						<div class="etheme_form_group">
							<?php if($etheme_mail_chimp_last_name_label != ''): ?>
							<label class="etheme_input_label"><?php esc_html_e($etheme_mail_chimp_last_name_label);?> </label>
							<?php endif; ?>
							<div class="etheme_input_element_container <?php if(($etheme_mail_chimp_last_name_icon_show == 'yes') && ($etheme_mail_chimp_last_name_icons != '')) : ?>etheme_input_group<?php endif; ?>">
								<?php if(($etheme_mail_chimp_last_name_icon_show == 'yes') && ($etheme_mail_chimp_last_name_icons != '') && ($etheme_mail_chimp_last_name_icon_before_after == 'before')) : ?>
								<div class="etheme_input_group_prepend">
									<div class="etheme_input_group_text">
										<?php
										\Elementor\Icons_Manager::render_icon( $settings['etheme_mail_chimp_last_name_icons'], [ 'aria-hidden' => 'true' ] );
										?>
									</div>
								</div>
								<?php endif; ?>
								<input type="text" class="etheme_user_last etheme_form_control <?php if(($etheme_mail_chimp_last_name_icon_show == 'yes') && ($etheme_mail_chimp_last_name_icons != '') && ($etheme_mail_chimp_last_name_icon_before_after == 'after')) : ?> etheme_append_input <?php endif; ?>" name="lastname" placeholder="<?php esc_html_e($etheme_mail_chimp_last_name_placeholder);?>" required />

								<?php if(($etheme_mail_chimp_last_name_icon_show == 'yes') && ($etheme_mail_chimp_last_name_icons != '') && ($etheme_mail_chimp_last_name_icon_before_after == 'after')) : ?>
								<div class="etheme_input_group_append">
									<div class="etheme_input_group_text">
										<?php
										\Elementor\Icons_Manager::render_icon( $settings['etheme_mail_chimp_last_name_icons'], [ 'aria-hidden' => 'true' ] );
										?>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
						<?php //endif; ?>
					</div>
				<?php endif;
				if(isset($etheme_mail_chimp_section_form_phone_show) && $etheme_mail_chimp_section_form_phone_show == 'yes'):?>
					<div class="etheme-mail-chimp-phone etheme_input_wraper etheme_input_container">
						<div class="etheme_form_group">
							<?php if($etheme_mail_chimp_phone_label != ''): ?>
							<label class="etheme_input_label"><?php esc_html_e($etheme_mail_chimp_phone_label);?> </label>
							<?php endif; ?>
							<div class="etheme_input_element_container <?php if(($etheme_mail_chimp_phone_icon_show == 'yes') && ($etheme_mail_chimp_phone_icons != '')) : ?>etheme_input_group<?php endif; ?>">
								<?php if(($etheme_mail_chimp_phone_icon_show == 'yes') && ($etheme_mail_chimp_phone_icons != '') && ($etheme_mail_chimp_phone_icon_before_after == 'before')) : ?>
								<div class="etheme_input_group_prepend">
									<div class="etheme_input_group_text">
										<?php
										\Elementor\Icons_Manager::render_icon( $settings['etheme_mail_chimp_phone_icons'], [ 'aria-hidden' => 'true' ] );
										?>
									</div>
								</div>
								<?php endif; ?>
								<input type="phone" class="etheme_mail_phone etheme_form_control <?php if(($etheme_mail_chimp_phone_icon_show == 'yes') && ($etheme_mail_chimp_phone_icons != '') && ($etheme_mail_chimp_phone_icon_before_after == 'after')) : ?> etheme_append_input <?php endif; ?>" name="phone" placeholder="<?php esc_html_e($etheme_mail_chimp_phone_placeholder);?>" required />

								<?php if(($etheme_mail_chimp_phone_icon_show == 'yes') && ($etheme_mail_chimp_phone_icons != '') && ($etheme_mail_chimp_phone_icon_before_after == 'after')) : ?>
								<div class="etheme_input_group_append">
									<div class="etheme_input_group_text">
										<?php
										\Elementor\Icons_Manager::render_icon( $settings['etheme_mail_chimp_phone_icons'], [ 'aria-hidden' => 'true' ] );
										?>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
					<div class="etheme-mail-chimp-email etheme_input_wraper etheme_input_container">
						<div class="etheme_form_group">
							<?php if($etheme_mail_chimp_email_address_label != ''): ?>
							<label class="etheme_input_label"><?php esc_html_e($etheme_mail_chimp_email_address_label);?> </label>
							<?php endif; ?>
							<div class="etheme_input_element_container <?php if(($etheme_mail_chimp_email_icon_show == 'yes') && ($etheme_mail_chimp_email_icons != '')) : ?>etheme_input_group<?php endif; ?>">
								<?php if(($etheme_mail_chimp_email_icon_show == 'yes') && ($etheme_mail_chimp_email_icons != '') && ($etheme_mail_chimp_email_icon_before_after == 'before')) : ?>
								<div class="etheme_input_group_prepend">
									<div class="etheme_input_group_text">
										<?php
										\Elementor\Icons_Manager::render_icon( $settings['etheme_mail_chimp_email_icons'], [ 'aria-hidden' => 'true' ] );
										?>
									</div>
								</div>
								<?php endif; ?>
								<input type="email" name="email" class="etheme_mail_email etheme_form_control <?php if(($etheme_mail_chimp_email_icon_show == 'yes') && ($etheme_mail_chimp_email_icons != '') && ($etheme_mail_chimp_email_icon_before_after == 'after')) : ?> etheme_append_input <?php endif; ?>" placeholder="<?php esc_html_e($etheme_mail_chimp_email_address_placeholder);?>" required />

								<?php if(($etheme_mail_chimp_email_icon_show == 'yes') && ($etheme_mail_chimp_email_icons != '') && ($etheme_mail_chimp_email_icon_before_after == 'after')) : ?>
								<div class="etheme_input_group_append">
									<div class="etheme_input_group_text">
										<?php
										\Elementor\Icons_Manager::render_icon( $settings['etheme_mail_chimp_email_icons'], [ 'aria-hidden' => 'true' ] );
										?>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="etheme_submit_input_holder etheme_input_wraper">
						<button type="submit" class="etheme-mail-submit" name="etheme_mail_chimp"><?php if(($etheme_mail_chimp_submit_icon_show == 'yes') && ($etheme_mail_chimp_submit_icons != '') && ($etheme_mail_chimp_submit_icon_position == 'before')): ?> 

							<?php
							\Elementor\Icons_Manager::render_icon( $settings['etheme_mail_chimp_submit_icons'], [ 'aria-hidden' => 'true' ] );
							?>

							<?php endif; ?><?php esc_html_e($etheme_mail_chimp_submit);?><?php if(($etheme_mail_chimp_submit_icon_show == 'yes') && ($etheme_mail_chimp_submit_icons != '') && ($etheme_mail_chimp_submit_icon_position == 'after')): ?> 

								<?php
								\Elementor\Icons_Manager::render_icon( $settings['etheme_mail_chimp_submit_icons'], [ 'aria-hidden' => 'true' ] );
								?>

							<?php endif; ?></button>
					</div>
				</div>
			</form>
		 </div>
        </div>
        <?php

        ob_end_flush();
    }

	public function __get_lists(){
		$options = [ '' => 'Select List'];


		$dataApi 	= array(
			'token' => function_exists('etheme_get_option') ? etheme_get_option( 'mail_chimp_api', '' ) : '',
			'list' => 'd52efa0bf6',
		);

		$token 		= isset($dataApi['token']) ? $dataApi['token'] : '';

		$server = explode('-', $token);

		if(!isset($server[1])){
			return $options;
		}

		$url = 'https://'.$server[1].'.api.mailchimp.com/3.0/lists?apikey='.$token;	

		$response = wp_remote_get($url, []);

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$headers = $response['headers']; 
			$body    = (array) json_decode( $response['body'] ); 
			$listed = isset( $body['lists'] ) ? $body['lists'] : [];

			if( is_array($listed) && sizeof($listed) > 0):
				foreach($listed as $v):
					$options[$v->id] = $v->name;
				endforeach;
			endif;
		}
		return  $options;
	}
}