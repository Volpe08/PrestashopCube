<?php
/**
 * AvanamBuilder - Website Builder
 *
 * NOTICE OF LICENSE
 *
 * @author    avanam.org
 * @copyright avanam.org
 * @license   You can not resell or redistribute this software.
 *
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

use AvanamBuilder\Wp_Helper;

class AdminAvanamBuilderLicenseController extends ModuleAdminController
{
    public $name;

    public function __construct()
    {		
        $this->bootstrap = true;
		
        parent::__construct();

        if (!$this->module->active) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminDashboard'));
        }
		
        $this->name = 'AdminAvanamBuilderLicense';
    }
	
    public function initToolBarTitle()
    {
        $this->toolbar_title[] = $this->trans('Avanam - License', [], 'Modules.Avanambuilder.Admin');
    }
	
    public function renderList()
    {
		ob_start();
		//if (defined('PARTNER_LICENSE_API_URL') && defined('BUILDER_PARTNER_NAME')) {
			$this->render_manually_activation_widget_partner();
		// } else {
		//	$this->render_manually_activation_widget();
		//}
		$html = ob_get_clean();
		
        return parent::renderList() . $html;
    }
	
    private function render_manually_activation_widget_partner() {
		$license_key = Wp_Helper::api_get_license_key();
		?>
		<form class="form-horizontal" method="post" action="<?php echo Wp_Helper::get_exit_to_dashboard( $this->name ); ?>">
			<div id="configuration_fieldset_general" class="panel ">
				<div class="panel-heading"><i class="icon-cogs"></i> <?php Wp_Helper::_e( 'License', 'elementor' ); ?></div>
				<div class="form-wrapper">	
					<div class="form-group">
						<label class="control-label col-lg-12" style="text-align:left"><?php Wp_Helper::_e( 'The Avanam Builder is not sold individually; it is available only through our partners only. The builder license can be activated via the partner module license manager.', 'elementor' ); ?></label>
					</div>

					<div class="form-group">
						<?php 
						$license_data = Wp_Helper::api_get_license_data(true) ?: null;

						$statuses = [
							Wp_Helper::STATUS_EXPIRED       => ['text' => 'Expired', 'message' => 'Your License Has Expired. Renew your license today to keep getting feature updates, premium support and unlimited access to the template library.'],
							Wp_Helper::STATUS_SITE_INACTIVE => ['text' => 'Mismatch', 'message' => 'Your license key doesn\'t match your current domain. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.'],
							Wp_Helper::STATUS_INVALID       => ['text' => 'Invalid', 'message' => 'Your license key doesn\'t match your current domain. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.'],
							Wp_Helper::STATUS_DISABLED      => ['text' => 'Disabled', 'message' => 'Your license key has been cancelled (most likely due to a refund request). Please consider acquiring a new license.'],
							Wp_Helper::STATUS_VALID         => ['text' => 'Active', 'message' => 'Your license has been activated.'],
							Wp_Helper::STATUS_INACTIVE      => ['text' => 'Inactive', 'message' => 'Your license has not been activated.'],
						];

						// Only set license status if it exists in the response
						$license_status = isset($license_data['license']) ? $license_data['license'] : 'Inactive';
						
						// Set text and color based on license status
						if (isset($statuses[$license_status])) {
							$status_text = $statuses[$license_status]['text'];
							$color = ($license_status === Wp_Helper::STATUS_VALID) ? '#008000' : '#ff0000';
						} else {
							$status_text = 'Inactive'; // Default to inactive if the status is unknown
							$color = '#ff0000';
							$license_status = 'inactive';
						}
						?>
						
						<label class="control-label col-lg-12" style="text-align:left; font-size: 14px;">
							<?php Wp_Helper::_e('License Status', 'elementor'); ?>:
							<span style="color: <?php echo $color; ?>; font-style: italic;">
								<?php Wp_Helper::_e($status_text, 'elementor'); ?>
							</span>

							<br/>
							
							<?php if (isset($statuses[$license_status]['message'])) : ?>
								<br/>
								<?php if ($license_status === Wp_Helper::STATUS_VALID) : ?>
									<p class="alert alert-success">
									<?php echo Wp_Helper::__($statuses[$license_status]['message'], 'elementor'); ?>
								</p>
								<?php else : ?>
								<p class="alert alert-danger">
									<?php echo Wp_Helper::__($statuses[$license_status]['message'], 'elementor'); ?>
								</p>
								<?php endif; ?>
							<?php endif; ?>

						</label>
								
					</div>

					<?php
					
					if (isset($GLOBALS['mod_theme_partner_data'])) {
						$partners = $GLOBALS['mod_theme_partner_data'];
						foreach ($partners as $partner) {
						?>
						<div class="form-group">
							<div class="col-lg-12" style="text-align:left; font-size: 14px;">
								<?php if ($license_status === Wp_Helper::STATUS_VALID) : ?>
								<?php Wp_Helper::_e('Partners:', 'elementor'); ?>
									<a href="<?php echo \Context::getContext()->link->getAdminLink($partner['licensemanager']); ?>">
										<?php echo $partner['partnername']; ?>
									</a>
								<?php else : ?>
								<?php Wp_Helper::_e('Go to Partner\'s dashboard to activate license:', 'elementor'); ?>
									<a href="<?php echo \Context::getContext()->link->getAdminLink($partner['licensemanager']); ?>">
										<?php Wp_Helper::_e('Click here to activate license.', 'elementor'); ?>
									</a>
								<?php endif; ?>
							</div>	
						</div>
						<?php
						}
					} else {
						?>
						<div class="form-group">
							<div class="col-lg-12" style="text-align:left; font-size: 14px;">
								<?php Wp_Helper::_e('You don\'t have any partner module installed.', 'elementor'); ?>
								<a href="#">
									<?php Wp_Helper::_e('Please install module from our partner.', 'elementor'); ?>
								</a>
							</div>	
						</div>
						<?php
					}
		?>
				</div>
			</div>
		</form>
		<?php
	}

    private function render_manually_activation_widget() {
		$license_key = Wp_Helper::api_get_license_key();
		?>
		<form class="form-horizontal" method="post" action="<?php echo Wp_Helper::get_exit_to_dashboard( $this->name ); ?>">
			<div id="configuration_fieldset_general" class="panel ">
				<div class="panel-heading"><i class="icon-cogs"></i> <?php Wp_Helper::_e( 'License', 'elementor' ); ?></div>
				<div class="form-wrapper">	
					<?php if ( empty( $license_key ) ) : ?>
						<div class="form-group">
							<label class="control-label col-lg-3 required"><?php Wp_Helper::_e( 'Your Email Address', 'elementor' ); ?></label>
							<div class="col-lg-9">
								<input type="text" name="avanam_builder_license_email" value="<?php echo Wp_Helper::esc_attr( Configuration::get('PS_SHOP_EMAIL') ); ?>" style="max-width: 500px;display: inline-block;vertical-align: middle;"/>
							</div>
							<div class="col-lg-9 col-lg-offset-3">
								<br/>
								<?php Wp_Helper::_e( 'The email address to which the license is issued. It will be used for activation and securely stored on our server.', 'elementor' ); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-lg-3 required"><?php Wp_Helper::_e( 'Your License Key', 'elementor' ); ?></label>
							<div class="col-lg-9">
								<input class="regular-text code" name="avanam_builder_license_key" type="text" value="" placeholder="<?php Wp_Helper::esc_attr_e( 'Please enter your license key here', 'elementor' ); ?>" style="max-width: 500px;display: inline-block;vertical-align: middle;"/>
								<input type="submit" class="btn btn-primary" name="submitAvanamActivateLicense" value="<?php Wp_Helper::esc_attr_e( 'Activate', 'elementor' ); ?>" style="display: inline-block;vertical-align: middle;"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-9 col-lg-offset-3">
								<br/>	
								<p class="alert alert-danger" style="max-width: 500px;display: vertical-align: middle;">
									<?php echo Wp_Helper::__('Your license has not been activated.', 'elementor'); ?>
								</p>
							</div>	
						</div>

					<?php else :
						$license_data = Wp_Helper::api_get_license_data( true ); ?>
						
						<div class="form-group">
							<label class="control-label col-lg-3 required"><?php Wp_Helper::_e( 'Your Email Address', 'elementor' ); ?></label>
							<div class="col-lg-9">
								<input type="text" value="<?php echo Wp_Helper::esc_attr( Wp_Helper::api_get_license_email() ); ?>" style="max-width: 500px;display: inline-block;vertical-align: middle;" disabled/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-lg-3 required"><?php Wp_Helper::_e( 'Your License Key', 'elementor' ); ?></label>
							<div class="col-lg-9">
								<input type="text" value="<?php echo Wp_Helper::esc_attr( Wp_Helper::api_get_hidden_license_key() ); ?>" style="max-width: 500px;display: inline-block;vertical-align: middle;" disabled/>
								<input type="submit" class="btn btn-primary" name="submitAvanamDeactivateLicense" value="<?php Wp_Helper::esc_attr_e( 'Deactivate', 'elementor' ); ?>" style="display: inline-block;vertical-align: middle;"/>
							</div>
							<div class="col-lg-9 col-lg-offset-3">
								<br/>
								<?php Wp_Helper::_e( 'Status', 'elementor' ); ?>:
								<?php if ( isset($license_data['license']) && Wp_Helper::STATUS_EXPIRED === $license_data['license'] ) : ?>
									<span style="color: #ff0000; font-style: italic;"><?php Wp_Helper::_e( 'Expired', 'elementor' ); ?></span>
								<?php elseif ( isset($license_data['license']) && Wp_Helper::STATUS_SITE_INACTIVE === $license_data['license'] ) : ?>
									<span style="color: #ff0000; font-style: italic;"><?php Wp_Helper::_e( 'Mismatch', 'elementor' ); ?></span>
								<?php elseif ( isset($license_data['license']) && Wp_Helper::STATUS_INVALID === $license_data['license'] ) : ?>
									<span style="color: #ff0000; font-style: italic;"><?php Wp_Helper::_e( 'Invalid', 'elementor' ); ?></span>
								<?php elseif ( isset($license_data['license']) && Wp_Helper::STATUS_DISABLED === $license_data['license'] ) : ?>
									<span style="color: #ff0000; font-style: italic;"><?php Wp_Helper::_e( 'Disabled', 'elementor' ); ?></span>
								<?php else : ?>
									<span style="color: #008000; font-style: italic;"><?php Wp_Helper::_e( 'Active', 'elementor' ); ?></span>
								<?php endif; ?>
							</div>
							<div class="col-lg-9 col-lg-offset-3">
								<?php if ( isset($license_data['license']) && Wp_Helper::STATUS_EXPIRED === $license_data['license'] ) : ?>
									<br/>
									<p class="alert alert-danger"><?php echo Wp_Helper::__( 'Your License Has Expired. Renew your license today to keep getting feature updates, premium support and unlimited access to the template library.', 'elementor' ); ?></p>
								<?php endif; ?>
								<?php if ( isset($license_data['license']) && Wp_Helper::STATUS_SITE_INACTIVE === $license_data['license'] ) : ?>
									<br/>
									<p class="alert alert-danger"><?php echo Wp_Helper::__( 'Your license key doesn\'t match your current domain. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'elementor' ); ?></p>
								<?php endif; ?>
								<?php if ( isset($license_data['license']) && Wp_Helper::STATUS_INVALID === $license_data['license'] ) : ?>
									<br/>
									<p class="alert alert-danger"><?php echo Wp_Helper::__( 'Your license key doesn\'t match your current domain. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'elementor' ); ?></p>
								<?php endif; ?>
								<?php if ( isset($license_data['license']) && Wp_Helper::STATUS_DISABLED === $license_data['license'] ) : ?>
									<br/>
									<p class="alert alert-danger"><?php echo Wp_Helper::__( 'Your license key has been cancelled (most likely due to a refund request). Please consider acquiring a new license.', 'elementor' ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</form>
		<?php
	}
		
    public function postProcess()
    {
		if (Tools::isSubmit('submitAvanamActivateLicense')) {
			if( !Tools::getValue( 'avanam_builder_license_key' ) ){
				return $this->errors[] = Wp_Helper::__( 'The license key is required. ', 'elementor' );;
			}

			if( !Tools::getValue( 'avanam_builder_license_email' ) ){
				return $this->errors[] = Wp_Helper::__( 'The license email is required. ', 'elementor' );;
			}
			
			$license_key = trim( Tools::getValue( 'avanam_builder_license_key' ) );
			$license_email = trim( Tools::getValue( 'avanam_builder_license_email' ) );
			
			$data = Wp_Helper::api_activate_license( $license_key, $license_email );

			//print_r($data); exit;
			if ( !is_array( $data ) || isset($data['error'])) {
				return $this->errors[] = $data['error'];
			}

			//	If activated
			if ( isset( $data['activated'] ) && $data['activated'] === true ) {

				$data['license'] = Wp_Helper::STATUS_VALID;
			}
			if ( isset($data['error']) && $data['success'] != true ) {
				$error_msg = Wp_Helper::api_get_error_message( $data['error'] );
				return $this->errors[] = $error_msg;
			}

			if ( $data['success'] == true || (isset( $data['activated'] ) && $data['activated'] === true) ) {
				Wp_Helper::api_set_license_key( $license_key );
				Wp_Helper::api_set_license_email( $license_email );			
				Wp_Helper::api_set_license_data( $data );
			}
		}
		if (Tools::isSubmit('submitAvanamDeactivateLicense')) {
			Wp_Helper::api_deactivate();
		}
		if (Tools::isSubmit('submitAvanamMigrationAddress')) {
			if( !Tools::getValue( 'avanam_builder_license_key_migration' ) ){
				$this->errors[] = Wp_Helper::__( 'The license key is required. ', 'elementor' );;
			}

			if( !Tools::getValue( 'avanam_builder_old_url' ) ){
				$this->errors[] = Wp_Helper::__( 'The old url is required. ', 'elementor' );;
			}

            if($this->errors){
                return $this->errors;
            }
			
			$license_key = trim( Tools::getValue( 'avanam_builder_license_key_migration' ) );
			$license_email = trim( Tools::getValue( 'avanam_builder_license_email' ) );
            $url_old = trim( Tools::getValue( 'avanam_builder_old_url' ) );

			Wp_Helper::api_deactivate($license_key,$license_email, $url_old);
			
			$data = Wp_Helper::api_activate_license( $license_key, $license_email );

			if ( !is_array( $data ) ) {
				return $this->errors[] = $data;
			}

			if ( Wp_Helper::STATUS_VALID !== $data['license'] ) {
				$error_msg = Wp_Helper::api_get_error_message( $data['error'] );
				return $this->errors[] = $error_msg;
			}

			Wp_Helper::api_set_license_key( $license_key );
			Wp_Helper::api_set_license_email( $license_email );
			Wp_Helper::api_set_license_data( $data );
		}
    }			
}
