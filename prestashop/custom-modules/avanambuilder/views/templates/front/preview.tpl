{**
 * AvanamBuilder - Website Builder
 *
 * NOTICE OF LICENSE
 *
 * @author    avanam.org
 * @copyright avanam.org
 * @license   You can not resell or redistribute this software.
 *
 * https://www.gnu.org/licenses/gpl-3.0.html
 **}

{extends file='layouts/layout-both-columns.tpl'}

{block name='breadcrumb'}{/block}

{block name='block_full_width'}
	<div id="content-wrapper">
		<div id="main-content">
			{hook h="displayContentWrapperTop"}
			{block name="content"}
				<div id="elementor" class="elementor"></div>
			{/block}
			{hook h="displayContentWrapperBottom"}
		</div>
	</div>
{/block}
