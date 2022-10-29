/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

const $ = require('jquery');
const bootstrap = require('bootstrap');
var searchRequest = null;
var minlength = 3;

$(function() {
    $(document).on("click", ".has-confirmation", function(e) {
        e.preventDefault();
        var confirmation = new bootstrap.Modal(document.getElementById('confirmation-popup'))
        confirmation.show();
        var link = $(this).attr('href');
        $('.confirm-action').attr('href', link);
    });

    $(document).on("click", ".form-validate button", function(e) {
        e.preventDefault();
        var confirmation = new bootstrap.Modal(document.getElementById('confirmation-popup'))
        confirmation.show();
        $('.confirm-action').attr('href', '');
        $('.confirm-action').addClass('confirm-action-form');
        
    });
    $(document).on("click", ".confirm-action-form", function(e) {
        e.preventDefault();
        $('.form-validate').trigger('submit');
        $(this).removeClass('confirm-action-form');        

    });

    

    $(document).on("keyup", "#searchText", function(e) {
        var that = this,
        value = $(this).val();
        var type = $('#search_type').val();
        if (value.length >= minlength ) {
            
            if (searchRequest != null) 
                searchRequest.abort();
            searchRequest = $.ajax({
                type: "GET",
                url: "/admin/ajax-search-by-name/"+type+"/"+value,                
                dataType: "json",
                success: function(msg){
                    if (value == $(that).val()) {
                        if(msg.success === true){
                            $('#has-search tbody').html(msg.data);
                        }else {
                            alert(msg.message)
                        }                        
                    }
                }
            });
        }
        if (value.length === 0 && searchRequest != null) {
            searchRequest = $.ajax({
                type: "GET",
                url: "/admin/ajax-search-by-name-all/"+type,                
                dataType: "json",
                success: function(msg){
                    if(msg.success === true){
                        $('#has-search tbody').html(msg.data);
                    }else {
                        alert(msg.message)
                    }           
                }
            });
        }
    });

    $(document).on("change", "#status_filter", function(e) {
        var that = this,
        value = $(this).val();
        var type = $('#search_type').val();
            
            if (searchRequest != null) 
                searchRequest.abort();
            searchRequest = $.ajax({
                type: "GET",
                url: "/admin/ajax-filter-by-status/"+type+"/"+value,                
                dataType: "json",
                success: function(msg){
                    if (value == $(that).val()) {
                        if(msg.success === true){
                            $('#has-search tbody').html(msg.data);
                        }else {
                            alert(msg.message)
                        }                        
                    }
                }
            });
    });

});
