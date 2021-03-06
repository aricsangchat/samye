/**
 * BLOCK: api-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';
import { DropdownMenu } from '@wordpress/components';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { RichText } = wp.editor;
const { Spinner } = wp.components;
const { withSelect } = wp.data;


/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType('cgb/featured-post-block', {
	title: __('Featured Post Block'),
	description: __('Feature posts'),
	icon: 'image-filter',
	category: 'common',
	keywords: [__('featured-post-block')],
	edit: withSelect(select => {
		console.log(select);
		return {
			posts: select('core').getEntityRecords('postType', 'grd-teaching', {
				per_page: 3
			})
		};
	})(({ posts, className, attributes, setAttributes }) => {
		attributes.className = 'haha';
		//attributes.description = 'joke';
		console.log(attributes);
		// if (!posts) {
		// 	return <p className={className}>
		// 		<Spinner />
		// 		{__('Loading Posts')}
		// 	</p>;
		// }
		// if (0 === posts.length) {
		// 	return <p>{__('No Posts')}</p>;
		// }
		const onChangeDescription = ( value ) => {
			setAttributes( { description: value } );
		};

		const onChangeDropDown = ( value ) => {
			setAttributes( { dropdown: value } );
		};
		return (
			<div className={className}>
				<DropdownMenu
					icon="move"
					label="Select a direction"
					controls={ [
						{
							title: 'Up',
							icon: 'arrow-up-alt',
							onClick: () => onChangeDropDown('up')
						},
						{
							title: 'Right',
							icon: 'arrow-right-alt',
							onClick: () => onChangeDropDown('down')
						}
					] }
				/>
				<RichText
					tagName="h2"
					value={attributes.content}
					onChange={content => setAttributes({ content })}
					placeholder={__('Add Title')}
				/>
				<RichText
					tagName="p"
					value={attributes.description}
					onChange={ onChangeDescription }
					placeholder={__('Add Description')}
				/>
			</div>
		);
	} // end withSelect
	), // end edit
	save() {
		return null;
	}
});

