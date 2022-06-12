<?php // @codingStandardsIgnoreLine.
/**
 * WooCommerce Checkout Settings
 *
 * @package WooCommerce\Admin
 */

use Automattic\WooCommerce\Admin\Features\OnboardingTasks\Tasks\WooCommercePayments;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Settings_Payment_Gateways', false ) ) {
	return new WC_Settings_Payment_Gateways();
}

/**
 * WC_Settings_Payment_Gateways.
 */
class WC_Settings_Payment_Gateways extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'checkout'; // @todo In future versions this may make more sense as 'payment' however to avoid breakage lets leave this alone until we refactor settings APIs in general.
		$this->label = _x( 'Payments', 'Settings tab label', 'woocommerce' );

		add_action( 'woocommerce_admin_field_payment_gateways', array( $this, 'payment_gateways_setting' ) );
		parent::__construct();
	}

	/**
	 * Get own sections.
	 *
	 * @return array
	 */
	protected function get_own_sections() {
		return array(
			'' => __( 'Payment methods', 'woocommerce' ),
		);
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {
		$settings =
			array(
				array(
					'title' => __( 'Payment methods', 'woocommerce' ),
					'desc'  => __( 'Installed payment methods are listed below and can be sorted to control their display order on the frontend.', 'woocommerce' ),
					'type'  => 'title',
					'id'    => 'payment_gateways_options',
				),
				array(
					'type' => 'payment_gateways',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'payment_gateways_options',
				),
			);

		return apply_filters( 'woocommerce_payment_gateways_settings', $settings );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		//phpcs:disable WordPress.Security.NonceVerification.Recommended
		global $current_section;

		// Load gateways so we can show any global options they may have.
		$payment_gateways = WC()->payment_gateways->payment_gateways();

		if ( $current_section ) {
			foreach ( $payment_gateways as $gateway ) {
				if ( in_array( $current_section, array( $gateway->id, sanitize_title( get_class( $gateway ) ) ), true ) ) {
					if ( isset( $_GET['toggle_enabled'] ) ) {
						$enabled = $gateway->get_option( 'enabled' );

						if ( $enabled ) {
							$gateway->settings['enabled'] = wc_string_to_bool( $enabled ) ? 'no' : 'yes';
						}
					}
					$this->run_gateway_admin_options( $gateway );
					break;
				}
			}
		}

		parent::output();
		//phpcs:enable
	}

	/**
	 * Run the 'admin_options' method on a given gateway.
	 * This method exists to easy unit testing.
	 *
	 * @param object $gateway The gateway object to run the method on.
	 */
	protected function run_gateway_admin_options( $gateway ) {
		$gateway->admin_options();
	}

	/**
	 * Output payment gateway settings.
	 */
	public function payment_gateways_setting() {
		?>
		<tr valign="top">
		<td class="wc_payment_gateways_wrapper" colspan="2">
			<table class="wc_gateways widefat" cellspacing="0" aria-describedby="payment_gateways_options-description">
				<thead>
					<tr>
						<?php
						$default_columns = array(
							'sort'        => '',
							'name'        => __( 'Method', 'woocommerce' ),
							'status'      => __( 'Enabled', 'woocommerce' ),
							'description' => __( 'Description', 'woocommerce' ),
							'action'      => '',
						);

						$columns = apply_filters( 'woocommerce_payment_gateways_setting_columns', $default_columns );

						foreach ( $columns as $key => $column ) {
							echo '<th class="' . esc_attr( $key ) . '">' . esc_html( $column ) . '</th>';
						}
						?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( WC()->payment_gateways->payment_gateways() as $gateway ) {

							echo '<tr data-gateway_id="' . esc_attr( $gateway->id ) . '">';

							foreach ( $columns as $key => $column ) {
								if ( ! array_key_exists( $key, $default_columns ) ) {
									do_action( 'woocommerce_payment_gateways_setting_column_' . $key, $gateway );
									continue;
								}

								$width = '';

								if ( in_array( $key, array( 'sort', 'status', 'action' ), true ) ) {
									$width = '1%';
								}

								$method_title = $gateway->get_method_title() ? $gateway->get_method_title() : $gateway->get_title();
								$custom_title = $gateway->get_title();

								echo '<td class="' . esc_attr( $key ) . '" width="' . esc_attr( $width ) . '">';

								switch ( $key ) {
									case 'sort':
										?>
										<div class="wc-item-reorder-nav">
											<button type="button" class="wc-move-up" tabindex="0" aria-hidden="false" aria-label="<?php /* Translators: %s Payment gateway name. */ echo esc_attr( sprintf( __( 'Move the "%s" payment method up', 'woocommerce' ), $method_title ) ); ?>"><?php esc_html_e( 'Move up', 'woocommerce' ); ?></button>
											<button type="button" class="wc-move-down" tabindex="0" aria-hidden="false" aria-label="<?php /* Translators: %s Payment gateway name. */ echo esc_attr( sprintf( __( 'Move the "%s" payment method down', 'woocommerce' ), $method_title ) ); ?>"><?php esc_html_e( 'Move down', 'woocommerce' ); ?></button>
											<input type="hidden" name="gateway_order[]" value="<?php echo esc_attr( $gateway->id ); ?>" />
										</div>
										<?php
										break;
									case 'name':
										echo '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $gateway->id ) ) ) . '" class="wc-payment-gateway-method-title">' . wp_kses_post( $method_title ) . '</a>';

										if ( $method_title !== $custom_title ) {
											echo '<span class="wc-payment-gateway-method-name">&nbsp;&ndash;&nbsp;' . wp_kses_post( $custom_title ) . '</span>';
										}
										break;
									case 'description':
										echo wp_kses_post( $gateway->get_method_description() );
										break;
									case 'action':
										if ( wc_string_to_bool( $gateway->enabled ) ) {
											/* Translators: %s Payment gateway name. */
											echo '<a class="button alignright" aria-label="' . esc_attr( sprintf( __( 'Manage the "%s" payment method', 'woocommerce' ), $method_title ) ) . '" href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $gateway->id ) ) ) . '">' . esc_html__( 'Manage', 'woocommerce' ) . '</a>';
										} else {
											/* Translators: %s Payment gateway name. */
											echo '<a class="button alignright" aria-label="' . esc_attr( sprintf( __( 'Set up the "%s" payment method', 'woocommerce' ), $method_title ) ) . '" href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $gateway->id ) ) ) . '">' . esc_html__( 'Set up', 'woocommerce' ) . '</a>';
										}
										break;
									case 'status':
										echo '<a class="wc-payment-gateway-method-toggle-enabled" href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $gateway->id ) ) ) . '">';
										if ( wc_string_to_bool( $gateway->enabled ) ) {
											/* Translators: %s Payment gateway name. */
											echo '<span class="woocommerce-input-toggle woocommerce-input-toggle--enabled" aria-label="' . esc_attr( sprintf( __( 'The "%s" payment method is currently enabled', 'woocommerce' ), $method_title ) ) . '">' . esc_attr__( 'Yes', 'woocommerce' ) . '</span>';
										} else {
											/* Translators: %s Payment gateway name. */
											echo '<span class="woocommerce-input-toggle woocommerce-input-toggle--disabled" aria-label="' . esc_attr( sprintf( __( 'The "%s" payment method is currently disabled', 'woocommerce' ), $method_title ) ) . '">' . esc_attr__( 'No', 'woocommerce' ) . '</span>';
										}
										echo '</a>';
										break;
								}

								echo '</td>';
							}

							echo '</tr>';
						}
						/**
						 * Add "Other payment methods" link in WooCommerce -> Settings -> Payments
						 * When the store is in WC Payments eligible country.
						 * See https://github.com/woocommerce/woocommerce/issues/32130 for more details.
						 */
						if ( WooCommercePayments::is_supported() ) {
							$columns_count      = count( $columns );
							$link_text          = __( 'Other payment methods', 'woocommerce' );
							$external_link_icon = '<svg style="margin-left: 4px" class="gridicon gridicons-external needs-offset" height="18" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g><path d="M19 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6v2H5v12h12v-6h2zM13 3v2h4.586l-7.793 7.793 1.414 1.414L19 6.414V11h2V3h-8z"></path></g></svg>';
							echo '<tr>';
							// phpcs:ignore -- ignoring the error since the value is harded.
							echo "<td style='border-top: 1px solid #c3c4c7; background-color: #fff' colspan='{$columns_count}'>";
							echo "<a id='settings-other-payment-methods' href='" . esc_url( admin_url( 'admin.php?page=wc-addons&section=payment-gateways' ) ) . "' target='_blank' class='components-button is-tertiary'>";
							// phpcs:ignore
							echo $link_text;
							// phpcs:ignore
							echo $external_link_icon;
							echo '</a>';
							echo '</td>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		$wc_payment_gateways = WC_Payment_Gateways::instance();

		$this->save_settings_for_current_section();

		if ( ! $current_section ) {
			// If section is empty, we're on the main settings page. This makes sure 'gateway ordering' is saved.
			$wc_payment_gateways->process_admin_options();
			$wc_payment_gateways->init();
		} else {
			// There is a section - this may be a gateway or custom section.
			foreach ( $wc_payment_gateways->payment_gateways() as $gateway ) {
				if ( in_array( $current_section, array( $gateway->id, sanitize_title( get_class( $gateway ) ) ), true ) ) {
					do_action( 'woocommerce_update_options_payment_gateways_' . $gateway->id );
					$wc_payment_gateways->init();
				}
			}

			$this->do_update_options_action();
		}
	}
}

return new WC_Settings_Payment_Gateways();
