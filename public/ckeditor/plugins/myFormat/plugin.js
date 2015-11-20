var pluginName = 'myFormat';

    CKEDITOR.plugins.add( pluginName, {
        icons: pluginName, // If you wish to have an icon...
        init: function( editor ) {
            // Tagname which you'd like to apply.
            var tag = 'h2',
                // Note: that we're reusing.
                //style = new CKEDITOR.style( editor.config[ 'format_' + tag ] );
                style = new CKEDITOR.style( { element: 'pre' } );

            // Creates a command for our plugin, here command will apply style. All the logic is
            // inside CKEDITOR.styleCommand#exec function so we don't need to implement anything.
            editor.addCommand( pluginName, new CKEDITOR.styleCommand( style ) );

            // This part will provide toolbar button highlighting in editor.
            editor.attachStyleStateChange( style, function( state ) {
                if ( this._.state == state )
                    return;
                
                var el = this.document.getById( 'cke_' + this.id );
                el.setState( state, 'cke_combo' );

                state == CKEDITOR.TRISTATE_DISABLED ?
                    el.setAttribute( 'aria-disabled', true ) :
                    el.removeAttribute( 'aria-disabled' );

                //!editor.readOnly && editor.getCommand( pluginName ).setState( state );
            } );

            // This will add button to the toolbar.
            editor.ui.addButton( 'MyFormat', {
                label: 'Click to apply format',
                command: pluginName,
                toolbar: 'insert,5'
            } );
        }
    });