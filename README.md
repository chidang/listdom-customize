# Listdom Customize Plugin

This plugin customizes the Listdom plugin to use proper WordPress taxonomy relationships instead of custom meta fields for categories.

## Changes Made

### Removed Meta Field Usage
- **Before**: Used `_listdom_categories` meta field to store category IDs
- **After**: Uses proper WordPress taxonomy relationship between `listdom-listing` post type and `listdom-category` taxonomy

### Updated Functions

1. **`get_listdom_categories()`** - Now uses `wp_get_object_terms()` instead of `get_post_meta()`
2. **`get_listdom_category_names()`** - Now uses `wp_get_object_terms()` with 'names' field
3. **`save_listdom_categories()`** - Now only uses `wp_set_object_terms()` instead of storing meta
4. **`render_listdom_category_meta_box()`** - Now reads existing categories using taxonomy

### Benefits

- **Better Performance**: WordPress taxonomy queries are optimized
- **Standard WordPress Practice**: Uses built-in taxonomy functionality
- **Better Integration**: Works seamlessly with WordPress core features
- **Cleaner Data**: No duplicate data storage

## Migration

The plugin includes a migration script that will:

1. Find all posts with `_listdom_categories` meta
2. Migrate the data to proper taxonomy relationships
3. Clean up the old meta fields

### How to Run Migration

1. Go to **Listdom Listings > Migrate Categories** in your WordPress admin
2. Click "Run Migration" to start the process
3. The script will show you the results of the migration

### After Migration

Once the migration is complete, you can:

1. Delete the `migrate-categories.php` file (optional)
2. Remove the migration include line from `listdom-customize.php`

## Usage

The helper functions work the same way as before:

```php
// Get category IDs
$category_ids = get_listdom_categories($post_id);

// Get category names
$category_names = get_listdom_category_names($post_id);

// Display categories
echo display_listdom_categories($post_id, ', ');
```

## Admin Interface

The admin interface remains the same - you can still select multiple categories using the Select2 dropdown in the meta box on listdom-listing posts.

## Compatibility

This change maintains backward compatibility with existing code that uses the helper functions, but now uses the proper WordPress taxonomy system under the hood.
