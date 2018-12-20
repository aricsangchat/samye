/**
 * BLOCK: api-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

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
registerBlockType('cgb/grd-home-block', {
	title: __('GRD HOME BLOCK'),
	description: __('Most recent GRD Posts'),
	icon: 'image-filter',
	category: 'common',
	keywords: [__('grd-home-block')],
	edit: withSelect(select => {
		return {
			posts: select('core').getEntityRecords('postType', 'post', {
				per_page: 3
			})
		};
	})(({ posts, className, attributes, setAttributes }) => {
		console.log(attributes);
		if (!posts) {
			return <p className={className}>
				<Spinner />
				{__('Loading Posts')}
			</p>;
		}
		if (0 === posts.length) {
			return <p>{__('No Posts')}</p>;
		}
		return (
			<div className={className}>
				<RichText
					tagName="h2"
					value={attributes.content}
					onChange={content => setAttributes({ content })}
					placeholder={__('Add Title')}
				/>
				<RichText
					tagName="p"
					value={attributes.description}
					onChange={description => setAttributes({ description })}
					placeholder={__('Add Title')}
				/>
				<ul>
					{posts.map(post => {
						return (
							<li>
								<a className={className} href={post.link}>
									{post.title.rendered}
								</a>
							</li>
						);
					})}
				</ul>
			</div>
		);
	} // end withSelect
	), // end edit
	save() {
		return null;
	}
});
