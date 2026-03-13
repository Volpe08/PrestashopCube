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

<div class="ava-dropdown-wrapper" id="_desktop_language_selector">
	<div class="ava-dropdown-toggle" data-toggle="ava-dropdown-widget">
		<img src="{Context::getContext()->link->getMediaLink(_THEME_LANG_DIR_)}{$current_language.id_lang}.jpg" alt="{$current_language.name_simple}" width="16" height="11"/>
		<span class="ava-dropdown-toggle-text">{$current_language.name_simple}</span>
		<span class="icon-angle-down icon-ava"></span>
	</div>
	<div class="ava-dropdown-menu">
		{foreach from=$languages item=language}
			<a data-btn-lang="{$language.id_lang}" href="javascript:void(0)"{if $language.id_lang == $current_language.id_lang} class="selected"{/if}>
				<img src="{Context::getContext()->link->getMediaLink(_THEME_LANG_DIR_)}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="16" height="11"/>
				{$language.name_simple}
			</a>
		{/foreach}
	</div>
</div>