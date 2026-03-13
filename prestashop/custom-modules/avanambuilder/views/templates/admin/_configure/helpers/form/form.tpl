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

{extends file="helpers/form/form.tpl"}

{block name="input_row"}
    {if $input.type == 'page_trigger'}
        <div class="form-group">
            <label class="control-label col-lg-3"></label>
            <div class="col-lg-9">
                {if $input.url}
                    <a href="{$input.url|escape:'html':'UTF-8'}" class="btn btn-info ava-btn-edit"><i class="icon-external-link"></i> {l s='Edit with AvanamBuilder' mod='avanambuilder'}</a>
                {else}
                    <div class="alert alert-info">&nbsp;{l s='Save page first to enable AvanamBuilder' mod='avanambuilder'}</div>
                {/if}
            </div>
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
