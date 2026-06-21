const fs = require('fs');
const glob = require('glob');

glob.sync('resources/js/Pages/**/*.vue').forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    
    // Some are multiline:
    // <PageHeader
    //      title="Users"
    //      description="..."
    
    // Let's try to match title="..."
    const titleMatch = content.match(/title="([^"]+)"/);
    if (titleMatch) {
        let title = titleMatch[1];
        
        // Build breadcrumbs
        // Let's deduce parent from title or route
        // E.g., Users -> [{label: 'Admin', url: '/admin'}, {label: 'Users'}]
        // Create User -> [{label: 'Admin', url: '/admin'}, {label: 'Users', url: '/admin/users'}, {label: 'Create'}]
        // This might be tricky to guess URLs for all.
    }
});
