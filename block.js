/**
 * Ninja Forms Form Block
 *
 * A block for embedding a Ninja Forms form into a post/page.
 */
( function( blocks, i18n, element, components ) {

	var el = element.createElement,
      TextControl = blocks.InspectorControls.TextControl,
      SelectControl = blocks.InspectorControls.SelectControl,
      InspectorControls = blocks.InspectorControls,
      Sandbox = components.Sandbox;

	blocks.registerBlockType( 'ninja-forms/form', {
		title: 'Ninja Forms',
		icon: 'feedback',
		category: 'common',

		attributes: {
      formID: {
        type: 'integer',
        default: 0
      },
		},

		edit: function( props ) {

      var focus = props.focus;

      var formID = props.attributes.formID;

      if( ! formID ) formID = 1; // Default.

      function onFormChange( newFormID ) {
				props.setAttributes( { formID: newFormID } );
      }

      var inspectorControls = el( InspectorControls, {},
        el( SelectControl, { label: 'Form ID', value: formID, options: ninjaFormsBlock.forms, onChange: onFormChange } )
      );

			return [
        el( 'div', { className: 'nf-iframe-container' },
          el( 'div', { className: 'nf-iframe-overlay' } ),
          el( 'iframe', { src: '/?nf_preview_form=' + formID + '&nf_iframe', height: '0', width: '500' })
        ),
        !! focus && inspectorControls
      ];
		},

		save: function( props ) {

      var formID = props.attributes.formID;

      if( ! formID ) return '';

			return '[ninja_forms id="' + formID + '"]';
		}
	} );


} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	window.wp.components
);
