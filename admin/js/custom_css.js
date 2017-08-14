// This code is used for embed CSS editor
( function( global, $ ) {
    var editor,
        syncCSS = function() {
            $( '#asw_css_textarea' ).val( editor.getSession().getValue() );
        },
        loadAce = function() {
            editor = ace.edit( 'asw_css' );
            global.safecss_editor = editor;
            editor.getSession().setUseWrapMode( true );
            editor.setShowPrintMargin( false );
            editor.getSession().setValue( $( '#asw_css_textarea' ).val() );
            editor.getSession().setMode( "ace/mode/css" );
            jQuery.fn.spin&&$( '#custom_css_container' ).spin( false );
            $( '#asw-form' ).submit( syncCSS );
        };
    if ( $.browser.msie&&parseInt( $.browser.version, 10 ) <= 7 ) {
        $( '#custom_css_container' ).hide();
        $( '#asw_css_textarea' ).show();
        return false;
    } else {
        $( global ).load( loadAce );
    }
    global.aceSyncCSS = syncCSS;
} )( this, jQuery );
