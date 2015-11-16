/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.plugins.add( 'section', {
	requires: 'richcombo',
	// jscs:disable maximumLineLength
	// jscs:enable maximumLineLength
	init: function( editor ) {
		if ( editor.blockless )
			return;

		var config = editor.config;

		// Gets the list of tags from the settings.
		var tags = config.format_section.split( ';' );

		// Create style objects for all defined styles.
		var styles = {},
			stylesCount = 0,
			allowedContent = [];
		for ( var i = 0; i < tags.length; i++ ) {
			var tag = tags[ i ];
			var style = new CKEDITOR.style( config[ 'format_' + tag ] );
			if ( !editor.filter.customConfig || editor.filter.check( style ) ) {
				stylesCount++;
				styles[ tag ] = style;
				styles[ tag ]._.enterMode = editor.config.enterMode;
				allowedContent.push( style );
			}
		}

		// Hide entire combo when all formats are rejected.
		if ( stylesCount === 0 )
			return;

		editor.ui.addRichCombo( 'strinsert', {
			label: 'Section',
			title: 'Section',
			toolbar: 'styles,20',
			allowedContent: allowedContent,

			panel: {
				css: [ CKEDITOR.skin.getPath( 'editor' ) ].concat( config.contentsCss ),
				multiSelect: false,
				attributes: { 'aria-label': 'Section' }
			},

			init: function() {
				this.startGroup( 'Define Section' );

				for ( var tag in styles ) {
					var label = tag;

					// Add the tag entry to the panel list.
					this.add( tag, styles[ tag ].buildPreview( label ), label );
				}
			},

			onClick: function( value ) {
				editor.focus();
				editor.fire( 'saveSnapshot' );

				var style = styles[ value ],
					elementPath = editor.elementPath();
				console.log(editor.elementPath());
				editor[ style.checkActive( elementPath, editor ) ? 'removeStyle' : 'applyStyle' ]( style );

				// Save the undo snapshot after all changes are affected. (#4899)
				setTimeout( function() {
					editor.fire( 'saveSnapshot' );
				}, 0 );
			},

			onRender: function() {
				editor.on( 'selectionChange', function( ev ) {
					var currentTag = this.getValue(),
						elementPath = ev.data.path;

					this.refresh();

					for ( var tag in styles ) {
						if ( styles[ tag ].checkActive( elementPath, editor ) ) {
							if ( tag != currentTag )
								this.setValue( tag, editor.lang.format[ 'tag_' + tag ] );
							return;
						}
					}

					// If no styles match, just empty it.
					this.setValue( '' );

				}, this );
			},

			onOpen: function() {
				this.showAll();
				for ( var name in styles ) {
					var style = styles[ name ];

					// Check if that style is enabled in activeFilter.
					if ( !editor.activeFilter.check( style ) )
						this.hideItem( name );

				}
			},

			refresh: function() {
				var elementPath = editor.elementPath();

				if ( !elementPath )
						return;

				// Check if element path contains 'p' element.
				if ( !elementPath.isContextFor( 'p' ) ) {
					this.setState( CKEDITOR.TRISTATE_DISABLED );
					return;
				}

				// Check if there is any available style.
				for ( var name in styles ) {
					if ( editor.activeFilter.check( styles[ name ] ) )
						return;
				}
				this.setState( CKEDITOR.TRISTATE_DISABLED );
			}
		} );
	}
} );

/**
 * A list of semicolon-separated style names (by default: tags) representing
 * the style definition for each entry to be displayed in the Format drop-down list
 * in the toolbar. Each entry must have a corresponding configuration in a
 * setting named `'format_(tagName)'`. For example, the `'p'` entry has its
 * definition taken from [config.format_p](#!/api/CKEDITOR.config-cfg-format_p).
 *
 *		config.format_tags = 'p;h2;h3;pre';
 *
 * @cfg {String} [format_tags='p;h1;h2;h3;h4;h5;h6;pre;address;div']
 * @member CKEDITOR.config
 */
CKEDITOR.config.format_tags = 'div';



/**
 * The style definition to be used to apply the `Normal (DIV)` format.
 *
 *		config.format_div = { element: 'div', attributes: { 'class': 'normalDiv' } };
 *
 * @cfg {Object} [format_div={ element: 'div' }]
 * @member CKEDITOR.config
 */
CKEDITOR.config.format_div = { element: 'div' };

/**
 * The style definition to be used to apply the `Formatted` format.
 *
 *		config.format_pre = { element: 'pre', attributes: { 'class': 'code' } };
 *
 * @cfg {Object} [format_pre={ element: 'pre' }]
 * @member CKEDITOR.config
 */
CKEDITOR.config.format_pre = { element: 'pre' };

CKEDITOR.config.format_Name = { element: 'div', attributes: { 'class': 'name', 'data-parentId': 1 } };
CKEDITOR.config.format_Address = { element: 'div', attributes: { 'class': 'address', 'data-parentId': 1 } };
CKEDITOR.config.format_PhoneNumber = { element: 'div', attributes: { 'class': 'phone', 'data-parentId': 1 } };
CKEDITOR.config.format_Email = { element: 'div', attributes: { 'class': 'email', 'data-parentId': 1 } };
CKEDITOR.config.format_MyProfileWebsite = { element: 'div', attributes: { 'class': 'profile_website', 'data-parentId': 1 } };
CKEDITOR.config.format_MyLinkedInProfile = { element: 'div', attributes: { 'class': 'linkedin', 'data-parentId': 1 } };
CKEDITOR.config.format_References = { element: 'div', attributes: { 'class': 'reference' } };
CKEDITOR.config.format_Objectives = { element: 'div', attributes: { 'class': 'objective' } };
CKEDITOR.config.format_OtherActivities= { element: 'div', attributes: { 'class': 'activitie' } };
CKEDITOR.config.format_WorkExperience= { element: 'div', attributes: { 'class': 'work' } };
CKEDITOR.config.format_Educations= { element: 'div', attributes: { 'class': 'education' } };
CKEDITOR.config.format_Photos= { element: 'div', attributes: { 'class': 'photo' } };
CKEDITOR.config.format_PersonalityTest= { element: 'div', attributes: { 'class': 'personal_test' } };
CKEDITOR.config.format_KeyQuanlifications= { element: 'div', attributes: { 'class': 'key_quanlification' } };