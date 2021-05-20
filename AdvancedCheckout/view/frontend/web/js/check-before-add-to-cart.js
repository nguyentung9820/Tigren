/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

 define([
    'jquery',
    'mage/url'
],
function ($, url) {
    'use strict';
    return function (widget) {
        $('#product-addtocart-button').on('click', function(e){
            e.preventDefault();
            var product = $("input[name*='product']").val();
            var url = "tigren/ajax/checkattribute";
            var urlajax = "tigren/ajax/clearcart";
            console.log(product);
            $.ajax({
                url: url,
                type: 'post',
                data: {data:product},
                success: function (result) {
                    console.log('done');
                    console.log(result);

                    if (result === true) {
                    
                    var popup = $('<div class="add-to-cart-dialog"/>').html($('.page-title span').text() + '<span> has been added to cart.</span>').modal({ //get product name from product view page only
                        modalClass: 'add-to-cart-popup',
                        buttons: [
                            {
                                text: $.mage.__('Clear Cart'),
                                click: function () {
                                    $.ajax({
                                        url: urlajax,
                                        type: 'POST',
                                        success: function (res) {
                                            if (res.messages) {
                                                $('[data-placeholder="messages"]').html(res.messages);
                                            }

                                            if (res.minicart) {
                                                $('[data-block="minicart"]').replaceWith(res.minicart);
                                                $('[data-block="minicart"]').trigger('contentUpdated');
                                            }
                                        }
                                    });
                                }
                            },
                            {
                                text: 'Continue Shopping',
                                click: function () {
                                    this.closeModal();
                                }
                            },
                            {
                                text: 'Proceed to Checkout',
                                click: function () {
                                    window.location = window.checkout.checkoutUrl
                                }
                            }
                        ]
                    });
                    popup.modal('openModal');
                    
                    }
                },
                error: function () {
                    console.log('fail'); 
                }
            });
        });
        $('.tocart').on('click', function(e){
            e.preventDefault();
            var product = $("input[name*='product']").val();
            var url = "tigren/ajax/checkattribute";
            var urlajax = "tigren/ajax/clearcart";
            console.log(product);
            $.ajax({
                url: url,
                type: 'post',
                data: {data:product},
                success: function (result) {
                    console.log('done');
                    console.log(result);

                    if (result === true) {
                    
                    var popup = $('<div class="add-to-cart-dialog"/>').html($('.page-title span').text() + '<span> has been added to cart.</span>').modal({ //get product name from product view page only
                        modalClass: 'add-to-cart-popup',
                        buttons: [
                            {
                                text: $.mage.__('Clear Cart'),
                                click: function () {
                                    $.ajax({
                                        url: urlajax,
                                        type: 'POST',
                                        success: function (res) {
                                            if (res.messages) {
                                                $('[data-placeholder="messages"]').html(res.messages);
                                            }

                                            if (res.minicart) {
                                                $('[data-block="minicart"]').replaceWith(res.minicart);
                                                $('[data-block="minicart"]').trigger('contentUpdated');
                                            }
                                        }
                                    });
                                }
                            },
                            {
                                text: 'Continue Shopping',
                                click: function () {
                                    this.closeModal();
                                }
                            },
                            {
                                text: 'Proceed to Checkout',
                                click: function () {
                                    window.location = window.checkout.checkoutUrl
                                }
                            }
                        ]
                    });
                    popup.modal('openModal');
                    
                    }
                },
                error: function () {
                    console.log('fail'); 
                }
            });
        });
    }
});