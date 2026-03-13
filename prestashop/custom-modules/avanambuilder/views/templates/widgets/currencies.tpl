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

<div class="ava-dropdown-wrapper" id="_desktop_currency_selector">
	<div class="ava-dropdown-toggle" data-toggle="ava-dropdown-widget">
		<span>{$current_currency.sign} {$current_currency.iso_code}</span>
		<span class="icon-angle-down icon-ava"></span>
	</div>
	<div class="ava-dropdown-menu">
		{foreach from=$currencies item=currency}
			<a data-btn-currency="{$currency.id}" href="javascript:void(0)" {if $currency.current} class="selected"{/if}>
				{$currency.iso_code} {$currency.sign}
			</a>
		{/foreach}
	</div>
</div>