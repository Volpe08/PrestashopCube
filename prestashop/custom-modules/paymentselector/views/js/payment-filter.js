/**
 * Filtre les options de paiement selon le montant du panier
 */
(function() {
    'use strict';

    console.log('Payment Selector: Script chargé');

    // Vérifier que les variables sont définies
    if (typeof paymentSelectorUseSumup === 'undefined') {
        console.warn('Payment Selector: Variables non définies');
        return;
    }

    console.log('Threshold:', paymentSelectorThreshold);
    console.log('Total:', paymentSelectorTotal);
    console.log('Use Sumup:', paymentSelectorUseSumup);

    // Fonction pour masquer/afficher les modules de paiement
    function filterPaymentMethods() {
        console.log('Payment Selector: Filtrage des méthodes de paiement');

        // Fonction pour masquer un élément
        function hideElement(element) {
            if (!element) return;
            console.log('Masquage de:', element);
            element.style.display = 'none';
            element.style.visibility = 'hidden';
            element.style.opacity = '0';
            element.style.height = '0';
            element.style.overflow = 'hidden';
        }

        // Fonction pour afficher un élément
        function showElement(element) {
            if (!element) return;
            console.log('Affichage de:', element);
            element.style.display = '';
            element.style.visibility = '';
            element.style.opacity = '';
            element.style.height = '';
            element.style.overflow = '';
        }

        // STRIPE: Masquer le form + les 2 divs suivantes
        function hideStripePayment() {
            console.log('>>> Masquage de Stripe');

            // 1. Trouver et masquer le form Stripe
            const stripeForm = document.querySelector('#js-stripe-payment-form');
            if (stripeForm) {
                console.log('✓ Form Stripe trouvé');
                hideElement(stripeForm);

                // Masquer les 2 divs suivantes
                let nextElement = stripeForm.nextElementSibling;
                let count = 0;

                while (nextElement && count < 2) {
                    if (nextElement.tagName === 'DIV') {
                        console.log('  → Masquage sibling ' + (count + 1), nextElement);
                        hideElement(nextElement);
                        count++;
                    }
                    nextElement = nextElement.nextElementSibling;
                }
            } else {
                console.warn('⚠ Form Stripe non trouvé (#js-stripe-payment-form)');
            }

            // 2. Masquer aussi le radio button et label Stripe si présents
            const stripeRadio = document.querySelector('input[data-module-name*="stripe"]');
            if (stripeRadio) {
                // Trouver le conteneur payment-option
                const paymentOptionContainer = stripeRadio.closest('.payment-option');
                if (paymentOptionContainer) {
                    console.log('✓ Conteneur Stripe payment-option trouvé');
                    // Masquer le conteneur parent (la div qui contient payment-option)
                    const parentDiv = paymentOptionContainer.parentElement;
                    if (parentDiv) {
                        hideElement(parentDiv);
                    }
                }
            }
        }

        // SUMUP: Masquer via data-module-name
        function hideSumupPayment() {
            console.log('>>> Masquage de SumUp');

            // Trouver l'input radio SumUp via data-module-name
            const sumupRadio = document.querySelector('input[data-module-name="sumuppaymentgateway"]');

            if (sumupRadio) {
                console.log('✓ Input SumUp trouvé:', sumupRadio);

                // Trouver le conteneur payment-option
                const paymentOptionContainer = sumupRadio.closest('.payment-option');

                if (paymentOptionContainer) {
                    console.log('✓ Conteneur SumUp payment-option trouvé:', paymentOptionContainer);

                    // Masquer la div parente qui contient le payment-option
                    const parentDiv = paymentOptionContainer.parentElement;
                    if (parentDiv) {
                        console.log('✓ Masquage de la div parente SumUp');
                        hideElement(parentDiv);
                    } else {
                        // Si pas de parent, masquer directement le payment-option
                        hideElement(paymentOptionContainer);
                    }
                } else {
                    console.warn('⚠ Conteneur payment-option SumUp non trouvé');
                }
            } else {
                console.warn('⚠ Input SumUp non trouvé (data-module-name="sumuppaymentgateway")');
            }
        }

        // STRIPE: Afficher
        function showStripePayment() {
            console.log('>>> Affichage de Stripe');

            const stripeForm = document.querySelector('#js-stripe-payment-form');
            if (stripeForm) {
                showElement(stripeForm);

                let nextElement = stripeForm.nextElementSibling;
                let count = 0;

                while (nextElement && count < 2) {
                    if (nextElement.tagName === 'DIV') {
                        showElement(nextElement);
                        count++;
                    }
                    nextElement = nextElement.nextElementSibling;
                }
            }

            const stripeRadio = document.querySelector('input[data-module-name*="stripe"]');
            if (stripeRadio) {
                const paymentOptionContainer = stripeRadio.closest('.payment-option');
                if (paymentOptionContainer) {
                    const parentDiv = paymentOptionContainer.parentElement;
                    if (parentDiv) {
                        showElement(parentDiv);
                    }
                }
            }
        }

        // SUMUP: Afficher
        function showSumupPayment() {
            console.log('>>> Affichage de SumUp');

            const sumupRadio = document.querySelector('input[data-module-name="sumuppaymentgateway"]');

            if (sumupRadio) {
                const paymentOptionContainer = sumupRadio.closest('.payment-option');

                if (paymentOptionContainer) {
                    const parentDiv = paymentOptionContainer.parentElement;
                    if (parentDiv) {
                        showElement(parentDiv);
                    } else {
                        showElement(paymentOptionContainer);
                    }
                }
            }
        }

        // Appliquer la logique selon le montant
        if (paymentSelectorUseSumup) {
            // Panier < seuil : afficher SumUp, masquer Stripe
            console.log('💰 Panier < seuil (' + paymentSelectorTotal + ' < ' + paymentSelectorThreshold + ')');
            hideStripePayment();
            showSumupPayment();
        } else {
            // Panier >= seuil : afficher Stripe, masquer SumUp
            console.log('💰 Panier >= seuil (' + paymentSelectorTotal + ' >= ' + paymentSelectorThreshold + ')');
            showStripePayment();
            hideSumupPayment();
        }
    }

    // Fonction de debug
    function debugPaymentElements() {
        console.log('=== DEBUG: Éléments de paiement ===');

        console.log('Stripe:');
        const stripeForm = document.querySelector('#js-stripe-payment-form');
        console.log('  Form:', stripeForm);
        const stripeRadio = document.querySelector('input[data-module-name*="stripe"]');
        console.log('  Radio:', stripeRadio);

        console.log('SumUp:');
        const sumupRadio = document.querySelector('input[data-module-name="sumuppaymentgateway"]');
        console.log('  Radio:', sumupRadio);
        if (sumupRadio) {
            console.log('  payment-option:', sumupRadio.closest('.payment-option'));
            console.log('  Parent div:', sumupRadio.closest('.payment-option')?.parentElement);
        }

        console.log('Tous les payment-option:', document.querySelectorAll('.payment-option'));
    }

    // Initialisation
    function init() {
        console.log('Payment Selector: Initialisation');

        // Debug initial
        debugPaymentElements();

        // Premier filtrage
        filterPaymentMethods();

        // Observer les changements DOM
        const paymentContainers = [
            '#payment-options',
            '.payment-options',
            '#checkout-payment-step',
            '.js-payment-option-form'
        ];

        paymentContainers.forEach(selector => {
            const container = document.querySelector(selector);
            if (container) {
                console.log('✓ Observer attaché à:', selector);

                const observer = new MutationObserver(function(mutations) {
                    const hasAddedNodes = mutations.some(m => m.addedNodes.length > 0);
                    if (hasAddedNodes) {
                        console.log('DOM modifié, re-filtrage...');
                        setTimeout(filterPaymentMethods, 100);
                    }
                });

                observer.observe(container, {
                    childList: true,
                    subtree: true
                });
            }
        });
    }

    // Attendre que le DOM soit prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Écouter les événements PrestaShop
    document.addEventListener('prestashop.checkout.step.change', function() {
        console.log('Étape de checkout changée');
        setTimeout(filterPaymentMethods, 300);
    });

    // Exposer pour debug manuel
    window.debugPaymentSelector = function() {
        console.log('=== DEBUG MANUEL ===');
        debugPaymentElements();
        filterPaymentMethods();
    };

})();
