require('./config$');

function success() {
require('../..//app');
require('../..//pages/list/list');
require('../..//pages/canvas/canvas');
require('../..//pages/test/test');
require('../..//pages/setphone/setphone');
require('../..//pages/application/application');
require('../..//pages/myswards/myswards');
require('../..//pages/myswardsdetail/raise/raise');
require('../..//pages/myswardsdetail/noprize/noprize');
require('../..//pages/myswardsdetail/waitprize/waitprize');
}
self.bootstrapApp ? self.bootstrapApp({ success }) : success();
