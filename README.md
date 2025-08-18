# Customize UI - Select2 for Categories

A WordPress plugin that automatically applies Select2 to elements with the class "listdom-category" in the frontend.

## Features

- Automatically loads Select2 library from CDN
- Applies Select2 to all elements with class "listdom-category"
- Handles dynamic content (elements added after page load)
- Responsive design with 100% width
- Clear button functionality
- Customizable placeholder text
- **Custom CSS styling** with enhanced visual design
- **Dark theme support** (respects system preferences)
- **Accessibility features** (high contrast mode, focus indicators)
- **Mobile-responsive** design with touch-friendly sizing
- **Smooth animations** and hover effects
- **Custom translations** for pagination text (overwrites "« Previous" to "<" and "Next »" to ">")
- **Admin Meta Box** for multiple category selection in listdom-listing posts
- **Tag Creation** - allows creating new categories on the fly in admin
- **Helper Functions** for retrieving and displaying categories in themes

## Installation

1. Upload the `customize-ui.php` file to your WordPress plugins directory (`/wp-content/plugins/customize-ui/`)
2. Activate the plugin through the 'Plugins' menu in WordPress admin
3. The plugin will automatically start working on the frontend

## Usage

### Frontend Usage

Simply add the class `listdom-category` to any `<select>` element in your frontend:

```html
<select class="listdom-category">
    <option value="">Select a category...</option>
    <option value="1">Category 1</option>
    <option value="2">Category 2</option>
    <option value="3">Category 3</option>
</select>
```

### Admin Usage

The plugin automatically adds a "Listdom Categories" meta box to the listdom-listing post type in WordPress admin. Features include:

- **Multiple Selection**: Select multiple categories for each listing
- **Tag Creation**: Type to create new categories on the fly
- **Search & Filter**: Easily find existing categories
- **Visual Tags**: Selected categories appear as removable tags

### Theme Integration

Use the provided helper functions in your theme:

```php
// Get category IDs for a post
$category_ids = get_listdom_categories($post_id);

// Get category names for a post
$category_names = get_listdom_category_names($post_id);

// Display categories with custom separator
echo display_listdom_categories($post_id, ' | ');
```

## Configuration

The plugin uses the following Select2 configuration:

- **Placeholder**: "Select an option..."
- **Allow Clear**: Yes
- **Width**: 100%
- **Theme**: Classic

### Custom CSS Features

The plugin includes a comprehensive custom CSS file (`assets/css/customize-ui.css`) with:

- **Enhanced Visual Design**: Modern styling with rounded corners and smooth transitions
- **Hover & Focus States**: Interactive feedback with color changes and shadows
- **Dark Theme Support**: Automatically adapts to system dark mode preferences
- **Mobile Optimization**: Touch-friendly sizing and prevents zoom on iOS
- **Accessibility**: High contrast mode support and proper focus indicators
- **Loading States**: Visual feedback during AJAX operations
- **Disabled States**: Clear visual indication when elements are disabled

## Requirements

- WordPress 5.0 or higher
- jQuery (automatically loaded by WordPress)
- Internet connection (for CDN resources)

## Customization

### JavaScript Configuration

To customize the Select2 options, edit the `add_script()` method in the `customize-ui.php` file. You can modify:

- Placeholder text
- Theme
- Width
- Additional Select2 options

### CSS Customization

To customize the visual styling, edit the `assets/css/customize-ui.css` file. The CSS includes:

- **Color Variables**: Easy to modify primary colors and themes
- **Responsive Breakpoints**: Mobile-first design approach
- **Accessibility Features**: WCAG compliant contrast ratios
- **Animation Controls**: Customizable transition timings

### Translation Customization

The plugin includes custom translation overrides for the 'listdom' text domain:

- `« Previous` → `<`
- `Next »` → `>`
- `Previous` → `<`
- `Next` → `>`

To add more custom translations, edit the `custom_translations()` method in the `customize-ui.php` file.

### File Structure

```
customize-ui/
├── customize-ui.php          # Main plugin file
├── assets/
│   ├── css/
│   │   └── customize-ui.css  # Custom styling
│   └── js/
│       └── admin.js          # Admin JavaScript
├── README.md                 # Documentation
└── example.html             # Usage example
```

## Support

For support or feature requests, please contact the plugin author.

## License

GPL v2 or later
