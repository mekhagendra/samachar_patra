# Samachar Patra Theme v2.0.0

## Modern WordPress Theme Architecture

A professional Nepali news theme completely restructured with modern WordPress development practices and organized file architecture.

## 🏗️ New Structure Overview

```
samachar_patra/
│
├── assets/                    # All static assets
│   ├── css/                  # Stylesheets
│   │   ├── style.css         # Main theme styles
│   │   ├── editor-style.css  # Block editor styles
│   │   ├── admin-style.css   # Admin interface styles
│   │   ├── ads-admin.css     # Ads management styles
│   │   └── news-8col-widget.css
│   │
│   ├── js/                   # JavaScript files
│   │   ├── main.js          # Main theme scripts
│   │   ├── customizer.js    # Customizer preview
│   │   ├── ads-admin.js     # Ads management
│   │   ├── ads-frontend.js  # Frontend ads
│   │   ├── ads-tinymce.js   # TinyMCE integration
│   │   └── province-widget-tabs.js
│   │
│   ├── images/              # Theme images
│   ├── fonts/               # Custom fonts
│   └── scss/                # SCSS source files (future)
│
├── inc/                      # Modular PHP functionality
│   ├── core/                # Core theme functionality
│   │   ├── setup.php        # Theme setup, supports, menus
│   │   ├── enqueue.php      # Scripts & styles enqueuing
│   │   └── security.php     # Security headers & measures
│   │
│   ├── customizer/          # WordPress Customizer
│   │   ├── register.php     # Customizer settings
│   │   └── controls.php     # Custom controls
│   │
│   ├── api/                 # REST API endpoints (future)
│   ├── blocks/              # Custom blocks (future)
│   ├── acf/                 # Advanced Custom Fields (future)
│   └── helpers.php          # Utility functions
│
├── templates/               # Template organization
│   ├── parts/              # Reusable layout partials
│   │   ├── header/         # Header components
│   │   │   └── header.php  # Main header
│   │   ├── footer/         # Footer components  
│   │   │   └── footer.php  # Main footer
│   │   ├── sidebar/        # Sidebar components (future)
│   │   └── navigation/     # Navigation components (future)
│   │
│   ├── components/         # Small UI components
│   │   └── interview.php   # Interview display component
│   │
│   ├── layouts/           # Page layout wrappers (future)
│   └── views/             # Advanced templating (future)
│
├── languages/             # Translation files (.po/.mo)
├── widgets/              # Custom widgets (legacy location)
├── includes/             # Legacy includes (to be migrated)
│
├── theme.json            # WordPress block theme configuration
├── functions.php         # Modern streamlined loader
├── style.css            # Theme metadata & styles
├── screenshot.png       # Theme screenshot
│
├── index.php            # Main template
├── header.php           # Header loader
├── footer.php           # Footer loader
├── single.php           # Single post template
├── archive.php          # Archive template
└── 404.php             # Error page template
```

## 🚀 Major Improvements

### 1. **Organized File Structure**
- **Separated concerns**: Assets, functionality, and templates in dedicated directories
- **Modular PHP**: Functions split into logical files (setup, enqueue, security, etc.)
- **Asset organization**: CSS, JS, images properly organized in `assets/`

### 2. **Modern WordPress Features**
- **theme.json**: Full Site Editing (FSE) support with custom color palettes, typography, and spacing
- **Block Editor**: Enhanced support for Gutenberg blocks with custom styles
- **Security**: Comprehensive security headers and measures
- **Performance**: Optimized asset loading with preloading for critical resources

### 3. **Developer Experience**
- **Clean functions.php**: Streamlined to only load organized modules
- **Component system**: Reusable template parts in organized structure  
- **Helper functions**: Centralized utilities for dates, views, pagination
- **Debug tools**: Development dashboard widgets and error handling

### 4. **Backward Compatibility**
- **Legacy support**: Existing widgets and functionality preserved
- **Migration path**: Old component references automatically redirected
- **Gradual upgrade**: Can migrate features piece by piece

## 📁 Key Files Explained

### Core Files

#### `functions.php`
- Modern, streamlined loader
- Organized includes from `inc/` directory
- Backward compatibility for existing functionality
- Version 2.0.0 with proper constants and structure

#### `theme.json`
- WordPress block theme configuration
- Custom color palette (Primary, Secondary, Accent)
- Typography settings with Nepali fonts (Roboto, Mukti)
- Spacing and layout configurations
- Block-specific styling

#### `inc/core/setup.php`
- Theme supports (thumbnails, menus, HTML5, etc.)
- Custom image sizes
- Widget areas registration
- Navigation walker
- Category creation

#### `inc/core/enqueue.php`
- Organized script and style loading
- Performance optimizations (preloading)
- Admin-specific assets
- Font loading with fallbacks

#### `inc/core/security.php`
- Security headers (CSP, XSS protection, etc.)
- Login protection
- XML-RPC disabling
- File upload sanitization

### Template Files

#### `templates/parts/header/header.php`
- Complete header with navigation
- Logo and branding support
- Mobile responsive design
- Advertisement integration

#### `templates/parts/footer/footer.php`  
- 4-column footer layout
- Widget areas
- Social links
- Copyright information

#### `templates/components/interview.php`
- Interview section display
- 3-column responsive grid
- Custom post queries
- Embedded styling

## 🔧 Usage Guide

### Loading Components

```php
// New method - organized structure
get_template_part('templates/parts/header/header');
get_template_part('templates/components/interview');

// Helper function with data
samacharpatra_component('parts/header/header', array('data' => 'value'));

// Check if component exists
if (samacharpatra_component_exists('components/interview')) {
    samacharpatra_component('components/interview');
}
```

### Asset URLs

```php
// CSS files
get_template_directory_uri() . '/assets/css/style.css'

// JavaScript files  
get_template_directory_uri() . '/assets/js/main.js'

// Images
get_template_directory_uri() . '/assets/images/logo.png'
```

### Theme Options

```php
// Get customizer option with default
$primary_color = samacharpatra_get_option('primary_color', '#0073aa');

// Check theme support
if (samacharpatra_supports('post-thumbnails')) {
    // Use thumbnails
}
```

## 🎨 Customization

### Colors (theme.json)
- **Primary**: #0073aa (Blue)
- **Secondary**: #005177 (Dark Blue)  
- **Accent**: #ff6b35 (Orange)
- **Grays**: Light, Medium, Dark variations

### Typography
- **Body Font**: Roboto
- **Heading Font**: Mukti (Nepali support)
- **System Font**: Available as fallback
- **Font Sizes**: Small (14px) to Gigantic (48px)

### Spacing
- **Content Width**: 1200px
- **Wide Width**: 1400px
- **Block Gap**: 1.5rem default

## 🔄 Migration Guide

### From v1.0 to v2.0

1. **Backup**: Old `functions.php` saved as `functions-old.php`
2. **Assets**: Moved from root/css/js to `assets/` directory
3. **Components**: Moved from `components/` to `templates/` structure
4. **Includes**: Split into organized `inc/` files

### Compatibility Notes
- All existing widgets continue to work
- Template references automatically redirected
- Customizer settings preserved
- No content or configuration loss

## 🔍 Development Features

### Debug Mode (WP_DEBUG = true)
- Component error display
- Dashboard widget showing all available components
- Asset loading debug information
- Security header testing

### Helper Functions
```php
// Nepali date conversion
$nepali_date = samacharpatra_ad_to_bs('2024-10-24');
echo samacharpatra_format_nepali_date('2024-10-24', 'full');

// Post views
samacharpatra_track_post_views(get_the_ID());
echo samacharpatra_get_post_views(get_the_ID());

// Breadcrumbs
samacharpatra_breadcrumbs();

// Pagination
echo samacharpatra_custom_pagination();
```

## 📊 Performance Optimizations

### Asset Loading
- **Preloading**: Critical CSS and fonts
- **Fallbacks**: Multiple CDN sources for external assets
- **Minification**: Ready for production minification
- **Caching**: File modification time-based versioning

### Security Features
- **Headers**: CSP, XSS protection, frame options
- **Login Protection**: Rate limiting, error message hiding
- **File Security**: Upload sanitization, direct access prevention
- **XML-RPC**: Disabled for security

## 🌐 Modern WordPress Support

### Block Editor
- **Wide blocks**: Proper alignment support
- **Color palette**: Theme colors available in editor
- **Typography**: Font options in block settings
- **Spacing**: Consistent spacing controls

### Full Site Editing (FSE)
- **Template parts**: Header, footer, sidebar defined
- **Custom templates**: Blank, full-width options
- **Block patterns**: Ready for pattern registration
- **Global styles**: Comprehensive styling system

## 📝 Future Enhancements

### Planned Features
- **SCSS Integration**: Compile SCSS from `assets/scss/`
- **Custom Blocks**: News-specific Gutenberg blocks
- **ACF Integration**: Advanced Custom Fields support
- **REST API**: Custom endpoints for news data
- **Performance**: Advanced caching and optimization
- **PWA**: Progressive Web App features

### Migration Roadmap
1. **Phase 1**: ✅ Core structure and assets (Complete)
2. **Phase 2**: Advanced customizer controls and blocks
3. **Phase 3**: API endpoints and ACF integration  
4. **Phase 4**: Performance optimization and PWA features

## 🆘 Support & Documentation

### Getting Help
- Check `COMPONENTS.md` for component usage
- Enable `WP_DEBUG` for development insights  
- Review dashboard widgets for component info
- Check error logs for component loading issues

### File Locations
- **Old backup**: `functions-old.php`
- **Component docs**: `COMPONENTS.md`
- **Debug info**: Admin Dashboard → Theme Components widget
- **Error logs**: WordPress debug log

## 📋 Changelog

### Version 2.0.0 (Current)
- ✅ Complete file structure reorganization
- ✅ Modern WordPress block editor support
- ✅ Enhanced security implementation
- ✅ Component-based architecture
- ✅ theme.json configuration
- ✅ Performance optimizations
- ✅ Backward compatibility maintained

### Version 1.0.0 (Legacy)
- Basic news theme functionality
- Single functions.php file
- Components in root-level directory
- Limited block editor support

---

**Samachar Patra v2.0.0** - Modern, organized, and future-ready WordPress theme architecture. 🚀