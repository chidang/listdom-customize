/**
 * Customize UI - Admin JavaScript
 * Handles Select2 initialization for admin meta boxes
 */

jQuery(document).ready(function($) {
    // Initialize Select2 for admin category selection
    $('.listdom-category-admin').select2({
        placeholder: 'Select categories...',
        allowClear: true,
        width: '100%',
        theme: 'classic',
        multiple: true,
        tags: true, // Allow creating new categories
        tokenSeparators: [',', ' '], // Allow comma and space separation
        createTag: function(params) {
            // Allow creating new categories if they don't exist
            return {
                id: params.term,
                text: params.term,
                newOption: true
            };
        },
        templateResult: function(data) {
            if (data.newOption) {
                return $('<span><i class="dashicons dashicons-plus-alt"></i> ' + data.text + ' (new)</span>');
            }
            return data.text;
        },
        templateSelection: function(data) {
            if (data.newOption) {
                return data.text + ' (new)';
            }
            return data.text;
        }
    });
    
    // Handle form submission to ensure proper data format
    $('#post').on('submit', function(e) {
        var $categorySelect = $('#listdom_categories');
        var selectedValues = $categorySelect.val();
        
        // If no categories selected, ensure empty array is submitted
        if (!selectedValues || selectedValues.length === 0) {
            $categorySelect.empty();
        }
    });
    
    // Add custom styling for admin interface
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .listdom-categories-container {
                margin: 10px 0;
            }
            
            .listdom-categories-container .select2-container {
                margin-bottom: 10px;
            }
            
            .listdom-categories-container .description {
                font-style: italic;
                color: #666;
                margin-top: 5px;
            }
            
            .select2-container--classic .select2-selection--multiple {
                min-height: 35px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            
            .select2-container--classic .select2-selection--multiple .select2-selection__choice {
                background-color: #0073aa;
                color: white;
                border: none;
                border-radius: 3px;
                padding: 2px 8px;
                margin: 2px;
            }
            
            .select2-container--classic .select2-selection--multiple .select2-selection__choice__remove {
                color: white;
                margin-right: 5px;
                font-weight: bold;
            }
            
            .select2-container--classic .select2-selection--multiple .select2-selection__choice__remove:hover {
                color: #ff6b6b;
            }
        `)
        .appendTo('head');
});
