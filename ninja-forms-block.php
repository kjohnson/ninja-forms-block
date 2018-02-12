<?php

/*
 * Plugin Name: Ninja Forms - Block
 * Version: 3.0.0-dev
 */

add_action( 'wp_head', function(){

  if( isset( $_GET[ 'nf_preview_form' ] ) && isset( $_GET[ 'nf_iframe' ] ) ){
    $form_id = intval( $_GET[ 'nf_preview_form' ] );
    ?>
    <style media="screen">
      #wpadminbar {
        display: none;
      }
      #nf-form-<?php echo $form_id; ?>-cont {
        z-index: 9001;
        position: fixed;
        top: 0; left: 0;
        width: 100vw;
        height: 100vh;
        background-color: white;
        /* overflow-x: hidden; */
      }
    </style>
    <script type="text/javascript">
    jQuery(document).on( 'nfFormReady', function(){
      var frameEl = window.frameElement;

      var $form = jQuery("#nf-form-<?php echo $form_id; ?>-cont");
      console.log( $form.find( '.ninja-forms-form-wrap' ) );
      var height = $form.find( '.ninja-forms-form-wrap' ).outerHeight(true);

      console.log( height );

      if (frameEl) {
        frameEl.height = height;
      }
    });
    </script>
    <?php
  }

});

add_action( 'init', function(){
  if( ! isset( $_GET[ 'form_preview_iframe' ] ) ) return;

  ?>
  <style media="screen">
    .iframe-container {
      position: relative;
    }
    .iframe-overlay {
      position: absolute;
      top: 0; right: 0; bottom: 0; left: 0;
    }
  </style>
  <div class="iframe-container">
     <div class="iframe-overlay"></div>
     <iframe
          id="idIframe"
          src="/?nf_preview_form=1&nf_iframe"
          frameborder="0"
          width="100%"
          onload="iframeLoaded()"
          ></iframe>
  </div>
  <script type="text/javascript">
  function iframeLoaded() {
    var iFrameID = document.getElementById('idIframe');
    if(iFrameID) {
      var target = iFrameID.contentWindow.document.getElementById('nf-form-1-cont');
      // console.log( target.scrollHeight );
      // here you can make the height, I delete it first, then I make it again
      iFrameID.height = "";
      iFrameID.height = target.scrollHeight;
    }
  }
  </script>
  <?php
  exit();
});

add_action( 'init', 'form_blocks_register_plugin_scripts_styles' );
function form_blocks_register_plugin_scripts_styles() {
    wp_register_script(
        'ninja-forms-block',
        plugins_url( 'block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-i18n', 'wp-element', 'underscore' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
    );
    wp_register_style(
        'ninja-forms-block-style',
        plugins_url( 'style.css', __FILE__ ),
        array( 'wp-edit-blocks' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'style.css' )
    );
    wp_register_style(
        'ninja-forms-block-editor',
        plugins_url( 'editor.css', __FILE__ ),
        array( 'wp-edit-blocks', 'form-blocks-style' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' )
    );
}

add_action( 'enqueue_block_editor_assets', 'form_blocks_enqueue_block_editor_assets' );
function form_blocks_enqueue_block_editor_assets() {
    wp_enqueue_script( 'ninja-forms-block' );

    $forms = [];
    foreach( Ninja_Forms()->form()->get_forms() as $form ){
      $forms[] = [
        'value' => $form->get_id(),
        'label' => $form->get_setting( 'title' ),
      ];
    }
    wp_localize_script( 'ninja-forms-block', 'ninjaFormsBlock', array(
        'forms' => $forms,
    ) );
    wp_enqueue_style( 'ninja-forms-block-style' );
    wp_enqueue_style( 'ninja-forms-block-editor' );
}
