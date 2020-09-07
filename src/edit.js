/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 *
 * Utility to make WordPress REST API requests. It’s a wrapper around window.fetch.
 * @see https://developer.wordpress.org/block-editor/packages/packages-api-fetch/
 */

import apiFetch from "@wordpress/api-fetch";

/**
 * SelectControl allow users to select from a single-option menu. It functions as a wrapper around the browser’s native <select> element.
 * @see https://developer.wordpress.org/block-editor/components/select-control/
 */

import { SelectControl } from "@wordpress/components";
import ServerSideRender from "@wordpress/server-side-render";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object} [props]           Properties passed from the editor.
 * @param {string} [props.className] Class name generated for the block.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ className, setAttributes, attributes }) {
	const { selectedMenu, menus } = attributes;

	if (menus.length <= 1) {
		apiFetch({ path: "/wcw/v1/menus" }).then((menus) => {
			setAttributes({
				menus: menus.map((menu) => {
					return { value: menu.term_id, label: menu.name };
				}),
			});
		});
	}

	return (
		<React.Fragment>
			<SelectControl
				label={__("Select a menu to display:")}
				options={[
					...[{ value: null, label: "Select a Menu", disabled: true }],
					...menus,
				]}
				value={selectedMenu}
				onChange={(selectedMenu) => {
					setAttributes({ selectedMenu: +selectedMenu });
				}}
			/>
			<ServerSideRender
				block="wcw/contributor-menu"
				attributes={{
					selectedMenu,
					menus,
				}}
			/>
		</React.Fragment>
	);
}
