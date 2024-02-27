<?php
/*
* QuantumCloud Promo + Support Page
* Revised On: 06-01-2017
*/


/*******************************
 * Main Class to Display Support
 * form and the promo pages
 *******************************/

if( !class_exists('QcSLidSupportAndPromoPage') ){


	class QcSLidSupportAndPromoPage{
	
		public $plugin_menu_slug = "";
		public $plugin_slug = "sld"; //Should be unique, like: qcsld_p123
		public $promo_page_title = 'More WordPress Goodies for You!';
		public $promo_menu_title = 'Support';
		public $plugin_name = '';
		
		public $page_slug = "";
		
		public $relative_folder_url;
		
		//public $relative_folder_url = plugin_dir_url( __FILE__ );
		
		function __construct( $plugin_slug = null )
		{
			/*
			if(!function_exists('wp_get_current_user')) {
				include(ABSPATH . "wp-includes/pluggable.php"); 
			}
			*/
			
			$this->page_slug = 'qcpro-promo-page-' . $plugin_slug;
			$this->relative_folder_url = plugin_dir_url( __FILE__ );
			
			add_action('admin_enqueue_scripts', array(&$this, 'include_promo_page_scripts'));
			
			//add_action( 'wp_ajax_process_qc_promo_form', array(&$this,'process_qc_promo_form') );
			
		} //End of Constructor
		
		function include_promo_page_scripts( $hook )
		{

            if( isset($_GET["page"]) && !empty($_GET["page"]) && (   $_GET["page"] == "qcpro-promo-page-qcld-slider-hero-pro-support"  ) ){                   
		   
                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'jquery-ui-core');
                wp_enqueue_script( 'jquery-ui-tabs' );
                wp_enqueue_script( 'jquery-custom-form-processor', $this->relative_folder_url . '/js/support-form-script.js',  array('jquery', 'jquery-ui-core','jquery-ui-tabs') );

                wp_enqueue_style( 'pd-support-fontawesome', $this->relative_folder_url . "/css/font-awesome.min.css");
                wp_enqueue_style( 'pd-support-style', $this->relative_folder_url . "/css/style.css");
                wp_enqueue_style( 'pd-support-style-responsive', $this->relative_folder_url . "/css/responsive.css");
                wp_enqueue_style( 'pd-support-style-font', "https://fonts.googleapis.com/css?family=Lato");

            }
		   
		}
		
		function show_promo_page()
		{
		
			if( $this->plugin_menu_slug == "" ){
			   return;
			}
			
			add_action( 'admin_menu', array(&$this, 'show_promo_page_callback_func') );
			
		  
		} //End of function show_promo_page
		
		/*******************************
		 * Callback function to add the menu
		 *******************************/
		function show_promo_page_callback_func()
		{
			add_submenu_page(
				$this->plugin_menu_slug,
				$this->promo_page_title,
				$this->promo_menu_title,
				'manage_options',
				$this->page_slug,
				array(&$this, 'qcpromo_support_page_callback_func' )
			);
		} //show_promo_page_callback_func
		
		/*******************************
		 * Callback function to show the HTML
		 *******************************/
		function qcpromo_support_page_callback_func()
		{
			?>
				
				
				<div class="qc_support_container"><!--qc_support_container-->
    
                <div class="qc_tabcontent clearfix-div">
                    <div class="qc-row">
                        <div class="support-btn-main clearfix-div">
                            
                        
                            <div class="qc-column-12">
                                <h4><?php esc_html_e('All our Pro Version users get Premium, Guaranteed Quick, One on One Priority Support.'); ?></h4>
                                <div class="support-btn">
                                    <a class="premium-support" href="<?php echo esc_url('https://www.quantumcloud.com/ps/'); ?>" target="_blank"><?php esc_html_e('GET PRIORITY SUPPORT'); ?></a>
                                    <a class="premium-support premium-support-width" href="<?php echo esc_url('https://www.quantumcloud.com/resources/kb-sections/slider-hero/'); ?>" target="_blank"><?php esc_html_e('Online KnowledgeBase'); ?></a>
                                </div>

                            </div>
                            <div class="qc-column-12" style="margin-top: 12px;">
                                <div class="support-btn">
                                    
                                    <a class="premium-support premium-support-free" href="<?php echo esc_url('https://www.quantumcloud.com/resources/free-support/'); ?>" target="_blank"><?php esc_html_e('Get Support for Free Version'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="qc-row qc-support-product-column">
                        <div class="qc-support-product-inn">
                            <div class="plugin-title-section">
                                <h3 class="plugin-title plugin-title-custom" ><?php esc_html_e('Check Out Some of Our Other Works that Might Make Your Website Better'); ?></h3>
                                <h3 class="qc-product-type"><?php esc_html_e('Innovative Plugins'); ?></h3>
                            </div>

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/knowledgebase-helpdesk/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/knowledgebase-helpdesk.jpg" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/knowledgebase-helpdesk/'); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('KB & HelpDesk w/ ChatBot'); ?></a></h4>
                                        <p><p><?php esc_html_e('KnowledgeBase HelpDesk is an advanced Knowledgebase plugin with helpdesk'); ?><strong>, </strong><?php esc_html_e('glossary and FAQ features all in one. KnowledgeBase HelpDesk is extremely simple and easy to use.'); ?></p></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/knowledgebase-helpdesk/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/knowledgebase-helpdesk/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->


                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/logo (1).png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank"><?php esc_html_e('WoowBot WooCommerce ChatBot'); ?></a></h4>
                                        <p><?php esc_html_e('WooWBot is a'); ?> <strong><?php esc_html_e('ChatBot for WooCommerce'); ?></strong> <?php esc_html_e('with zero configuration or bot training required. This WooCommerce based Shop Bot that can help'); ?> <strong><?php esc_html_e('Increase your store Sales'); ?></strong> <?php esc_html_e('perceptibly.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/woowbot-woocommerce-chatbot/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-for-wordpress/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/wpboticon-256x256-1.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-for-wordpress/'); ?>" target="_blank"><?php esc_html_e('WPBot – ChatBot for WordPress'); ?></a></h4>
                                        <p><?php esc_html_e('WPBot is a ChatBot for'); ?><strong> <?php esc_html_e('any WordPress website'); ?></strong> <?php esc_html_e('that can improve user engagement, answer questions &amp; help'); ?> <strong><?php esc_html_e('generate more leads'); ?></strong>. <?php esc_html_e('Integrated with'); ?> <strong><?php esc_html_e('Google'); ?></strong><?php esc_html_e('‘s'); ?> <strong><?php esc_html_e('DialogFlow (AI and NLP).'); ?></strong></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/chatbot/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-for-wordpress/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/icon.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Business Directory w/ Maps'); ?></a></h4>
                                        <p><?php esc_html_e('This innovative and powerful, yet'); ?> <strong> <?php esc_html_e('Simple &amp; Multi-purpose Business Directory'); ?></strong>  <?php esc_html_e('WordPress PlugIn allows you to create comprehensive Lists of Businesses with maps and tap to call features.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/phone-directory/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/slider-hero/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/slider-hero-icon-256x256.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/slider-hero/'); ?>" target="_blank"><?php esc_html_e('Slider Hero'); ?></a></h4>
                                        <p><?php esc_html_e('Slider Hero is a unique slider plugin that allows you to create'); ?> <strong> <?php esc_html_e('Cinematic Product Intro Adverts'); ?></strong>  <?php esc_html_e('and'); ?>
                                        <strong> <?php esc_html_e('Hero sliders'); ?></strong> <?php esc_html_e('with great Javascript animation effects.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/slider-hero/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/slider-hero/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/sld-icon-256x256.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Link Directory'); ?></a></h4>
                                        <p><?php esc_html_e('Directory plugin with a unique approach! Simple Link Directory is an advanced WordPress Directory plugin for One Page 
                                        directory and Content Curation solution.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/simple-link-directory/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->


                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/infographic-maker-ilist/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/iList-icon-256x256.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/infographic-maker-ilist/'); ?>" target="_blank"><?php esc_html_e('InfoGraphic Maker – iList'); ?></a></h4>
                                        <p><?php esc_html_e('iList is first of its kind'); ?> <strong> <?php esc_html_e('InfoGraphic maker'); ?></strong> <?php esc_html_e('WordPress plugin to create Infographics and elegant Lists effortlessly to visualize data. It is a must have content creation and content curation tool.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/infographic-and-list-builder-ilist/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/infographic-maker-ilist/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/portfolio-x-plugin/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/portfolio-x-logo-dark-2.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/portfolio-x-plugin/'); ?>" target="_blank"><?php esc_html_e('Portfolio X'); ?></a></h4>
                                        <p><?php esc_html_e('Portfolio X is an advanced, responsive portfolio with streamlined workflow and unique designs and templates to show your works or projects.'); ?> <strong><?php esc_html_e('Portfolio Showcase'); ?></strong> <?php esc_html_e('and'); ?> <strong> <?php esc_html_e('Portfolio Widgets'); ?></strong> <?php esc_html_e('are included.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/portfolio-x/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/portfolio-x-plugin/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/comment-link-remove/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/Comment-Link-Remove-300x300 (1).jpg" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/comment-link-remove/'); ?>" target="_blank"><?php esc_html_e('Comment Tools w/ Sentiment Analysis'); ?></a></h4>
                                        <p> <?php esc_html_e('Comment Tools Pro adds an arsenal of'); ?> <strong> <?php esc_html_e('practical tools'); ?></strong>. <?php esc_html_e('It'); ?> <strong> <?php esc_html_e('reduces spammy'); ?></strong>, <?php esc_html_e('low quality comments and'); ?><strong> <?php esc_html_e('increases user interactivity'); ?> </strong> <?php esc_html_e('and'); ?> <strong> <?php esc_html_e('content value'); ?></strong> <?php esc_html_e('of your blog'); ?>.</p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/comment-link-remove/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/comment-link-remove/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->
                            

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-shop-assistant-jarvis/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/jarvis-icon-256x256.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-shop-assistant-jarvis/'); ?>" target="_blank"><?php esc_html_e('WooCommerce Shop Assistant'); ?></a></h4>
                                        <p> <?php esc_html_e('WooCommerce Shop Assistant'); ?> – <strong><?php esc_html_e('JARVIS'); ?></strong> <?php esc_html_e('shows recent user activities, provides advanced search, floating cart, featured products, store notifications, order notifications – all in one place for easy access by buyer and make quick decisions.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/shop-assistant-for-woocommerce-jarvis/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-shop-assistant-jarvis/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/express-shop/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/express-shop.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/express-shop/'); ?>" target="_blank"><?php esc_html_e('Express Shop'); ?></a></h4>
                                        <p><?php esc_html_e('Express Shop is a WooCommerce addon to show all products in one page. User can add products to cart and go to checkout. 
                                        Filtering and search integrated in single page.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/express-shop/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/express-shop/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/woo-tabbed-icon-256x256.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/'); ?>" target="_blank"><?php esc_html_e('Woo Tabbed Category Products'); ?></a></h4>
                                        <p><?php esc_html_e('WooCommerce plugin that allows you to showcase your products category wise in tabbed format. This is a unique woocommerce plugin that lets dynaimically load your products in tabs based on your product categories .'); ?></p>
                                         <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/woo-tabbed-category-product-listing/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/ichart/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/ilist-chat.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/ichart/'); ?>" target="_blank"><?php esc_html_e('iChart – Charts, Graphs and Data Tables'); ?></a></h4>
                                        <p><strong><?php esc_html_e('Responsive, HTML5'); ?></strong> <?php esc_html_e('Charts, Graphs and'); ?> <strong><?php esc_html_e('Data Tables'); ?></strong> <?php esc_html_e('are now easy to build and add to any WordPress page with just a few clicks.'); ?> <strong><?php esc_html_e('Import/Export'); ?></strong> <?php esc_html_e('Charts,'); ?> <strong> <?php esc_html_e('Links'); ?></strong> <?php esc_html_e('in the Chart Data,'); ?> <strong><?php esc_html_e('ToolTip'); ?></strong> <?php esc_html_e('text, Live Chart'); ?> <strong> <?php esc_html_e('Preview'); ?></strong> <?php esc_html_e('and more!'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/ichart/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/ichart/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-addons/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/chatbot-addons.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-addons/'); ?>" target="_blank"><?php esc_html_e('ChatBot Addons'); ?></a></h4>
                                        <p><?php esc_html_e('Empower'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-for-wordpress/'); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('WPBot'); ?></a> <?php esc_html_e('and'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank" rel="noopener noreferrer"> <?php esc_html_e('WoowBot'); ?></a> – <?php esc_html_e('Extend Capabilities with AddOns! FaceBook messenger, white label and more!'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-addons/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->
                            
                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a target="_blank" href="<?php echo esc_url('https://wordpress.org/plugins/increase-sales/'); ?>"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/icon-256x256.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a target="_blank" href="<?php echo esc_url('https://wordpress.org/plugins/increase-sales/'); ?>"><?php esc_html_e('Increase Sales'); ?></a></h4>
                                        <p><?php esc_html_e('Increase Sales is a new and unique WooCommerce addon that strategically places your cross-sells products inline inside, at top or bottom of the Cart during checkout.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/increase-sales/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/increase-sales/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/seo-help'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/seo-help.jpg" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/seo-help'); ?>" target="_blank"><?php esc_html_e('SEO Help'); ?></a></h4>
                                        <p><?php esc_html_e('SEO Help is a unique WordPress plugin to help you write better Link Bait titles. The included LinkBait title generator will take the WordPress post title as Subject and generate alternative ClickBait titles for you to choose from.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/seo-help/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/seo-help'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/comment-link-remove/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/comment-link-remove.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/comment-link-remove/'); ?>" target="_blank"><?php esc_html_e('Comment Link Remove'); ?></a></h4>
                                        <p><?php esc_html_e('All in one solution to fight comment spammers. Tired of deleting useless spammy comments from your WordPress blog posts? Comment Link Remove WordPress plugin removes author link and any other links from the user comments.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/comment-link-remove/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/comment-link-remove/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/bargain-bot/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/bargaining-chatbot.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/bargain-bot/'); ?>" target="_blank"><?php esc_html_e('Bargain Bot'); ?></a></h4>
                                        <p><?php esc_html_e('Allow shoppers to Make Their Offer Now with a Bargaining Bot. Win more customers with smart price negotiations. Bargain Bot can work with any WooCommerce website in LightBox mode or as an addon for the WoowBot!'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://wordpress.org/plugins/bargain/'); ?>" target="_blank" class="button download-free"><?php esc_html_e('Download Free'); ?></a>
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/bargain-bot/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/directory-addons/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/directory-addons.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/directory-addons/'); ?>" target="_blank"><?php esc_html_e('Directory AddOns'); ?></a></h4>
                                        <p><?php esc_html_e('Empower'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Link Directory'); ?></a> <?php esc_html_e('and'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Business Directory Pro'); ?></a> – <?php esc_html_e('Extend Capabilities with AddOns!'); ?></p>
                                        <div class="buy-download-section">
                                           
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/directory-addons/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/image-tools-for-wordpress/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/image-tools-pro.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/image-tools-for-wordpress/'); ?>" target="_blank"><?php esc_html_e('Image Tools Pro'); ?></a></h4>
                                        <p><?php esc_html_e('Image Tools Pro adds an arsenal of'); ?> <strong><?php esc_html_e('practical tools'); ?></strong> <?php esc_html_e('for your WordPress Images to make your life easier.'); ?></p>
                                        <div class="buy-download-section">
                                           
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/image-tools-for-wordpress/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/live-chat/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/live-chat-wordpress-plugin.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/live-chat/'); ?>" target="_blank"><?php esc_html_e('Live Chat plugin for WordPress'); ?></a></h4>
                                        <p><?php esc_html_e('This feature rich, native Live Chat plugin for WordPress plugin can work with the WPBot or work stand alone. Does not require external server or complex set up.'); ?></p>
                                        <div class="buy-download-section">
                                           
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/live-chat/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/support-ticket-for-knowledgebase/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/support-ticket.jpg" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/support-ticket-for-knowledgebase/'); ?>" target="_blank"><?php esc_html_e('WordPress Support Ticket'); ?></a></h4>
                                        <p><?php esc_html_e('Provide complete helpdesk ticket system on your website. Easy to configure and AJAX based ticket plugin for WordPress.'); ?></p>
                                        <div class="buy-download-section">
                                           
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/support-ticket-for-knowledgebase/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Support Ticket'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/plugins/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/coming-soon-special.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/plugins/'); ?>" target="_blank"><?php esc_html_e('Something Exciting'); ?></a></h4>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                        </div>

                    </div>
                    <!--qc row-->
                    
                    <div class="qc-row qc-support-product-column">
                        <div class="qc-support-product-inn">
                            <div class="plugin-title-section">
                                <h3 class="plugin-title plugin-title-custom" ><?php esc_html_e('Premium Themes that Add Perceptible Value to Your Website.'); ?></h3>
                                <h3 class="qc-product-type"><?php esc_html_e('Creative Themes'); ?></h3>
                            </div>

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/knowledgebase-theme/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/premium-theme-kbx-1.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/knowledgebase-theme/'); ?>" target="_blank"><?php esc_html_e('KnowledgeBase X Theme'); ?></a></h4>
                                        <p><?php esc_html_e('KnowledgeBase HelpDesk is an advanced Knowledgebase plugin with helpdesk'); ?><strong>, </strong><?php esc_html_e('glossary and FAQ features all in one. Make the best out of our'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/knowledgebase-helpdesk/'); ?>" target="_blank" rel="noopener"><?php esc_html_e('KnowledgeBase X'); ?></a> <?php esc_html_e('plugin'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/knowledgebase-theme/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Get Theme'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/knowledgebase-theme/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/premium-theme-woowbot.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/woowbot-theme/'); ?>" target="_blank"><?php esc_html_e('WooCommerce ChatBot Theme'); ?></a></h4>
                                        <p><?php esc_html_e('WoowBot is a'); ?> <strong> <?php esc_html_e('Plug n’ play'); ?></strong> <?php esc_html_e('Shopping Chat Bot that can help'); ?> <strong> <?php esc_html_e('Increase your store Sales'); ?></strong>. <?php esc_html_e('Make the best out of the popular'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank" rel="noopener"><?php esc_html_e('WoowBot plugin'); ?></a> <?php esc_html_e('for WooCommerce.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/woowbot-theme/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Get Theme'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/chatbot-theme/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/premium-theme-chatbot-master.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/chatbot-theme/'); ?>" target="_blank"><?php esc_html_e('WPBot – ChatBot Master Theme'); ?></a></h4>
                                        <p><?php esc_html_e('WPBot is a ChatBot for'); ?><strong> <?php esc_html_e('any WordPress website'); ?></strong> <?php esc_html_e('that can improve user engagement, answer questions &amp; help'); ?> <strong> <?php esc_html_e('generate more leads'); ?></strong>. <?php esc_html_e('Make the best out of the popular'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-for-wordpress/'); ?>" target="_blank" rel="noopener"><?php esc_html_e('WPBot plugin'); ?></a>.</p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/chatbot-theme/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Get Theme'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/simple-business-directory-theme/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/premium-theme-sbd.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/simple-business-directory-theme/'); ?>" target="_blank"><?php esc_html_e('Simple Business Directory Theme'); ?></a></h4>
                                        <p><?php esc_html_e('This innovative and powerful, yet'); ?><strong> <?php esc_html_e('Simple &amp; Multi-purpose Business Directory'); ?></strong> <?php esc_html_e('theme is perfect for our'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>"> <?php esc_html_e('SBD plugin'); ?></a> <?php esc_html_e('to meet all your business directory needs'); ?>.</p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/simple-business-directory-theme/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Get Theme'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/simple-blog/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/premium-theme-simple-blog.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/simple-blog/'); ?>" target="_blank"><?php esc_html_e('Simple Blog Theme'); ?></a></h4>
                                        <p><?php esc_html_e('Crafted carefully to provide the best blogging experiences! One Click Install, Demo Data, Compatible with the'); ?> <strong><?php esc_html_e('Elementor'); ?></strong> <?php esc_html_e('and the'); ?> <strong> <?php esc_html_e('Gutenberg'); ?></strong> <?php esc_html_e('Page Builder!'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/simple-blog/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Get Theme'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/simple-link-directory/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/premium-theme-sld.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/simple-link-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Link Directory Theme'); ?></a></h4>
                                        <p><?php esc_html_e('Simple Link Directory is an advanced WordPress Directory plugin for One Page directory and Content Curation solution. Get the best of the SLD plugin!'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/simple-link-directory/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Get Theme'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/woo-tabbed-theme/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/premium-theme-wootabbed.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/woo-tabbed-theme/'); ?>" target="_blank"><?php esc_html_e('WooTabbed Theme'); ?></a></h4>
                                        <p><?php esc_html_e('Crafted carefully to make the best out of the popular'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/'); ?>" target="_blank" rel="noopener"><?php esc_html_e('WooTabbed'); ?> </a> <?php esc_html_e('for WooCommerce. Get a shopping theme that sells!'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/woo-tabbed-theme/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Get Theme'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/express-shop-theme/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/premium-theme-express-shop.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/express-shop-theme/'); ?>" target="_blank"><?php esc_html_e('Express Shop Theme'); ?></a></h4>
                                        <p><?php esc_html_e('Crafted carefully to make the best out of the popular'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/'); ?>" target="_blank" rel="noopener"><?php esc_html_e('WooTabbed'); ?> </a> <?php esc_html_e('for WooCommerce. Get a shopping theme that sells!'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/express-shop-theme/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Get Theme'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/'); ?>"  target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/premium-theme-coming-soont.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/'); ?>"  target="_blank"><?php esc_html_e('Coming Soon!'); ?></a></h4>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            

                        </div>

                    </div>
                    
                    <div class="qc-row qc-support-product-column">
                        <div class="qc-support-product-inn">
                            <div class="plugin-title-section">
                                <h3 class="plugin-title plugin-title-custom" ><?php esc_html_e('Available on our '); ?> <a href="<?php echo esc_url('https://www.dna88.com/'); ?>" target="_blank"> <?php esc_html_e('dna88.com'); ?> </a> <?php esc_html_e('website'); ?></h3>
                                <h3 class="qc-product-type"><?php esc_html_e('Innovative Plugins'); ?></h3>
                            </div>

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.dna88.com/product/button-menu/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/button-menu.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.dna88.com/product/button-menu/'); ?>" target="_blank"><?php esc_html_e('Button Menu'); ?></a></h4>
                                        <p><?php esc_html_e('Show your WordPress navigation menus anywhere on any page as buttons easily using a shortcode. Supports unlimited sub menu levels with icons, animations and complete control over the colors of the individual icons'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.dna88.com/product/button-menu/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.dna88.com/product/notice-pro/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/notice-pro.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.dna88.com/product/notice-pro/'); ?>" target="_blank"><?php esc_html_e('WordPress Notifications'); ?></a></h4>
                                        <p><?php esc_html_e('Display Sitewide notices elegantly with beautiful action button. The Notice Pro version supports unlimited, concurrent sitewide notices that can be defined to display for specific user roles on specific pages.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.dna88.com/product/notice-pro/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.dna88.com/product/voice-widgets/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/voice-widgets-for-wordPress.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.dna88.com/product/voice-widgets/'); ?>" target="_blank"><?php esc_html_e('Voice Widgets'); ?></a></h4>
                                        <p><?php esc_html_e('Get voice messages with your forms and increase user conversions with Voice widgets. Record voice messages with your WordPress forms – CF7, WPForms, BBPress, Blog Comments, and Woocommerce Product Reviews. Supports standalone voice form.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.dna88.com/product/voice-widgets/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.dna88.com/product/highlight/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/highlight.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.dna88.com/product/highlight/'); ?>" target="_blank"><?php esc_html_e('Highlight Sitewide Notice, Text, Button Menu'); ?></a></h4>
                                        <p><?php esc_html_e('Add a sitewide notice or small message bar to the top or bottom of each page of your website to display notice messages or notification such as sales, notices, coupons and any text messages.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.dna88.com/product/highlight/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->

                            <div class="qc-column-4"><!-- qc-column-4 -->
                                <!-- Feature Box 1 -->
                                <div class="support-block ">
                                    <div class="support-block-img">
                                        <a href="<?php echo esc_url('https://www.dna88.com/product/video-connect/'); ?>" target="_blank"> <img src="<?php echo plugin_dir_url(__FILE__); ?>images/video-connect.png" alt=""></a>
                                    </div>
                                    <div class="support-block-info">
                                        <h4><a href="<?php echo esc_url('https://www.dna88.com/product/video-connect/'); ?>" target="_blank"><?php esc_html_e('Video Connect'); ?></a></h4>
                                        <p><?php esc_html_e('Featured Product videos for Woocommerce, Video widget, Videos with contact form 7. Use videos to explain your products or services and connect with your users. All in one Video solution for WordPress.'); ?></p>
                                        <div class="buy-download-section">
                                            <a href="<?php echo esc_url('https://www.dna88.com/product/video-connect/'); ?>" target="_blank" class="button button-primary get-pro"><?php esc_html_e('Go Pro'); ?></a>
                                        </div>

                                    </div>
                                </div>
                            </div><!--/qc-column-4 -->



                        </div>

                    </div>

                </div>
    
            </div><!--qc_support_container-->
				
			<?php
		} //End of qcpromo_support_page_callback_function
		
		
	
	} //End of the class QcSLDSupportAndPromoPage


} //End of class_exists


/*
* Create Instance, set instance variables and then call appropriate worker.
*/

//Supply Unique Promo Page Slug as the constructor parameter of the class QcSLDSupportAndPromoPage. ex: sld-page-2124a to the constructor

//Please create an unique instance for your use, example: $instance_sldf2

$heroinstance = new QcSLidSupportAndPromoPage('qcld-slider-hero-pro-support');

if( is_admin() )
{
	$heroinstance->plugin_menu_slug = "Slider-Hero"; //Edit Value
	$heroinstance->plugin_name = "Slider Hero"; //Edit Value
	$heroinstance->show_promo_page();
}
