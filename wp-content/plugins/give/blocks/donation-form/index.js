/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

/**
 * Internal dependencies
 */
import './style.scss';
import GiveLogo from '../components/logo';
import blockAttributes from './data/attributes';
import GiveForm from './edit/block';

/**
 * Register Block
*/

export default registerBlockType( 'give/donation-form', {

	title: __( 'Give Form' ),
	description: __( 'The Give Donation Form block insert an existing donation form into the page. Each form\'s presentation can be customized below.' ),
	category: 'give',
	icon: <GiveLogo color="grey" />,
	keywords: [
		__( 'donation' ),
	],
	supports: {
		html: false,
	},
	attributes: blockAttributes,
	edit: GiveForm,

	save: () => {
		// Server side rendering via shortcode
		return null;
	},
} );
