let vex = require('vex-js');
vex.registerPlugin(require('vex-dialog'));
vex.defaultOptions.className = 'vex-theme-plain';
vex.dialog.buttons.YES.text = 'بله';
vex.dialog.buttons.NO.text = 'خیر';


export default vex;