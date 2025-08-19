/**
 * Customize UI - Frontend JavaScript
 * Handles category selection changes and search button triggering
 */

jQuery(document).ready(function($) {
    // Handle category selection change to trigger search
    $(document).on('change', '.listdom-category', function() {
        // Find and trigger the search button
        var $searchButton = $(this).closest('.lsd-search-form').find('.lsd-search-button');
        
        if ($searchButton.length > 0) {
            // Add a small delay to ensure the change is processed
            setTimeout(function() {
                $searchButton.trigger('click');
            }, 100);
        }
    });
    
    // Also handle Select2 change events if Select2 is used
    if ($.fn.select2) {
        $(document).on('select2:select select2:unselect', '.listdom-category', function() {
            // Find and trigger the search button
            var $searchButton = $(this).closest('.lsd-search-form').find('.lsd-search-button');
            
            if ($searchButton.length > 0) {
                // Add a small delay to ensure the change is processed
                setTimeout(function() {
                    $searchButton.trigger('click');
                }, 100);
            }
        });
    }
    
    // Handle dynamic content (elements added after page load)
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).hasClass('listdom-category') || $(e.target).find('.listdom-category').length) {
            // Re-bind events for newly added category selectors
            $(e.target).find('.listdom-category').off('change').on('change', function() {
                var $searchButton = $(this).closest('.lsd-search-form').find('.lsd-search-button');
                
                if ($searchButton.length > 0) {
                    setTimeout(function() {
                        $searchButton.trigger('click');
                    }, 100);
                }
            });
        }
    });
});
