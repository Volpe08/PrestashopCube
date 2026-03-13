{if isset($parsed_footer)}
    {hook h="displayFooter" mod="blockwishlist"}
    {hook h='displayFooterPageBuilder'}

    {* Wishlist Model 
    <div class="modal ava-modal ava-modal-wishlist" role="dialog"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>×</span></button><h4 class="modal-title text-xs-center"></h4></div></div></div></div>

    Compare Model 
    <div class="modal ava-modal ava-modal-compare" role="dialog"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>×</span></button><h4 class="modal-title text-xs-center"></h4></div></div></div></div> *}

{else}
    {if file_exists("$theme_dir/_partials/footer.tpl")}
        {include file="$theme_dir/_partials/footer.tpl"}
    {else} 
        {include file="$parent_theme_dir/_partials/footer.tpl"}
    {/if}
{/if}
