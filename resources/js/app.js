require('./bootstrap');

require('alpinejs');

import Fuse from 'fuse.js';
window.Fuse = Fuse;

require('./helpers.js');
require('./components/suggestInput.js');
require('./components/expandableNotes.js');
require('./components/deleteForm.js');