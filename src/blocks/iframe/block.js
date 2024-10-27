
import './style.scss';
import './editor.scss';
import Inspector from './inspector';

const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { PanelBody, TextControl, ToggleControl, Placeholder} = wp.components;
const { registerBlockType } = wp.blocks;


registerBlockType( 'dvieweronline/dvieweronline-iframe', {

	title: __( '3DViewerOnline Iframe', 'dvieweronline-iframe' ),
	description: __('Easily insert 3dvieweronline models into the block editor.', 'dvieweronline-iframe'),
	keywords: [ __( 'iframe' ), __( 'external' )],
	category: 'embed',
	icon: 'admin-site-alt',

	supports: {
		anchor: true,
		className: false,
		customClassName: true,
		align: ['full'],
	},

	attributes: {
		iframeSrc: {
			type: 'string',
		},
		iframeWidth: {
			type: 'string',
		},
		iframeHeight: {
			type: 'string',
		},
		allowFullscreen: {
			type: 'boolean',
		},
		useLazyload: {
			type: 'boolean',
		},
		useImportant: {
			type: 'boolean',
		}
	},

	edit: function( props ) {

		const { attributes, setAttributes } = props;
        
		let customClassName = [attributes.className];
		if(attributes.align == 'full') customClassName.push('alignfull');
		customClassName.push('threed_model_iframe1');

        const iframeStyle = {
            width: '100%',
            maxWidth: attributes.iframeWidth || '100%',
            height: attributes.iframeHeight || '500px'
        };
        for(let i in iframeStyle){ 
        
            if(iframeStyle[i].search('%') == '-1'){
                if(iframeStyle[i].search('px') == '-1'){
                    iframeStyle[i] +='px';
                }
            }
        }
        let allow = {};
        if(attributes.allowFullscreen) allow.allowFullscreen = true;

        const block = attributes.iframeSrc
			? <iframe
				id={ attributes.anchor }
				className={ customClassName.join(' ') }
				src={ attributes.iframeSrc }
				style={ iframeStyle }
				frameBorder="0"
				{ ...allow }
				></iframe>
			: '';

        return (
			<Fragment>
				<Inspector { ...props } />
				<Placeholder
                    icon="admin-site-alt"
                    label={ __('3D Viewer Online', 'dvieweronline-iframe') }
                    >
                    <TextControl
                        label={  __('Paste the link of the 3D model you want to display on your site.', 'dvieweronline-iframe') }
                        value={ attributes.iframeSrc }
                        onChange={ ( value ) => { setAttributes( {iframeSrc: value } ) } }
                    />
                    { block }
                </Placeholder>
                
            </Fragment>
		);
	},

	save: function( props ) {

		const { attributes } = props;

		let customClassName = [attributes.className];
		if(attributes.align == 'full') customClassName.push('alignfull');

        const iframeStyle = {
            width: '100%',
            maxWidth: attributes.iframeWidth || '100%',
            height: attributes.iframeHeight || '500px'
        };
        for(let i in iframeStyle){ 
        
            if(iframeStyle[i].search('%') == '-1'){
                if(iframeStyle[i].search('px') == '-1'){
                    iframeStyle[i] +='px';
                }
            }
        }
        if(attributes.useImportant){
        	for(let i in iframeStyle){ iframeStyle[i] += ' !important'; }
		}

        let allow = {};
        if( attributes.allowFullscreen ) allow.allowFullscreen = true;

        return (
            <Fragment>
                <iframe
                    style={ iframeStyle }
					id={ attributes.anchor }
                    src={ attributes.iframeSrc }
					class={ customClassName.join(' ') }
					loading={ attributes.useLazyload ? 'lazy' : false}
                    frameBorder="0"
                    { ...allow }
                ></iframe>
            </Fragment>
        );
	},

} );
