/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

const $ = require('jquery');
require('mdb-ui-kit');
// any CSS you import will output into a single css file (app.scss in this case)
import '../styles/app.scss';
import '../styles/mdb-ui-kit.scss';

require('./../scripts/table');
// start the Stimulus application
import '../bootstrap';