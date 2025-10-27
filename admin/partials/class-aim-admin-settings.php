<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>

<!-- Settings form Start -->
<div class="wrap">
    <h1><?php echo __('Algolia Index Manager', 'algolia-index-manager'); ?></h1>
    <p>
        <?php echo __(
            'Configure your Algolia account credentials. You can find them in the <a href="https://dashboard.algolia.com/account/api-keys/all" target="_blank" rel="nofollow noopener noreferrer">API Keys</a> section of your Algolia dashboard.',
            'algolia-index-manager'
        ); ?>
    </p>
    <p>
        <?php echo __(
            'Once you provide your Algolia Application ID and API key, this plugin will be able to securely communicate with Algolia servers. <br /> We ensure your information is correct by testing them against the Algolia servers upon save.',
            'algolia-index-manager'
        ); ?>
    </p>
    <p>
        <?php echo __( 'You can add your Algolia credentials here or in the <code>wp-config.php</code> file. ', 'algolia-index-manager' ); ?>
    </p>
    <form method="post" autocomplete="off">
        <?php wp_nonce_field('algolia_settings', '_algolia_settings_nonce'); ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="application_id">
                            <?php echo esc_html__('Application ID', 'algolia-index-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" id="application_id" name="application_id" value="<?php echo esc_attr( $credentials->application_id->value ); ?>" class="regular-text" autocomplete="off" <?php echo esc_attr( $credentials->application_id->disabled ? 'disabled' : '' ); ?> />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="search_api_key">
                            <?php echo esc_html__('Search API Key', 'algolia-index-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" id="search_api_key" name="search_api_key" value="<?php echo esc_attr( $credentials->search_api_key->value ); ?>" class="regular-text" autocomplete="off" <?php echo esc_attr( $credentials->search_api_key->disabled ? 'disabled' : '' ); ?> />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="write_api_key">
                            <?php echo esc_html__('Write API Key', 'algolia-index-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="password" id="write_api_key" name="write_api_key" value="<?php echo esc_attr( $credentials->write_api_key->value ); ?>" class="regular-text" autocomplete="off" <?php echo esc_attr( $credentials->write_api_key->disabled ? 'disabled' : '' ); ?> />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="indice_prefix">
                            <?php echo esc_html__('Indice Prefix', 'algolia-index-manager'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" id="indice_prefix" name="indice_prefix" value="<?php echo esc_attr( $credentials->indice_prefix->value ); ?>" class="regular-text" autocomplete="off" <?php echo esc_attr( $credentials->indice_prefix->disabled ? 'disabled' : '' ); ?> />
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
        </p>
    </form>
</div>
<!-- Settings form End -->

