import os
import re

directories = [
    'app/Http/Controllers',
    'app/Services'
]

# Mapping function names to generic short comments for controllers
controller_comments = {
    '__construct': 'Initialize dependencies',
    'index': 'Display listing of resource',
    'create': 'Show form for creating resource',
    'store': 'Store newly created resource',
    'show': 'Display specified resource',
    'edit': 'Show form for editing resource',
    'update': 'Update specified resource',
    'destroy': 'Remove specified resource from storage',
}

def process_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    lines = content.split('\n')
    new_lines = []
    
    in_docblock = False
    docblock_buffer = []
    
    for i, line in enumerate(lines):
        stripped = line.strip()
        
        if stripped == '/**':
            in_docblock = True
            docblock_buffer = [line]
            continue
            
        if in_docblock:
            docblock_buffer.append(line)
            if stripped == '*/':
                in_docblock = False
                next_line_idx = i + 1
                while next_line_idx < len(lines) and lines[next_line_idx].strip() == '':
                    next_line_idx += 1
                
                if next_line_idx < len(lines):
                    next_line = lines[next_line_idx].strip()
                    if next_line.startswith('public function') or next_line.startswith('protected function') or next_line.startswith('private function'):
                        # Skip adding docblock, we'll replace with // #
                        docblock_buffer = []
                        pass
                    else:
                        new_lines.extend(docblock_buffer)
                        docblock_buffer = []
            continue
            
        new_lines.append(line)
        
    content = '\n'.join(new_lines)
    
    # We want to replace existing // # comments to avoid duplicates or replace old ones if we run again?
    # Actually if they already have // #, we don't need to add it again, but since our script might have missed some or we are running on new files:
    
    # regex for public function. We will match the previous line to see if it's already a // # comment
    pattern = re.compile(r'(?:[ \t]*//[^\n]*\n)*([ \t]*)(public|protected|private)\s+function\s+([a-zA-Z0-9_]+)')
    
    def repl(m):
        indent = m.group(1)
        visibility = m.group(2)
        func_name = m.group(3)
        
        if 'Controller' in filepath:
            comment_text = controller_comments.get(func_name, f'Handle {func_name} action')
        else:
            comment_text = func_name.replace('_', ' ').title()
            words = re.findall(r'[A-Z]?[a-z]+|[A-Z]+(?=[A-Z][a-z]|\d|\W|$)|\d+', func_name)
            if words:
                words[0] = words[0].capitalize()
                comment_text = ' '.join(words)
                
            if comment_text.startswith('Get '):
                comment_text = 'Retrieve ' + comment_text[4:]
        
        return f'{indent}// # {comment_text}\n{indent}{visibility} function {func_name}'
        
    new_content = pattern.sub(repl, content)
    
    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print(f"Updated {filepath}")

for d in directories:
    if os.path.exists(d):
        for root, dirs, files in os.walk(d):
            for file in files:
                if file.endswith('.php'):
                    process_file(os.path.join(root, file))

